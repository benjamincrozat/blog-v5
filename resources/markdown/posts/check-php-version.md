---
id: "01KKEW2780NK2M3H3CFCG87HN6"
title: "How to check your PHP version and active binary"
slug: "check-php-version"
author: "benjamincrozat"
description: "Check the PHP version used by your terminal, web server, Laravel app, Docker container, or Herd, then find the active binary and php.ini file."
categories:
  - "laravel"
  - "php"
published_at: 2023-09-01T22:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/4CD3Od4OzsLNBbQ.png"
sponsored_at: null
---
**Run `php -v` to check the PHP version used by your terminal:**

```bash
php -v
```

The first line looks like this:

```text
PHP 8.5.9 (cli) ...
```

That answers the common question. But it does not prove that your website, queue worker, or Docker container uses the same PHP installation.

The latest stable release is PHP 8.5.9 as I update this guide. I keep the current stable and preview releases on my [latest PHP version page](/latest-php-version).

## Pick the command that matches where PHP runs

| What you need to inspect | Command or method |
| --- | --- |
| PHP used by your terminal | `php -v` |
| First PHP binary in your shell path | `command -v php` on macOS/Linux or `(Get-Command php).Source` in PowerShell |
| Every PHP binary in your shell path | `which -a php` on macOS/Linux or `where.exe php` on Windows |
| Loaded CLI configuration | `php --ini` |
| PHP used by a website | A temporary script using `PHP_VERSION` and `PHP_SAPI` |
| PHP used by Laravel | `php artisan about` |
| PHP inside Docker Compose | `docker compose exec app php -v` |
| PHP selected by Laravel Herd | `herd which-php` and `herd php -v` |

Replace `app` in the Docker command with the PHP service name from your `compose.yaml` file.

## Check the PHP binary, SAPI, and configuration together

Version mismatches are easier to diagnose when you print more than the version number:

```bash
php -r 'printf("PHP %s | SAPI: %s | Binary: %s\n", PHP_VERSION, PHP_SAPI, PHP_BINARY);'
```

You should see output similar to this:

```text
PHP 8.5.9 | SAPI: cli | Binary: /opt/homebrew/bin/php
```

Then ask PHP which configuration files it loaded:

```bash
php --ini
```

For a shorter answer on macOS or Linux:

```bash
php -i | grep "Loaded Configuration File"
```

PowerShell has an equivalent:

```powershell
php -i | Select-String "Loaded Configuration File"
```

This matters because editing a `php.ini` file that your active PHP process never loads changes nothing. My [php.ini location guide](/php-ini-location) covers the common paths.

## Check the PHP version used by your website

Your web server may use PHP-FPM while the terminal uses another CLI binary. Create a temporary PHP file in the public directory:

```php
<?php

header('Content-Type: text/plain');

echo 'PHP: ' . PHP_VERSION . PHP_EOL;
echo 'SAPI: ' . PHP_SAPI . PHP_EOL;
echo 'Binary: ' . PHP_BINARY . PHP_EOL;
echo 'php.ini: ' . (php_ini_loaded_file() ?: 'none') . PHP_EOL;
```

Open the file in your browser and compare its output with the CLI commands above. Delete the file as soon as you finish.

Use `phpinfo()` only when you need the complete configuration and extension list:

```php
<?php

phpinfo();
```

That page exposes far more server information, so it should also be temporary.

## Check PHP in a Laravel project

Laravel gives you the PHP and framework versions in one command:

```bash
php artisan about
```

The command still runs through the PHP binary selected by your shell. If the website behaves differently, compare it with the temporary browser script rather than assuming both environments match.

You can check the framework separately with my [Laravel version guide](/check-laravel-version).

Composer can also tell you whether the current PHP binary and extensions satisfy the installed packages:

```bash
composer check-platform-reqs
```

That is more useful than the version number alone after changing PHP versions.

## Check PHP in Docker

Running `php -v` on your computer checks the host, not the container. Run it inside the PHP service instead:

```bash
docker compose exec app php -v
docker compose exec app php --ini
```

If the service is not running, inspect a new container:

```bash
docker compose run --rm app php -v
```

Again, replace `app` with your actual PHP service name.

## Check PHP in Laravel Herd

Herd can manage several PHP versions, so start by checking which binary it selected:

```bash
herd which-php
herd php -v
```

If plain `php -v` disagrees with `herd php -v`, your shell path is pointing somewhere else. The [Laravel Herd guide](/laravel-herd) covers switching global and per-site versions.

## When CLI and browser versions disagree

Do not upgrade random installations until you know which runtime is wrong. Compare these four facts:

| Runtime | Version | SAPI | Loaded configuration |
| --- | --- | --- | --- |
| Terminal | `php -v` | `php -r 'echo PHP_SAPI;'` | `php --ini` |
| Website | `PHP_VERSION` | `PHP_SAPI` | `php_ini_loaded_file()` |
| Laravel command | `php artisan about` | Usually `cli` | Same as the selected CLI binary |
| Docker service | `docker compose exec app php -v` | Usually `cli` | Run `php --ini` inside the same container |

Common causes are:

- Homebrew, Herd, Valet, XAMPP, or a system PHP binary appears first in your shell path.
- The web server still points to an older PHP-FPM service.
- A long-running queue worker was not restarted after the PHP change.
- The command ran on the host instead of inside the container.
- CLI and PHP-FPM load different `php.ini` files.

Once those four facts match, you know you are changing the PHP runtime that actually handles the request.

Related guides:

- [Check the latest stable PHP version and support dates](/latest-php-version)
- [Show every PHP error while debugging](/php-show-all-errors)
- [Check which Laravel version is running](/check-laravel-version)
- [Set up Laravel and PHP with Herd](/laravel-herd)
