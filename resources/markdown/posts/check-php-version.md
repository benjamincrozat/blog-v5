---
id: "01KKEW2780NK2M3H3CFCG87HN6"
title: "6 ways to check your version of PHP"
slug: "check-php-version"
author: "benjamincrozat"
description: "Discover how to check your version of PHP using phpinfo(), your terminal, Laravel's welcome page, or a Laravel Artisan command."
categories:
  - "laravel"
  - "php"
published_at: 2023-09-02T00:00:00+02:00
modified_at: 2026-03-12T18:37:53Z
serp_title: "6 ways to check your version of PHP in 2025"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/4CD3Od4OzsLNBbQ.png"
sponsored_at: null
---
## Introduction

To quickly check your [PHP](https://www.php.net) version, open your terminal and run `php -v`. The first line shows your current PHP version (e.g., `PHP 8.3.1`).

Here's a detailed look at 6 easy methods:

## Run `php -v` in your terminal

This method works perfectly on macOS, Linux, Windows, and WSL.

```bash
php -v
```

This outputs "PHP 8.3.1 (cli) (built: Feb 10 2025 12:00:00)"

## Use the `phpversion()` function

![Checking PHP version using phpversion().](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/170/conversions/CleanShot_2023-09-02_at_16.49.10_2x_z0shyv-medium.jpg)

Simply create a PHP script containing:

```php
<?php echo phpversion(); ?>
// Output: 8.3.1
```

## Use the `phpinfo()` function

![Checking PHP version using phpinfo().](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/171/conversions/CleanShot_2023-09-02_at_16.17.14_2x_gkxt9j-medium.jpg)

Create a PHP file with:

```php
<?php phpinfo(); ?>
```

Open this in your browser and find the PHP version at the top.

## Check PHP version via Composer

If you use Composer, run this command:

```bash
composer --version
```

## Check PHP version using Laravel's welcome page

![Laravel welcome page showing PHP version.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/173/conversions/CleanShot_2023-09-02_at_16.13.39_2x_nz0b7t-medium.jpg)

Laravel conveniently shows your PHP version in the bottom-right corner of the default welcome page.

## Check PHP version with Laravel Artisan

![Checking PHP version using Laravel Artisan.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/174/conversions/CleanShot_2023-09-02_at_16.14.00_2x_klstth-medium.jpg)

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

### Which PHP versions are end-of-life (EOL) in 2025?

PHP 7.x and older are considered EOL in 2025. Upgrade to PHP 8.x to stay secure.

### How do I find my PHP version in WordPress?

In the admin panel, go to **Tools → Site Health → Info → Server**.

### Can I have multiple PHP versions installed?

Yes! Tools like [Laravel Herd](/laravel-herd), [Laravel Valet](/laravel-valet), [Homebrew](https://brew.sh) (macOS), [Docker](https://www.docker.com), and version managers allow multiple PHP versions on the same system.

Once you know which PHP version you are really on, these next reads help with local setup, config, and the Laravel side around it:

- [Run PHP and Laravel more smoothly on macOS](/laravel-valet)
- [Set up PHP on macOS or Windows with less friction](/laravel-herd)
- [This is the location of your php.ini](/php-ini-location)
- [Double-check which Laravel version is actually running](/check-laravel-version)
- [Set up Laravel on macOS without a messy local stack](/laravel-installation-macos)
