---
id: "01KKEW2780NK2M3H3CFCG87HN6"
title: "Check PHP version: command line and browser methods"
slug: "check-php-version"
author: "benjamincrozat"
description: "Check your PHP version with php -v, phpinfo(), phpversion(), or Laravel's php artisan about command, and know which method fits CLI or browser access."
categories:
  - "laravel"
  - "php"
published_at: 2023-09-02T00:00:00+02:00
modified_at: 2026-03-14T10:17:05Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/4CD3Od4OzsLNBbQ.png"
sponsored_at: null
---
## Introduction

**To check your [PHP](https://www.php.net) version, run `php -v` in your terminal.** The first line shows your current version immediately.

If you cannot use the terminal, `phpinfo()`, `phpversion()`, and Laravel's `php artisan about` command are the next easiest options.

## Run `php -v` in your terminal

This method works perfectly on macOS, Linux, Windows, and WSL.

```bash
php -v
```

This outputs something like `PHP 8.4.4 (cli) (built: ...)`.

## Use the `phpversion()` function

![Checking PHP version using phpversion().](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/check-php-version-068c21630df8ec212ffd.jpg/public)

Simply create a PHP script containing:

```php
<?php echo phpversion(); ?>
// Output: 8.4.4
```

## Use the `phpinfo()` function

![Checking PHP version using phpinfo().](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/check-php-version-b4f57c73364e57eadefb.jpg/public)

Create a PHP file with:

```php
<?php phpinfo(); ?>
```

Open this in your browser and find the PHP version at the top.

## Check PHP version using Laravel's welcome page

![Laravel welcome page showing PHP version.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/check-php-version-061c7222686bd6a5e33c.jpg/public)

Laravel conveniently shows your PHP version in the bottom-right corner of the default welcome page.

## Check PHP version with Laravel Artisan

![Checking PHP version using Laravel Artisan.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/check-php-version-c2831ffe38082032d779.jpg/public)

From your Laravel project's root, run:

```bash
php artisan about
```

You'll see your PHP version along with other useful details.
If you also need the framework version, here are [6 ways to check Laravel's version](/check-laravel-version).

## FAQ

### How do I check my PHP version on macOS?

Run `php -v` in your macOS terminal.

### How do I check my PHP version on Ubuntu?

Run `php -v` in your Ubuntu terminal.

### How do I check my PHP version on Windows?

Run `php -v` in your Windows command prompt.

### Which PHP versions are end-of-life (EOL) in 2026?

PHP 7.x and older are long end-of-life, and even older PHP 8 branches may already be out of active support depending on the exact minor version. Check the official [supported versions page](https://www.php.net/supported-versions.php) before you plan an upgrade path.

### How do I find my PHP version in WordPress?

In the admin panel, go to **Tools → Site Health → Info → Server**.

### Can I have multiple PHP versions installed?

Yes! Tools like [Laravel Herd](/laravel-herd), [Laravel Valet](/laravel-valet), [Homebrew](https://brew.sh) (macOS), [Docker](https://www.docker.com), and version managers allow multiple PHP versions on the same system.

Once you know which PHP version you are really on, these next reads help with local setup, config, and the Laravel side around it:

- [Run PHP and Laravel more smoothly on macOS](/laravel-valet)
- [Set up PHP on macOS or Windows with less friction](/laravel-herd)
- [Find the php.ini file that's actually affecting your setup](/php-ini-location)
- [Double-check which Laravel version is actually running](/check-laravel-version)
- [Set up Laravel on macOS without a messy local stack](/laravel-installation-macos)
