# Stage 1: Install PHP dependencies
FROM composer:2 AS vendor
WORKDIR /app

# Only copy Composer manifests to leverage Docker layer cache
COPY composer.json composer.lock ./
# Install PHP dependencies for production (no dev), skip scripts, ignore platform reqs (runtime has extensions)
RUN composer install --no-dev --prefer-dist --no-progress --no-interaction --optimize-autoloader --no-scripts --ignore-platform-reqs

# Stage 2: Build frontend assets
FROM node:20-alpine AS assets
WORKDIR /app

# Install Node dependencies using lockfile for reproducibility
COPY package.json package-lock.json ./
RUN npm ci
# Copy the rest of the application to build frontend assets
COPY . .

RUN npm run build

# Stage 3: Set up runtime environment
FROM php:8.4-fpm-alpine AS runtime

# Install system packages
RUN apk add --no-cache \
    nginx supervisor tzdata \
    icu-dev oniguruma-dev libzip-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev

# Install PHP extensions
ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN install-php-extensions @composer apcu bcmath calendar Core ctype curl date dom ev excimer exif \
    fileinfo filter ftp gd gettext gmp hash iconv igbinary imagick \
    imap intl json ldap libxml mbstring mongodb msgpack mysqli \
    mysqlnd openssl pcntl pcre PDO pdo_mysql pdo_pgsql pdo_sqlite pdo_sqlsrv \
    Phar posix pspell random readline redis Reflection session shmop \
    SimpleXML soap sockets sodium SPL sqlite3 sqlsrv standard tokenizer xml \
    xmlreader xmlwriter xsl OPcache zip zlib

# Config PHP-FPM
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# Minimal production-oriented PHP settings
RUN cat > "$PHP_INI_DIR/conf.d/99-production.ini" <<'INI'
opcache.enable=1
opcache.enable_cli=0
opcache.jit_buffer_size=0
opcache.validate_timestamps=0
opcache.memory_consumption=32
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=8000
memory_limit=128M
expose_php=0
cgi.fix_pathinfo=0
INI

# Tune PHP-FPM pool for low-memory instance
RUN { \
  echo "pm.max_children = 2"; \
  echo "pm.start_servers = 1"; \
  echo "pm.min_spare_servers = 1"; \
  echo "pm.max_spare_servers = 2"; \
  echo "pm.max_requests = 200"; \
  echo "request_terminate_timeout = 30s"; \
} >> /usr/local/etc/php-fpm.d/www.conf

RUN ln -sf /dev/stdout /var/log/nginx/access.log
RUN ln -sf /dev/stderr /var/log/nginx/error.log

# Config Nginx
RUN mkdir -p /run/nginx /etc/nginx/conf.d /var/log/nginx; \
  cat > /etc/nginx/nginx.conf <<'NGINX'
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log warn;
pid /run/nginx.pid;

events { worker_connections 1024; }

http {
  include       /etc/nginx/mime.types;
  default_type  application/octet-stream;

  log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                    '$status $body_bytes_sent "$http_referer" '
                    '"$http_user_agent" "$http_x_forwarded_for"';

  access_log  /var/log/nginx/access.log  main;
  sendfile        on;
  keepalive_timeout  65;
  server_tokens off;
  gzip on;
  gzip_types text/plain text/css application/json application/javascript application/xml+rss application/xml text/javascript image/svg+xml;

  server {
    listen 8080 default_server;
    listen [::]:8080 default_server;
    client_max_body_size 50m;

    server_name _;
    # Public web root served by Nginx
    root /var/www/html/public;
    index index.php;
    default_type application/octet-stream;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    charset utf-8;


    location / {
      # Try static files first, then fall back to Laravel front controller
      try_files $uri $uri/ /index.php?$query_string;
    }
    error_page 404 /index.php;

    location ~ \.php$ {
      # Forward PHP requests to PHP-FPM
      include fastcgi_params;
      fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
      fastcgi_param DOCUMENT_ROOT $realpath_root;
      fastcgi_index index.php;
      fastcgi_pass 127.0.0.1:9000;
      fastcgi_read_timeout 300;
    }

    location ~ /\.(?!well-known).* {
      # Block hidden files/directories (.env, .git, etc.)
      deny all;
    }
  }
}
NGINX

# Config Supervisor
RUN cat > /etc/supervisord.conf <<'SUP'
[supervisord]
nodaemon=true

[program:php-fpm]
command=/usr/local/sbin/php-fpm -F
autostart=true
autorestart=true
priority=10
stdout_logfile=/dev/fd/1
stdout_logfile_maxbytes=0

[program:nginx]
command=/usr/sbin/nginx -g "daemon off;"
autostart=true
autorestart=true
priority=20
stdout_logfile=/dev/fd/1
stdout_logfile_maxbytes=0

SUP

WORKDIR /var/www/html

COPY . ./
COPY --from=vendor /app/vendor ./vendor
# composer.lock not needed in runtime image
COPY --from=assets /app/public/build ./public/build

RUN mkdir -p storage bootstrap/cache; \
  chown -R www-data:www-data storage bootstrap/cache; \
  chmod -R ug+rwx storage bootstrap/cache

RUN set -eux; \
  if [ ! -f .env ]; then \
    if [ -f .env.example ]; then cp .env.example .env; else : > .env; fi; \
  fi

EXPOSE 8080

RUN chmod +x /var/www/html/entrypoint.sh

ENTRYPOINT ["/var/www/html/entrypoint.sh"]
