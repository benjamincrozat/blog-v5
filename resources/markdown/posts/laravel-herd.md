---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/45/CleanShot_2023-07-19_at_23.14.36_2x_xazzjv.jpg
Title: "Laravel Herd: the simplest way to install PHP on your Mac"
Description: "Laravel Herd is a native macOS app that makes it even easier than Laravel Valet to get started with the framework."
Published at: 2023-07-19
Modified at: 2023-08-12
Categories: laravel, tools
---

## What is Laravel Herd?

[Laravel Herd](https://herd.laravel.com) is a *free* native macOS app that makes it even easier than [Laravel Valet](https://benjamincrozat.com/install-php-mac-laravel-valet) to get started with Laravel. It includes everything you need for a local development environment such as PHP, Nginx, and Dnsmasq.

It was introduced at [Laracon US](https://laracon.us) in July 19, 2023 and developed by [Beyond Code](https://beyondco.de) for [Laravel LLC](https://laravel.com).

## How to install PHP on your Mac using Laravel Herd

1. [Download Herd](https://herd.laravel.com/download) on the official website.
2. Open the Disk Image and drag the application into your */Applications* folder.
3. Launch it and follow the instructions. You can set it up from scratch or import your Laravel Valet configuration.

That's it. Unlike with Laravel Valet (I wrote about it [here](https://benjamincrozat.com/install-php-mac-laravel-valet)), there is no need to install Homebrew (a package manager) and mess with all the dependencies. It just works out of the box.

Make sure everything works by running the following commands:

```bash
php --version
laravel --version
composer --version
```

## What PHP versions does Laravel Herd support?

Laravel Herd supports the following versions of PHP:
- PHP 7.4
- PHP 8.0
- PHP 8.1
- PHP 8.2
- PHP 8.3

## Which PHP extensions are included?

Laravel Herd includes the following PHP extensions:
- bcmath
- bz2
- calendar
- ctype
- curl
- dba
- dom
- exif
- ffi
- fileinfo
- filter
- ftp
- gd
- gmp
- iconv
- imagick
- intl
- mbstring
- mysqli
- opcache
- openssl
- pcntl
- pdo
- pdo_mysql
- pdo_pgsql
- pdo_sqlite
- pgsql
- phar
- posix
- readline
- redis
- session
- shmop
- simplexml
- soap
- sockets
- sodium
- sqlite3
- sysvmsg
- sysvsem
- sysvshm
- tokenizer
- xml
- xmlreader
- xmlwriter
- zip
- zlib

## The strengths of Laravel Herd compared to Valet

- Everything is bundled together. I thought Laravel Valet was simple, but Herd is even simpler.
- The PHP binaries embedded in Laravel Herd are compiled to run super fast on your Apple silicon Mac. So you will have a significant performance boost.

## The limitations of Laravel Herd compared to Valet

Using Laravel Herd, you will encounter some limitations, and this is where you will want to [switch to Laravel Valet](https://benjamincrozat.com/install-php-mac-laravel-valet).

- You cannot install PHP versions before PHP 7.4.
- Although Herd included many, you cannot add PHP extensions.

## Will there be a Windows or Linux version?

According to the developer, **there isn't a Windows or Linux version planned and never will.**

