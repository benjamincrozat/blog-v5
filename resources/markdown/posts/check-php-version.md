---
id: "01KKEW2780NK2M3H3CFCG87HN6"
title: "How to check your PHP version quickly"
slug: "check-php-version"
author: "benjamincrozat"
description: "Check your PHP version with php -v, phpversion(), phpinfo(), or Laravel's artisan about command, depending on whether you are in CLI, browser, or a Laravel app."
categories:
  - "laravel"
  - "php"
published_at: 2023-09-02T00:00:00+02:00
modified_at: 2026-03-19T22:39:10Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/4CD3Od4OzsLNBbQ.png"
sponsored_at: null
---
## Introduction

**To check your PHP version, run `php -v` in your terminal.**

That is the fastest and most reliable method when you have shell access.

If you do not have terminal access, use one of these instead:

- `phpversion()` for a tiny browser-safe output
- `phpinfo()` if you need full configuration details
- `php artisan about` inside a Laravel project

## Check PHP version in the CLI

For most people, this is the right answer.

```bash
php -v
```

You will get output like this:

```text
PHP 8.4.16 (cli) ...
```

That first line is enough if you only want the current version.

### Why `php -v` is usually the best method

- it is fast
- it works on macOS, Linux, Windows, and WSL
- it tells you which PHP binary your shell is actually using

If you have multiple PHP versions installed, the terminal result may not match the version your web server is using. That is where the browser methods below help.

## Check PHP version in the browser

If you need to know what the web server is running, use `phpversion()` or `phpinfo()`.

### Use `phpversion()` for the cleanest output

Create a temporary PHP file with:

```php
<?php

echo phpversion();
```

That prints only the version string, which makes it the cleaner browser option when you do not need extra details.

The PHP manual describes `phpversion()` as the function that gets the current PHP version.

### Use `phpinfo()` when you also need configuration details

Create a file like this:

```php
<?php

phpinfo();
```

Open it in your browser and look at the top of the page.

This is useful when you need more than the version number, for example:

- loaded extensions
- active `php.ini`
- SAPI details
- environment information

The tradeoff is that `phpinfo()` exposes a lot more information, so do not leave that file publicly accessible after you are done.

## Check PHP version in Laravel

If you are already inside a Laravel project, this is the easiest framework-aware command:

```bash
php artisan about
```

You will see the PHP version alongside your Laravel version and other environment details.

If you want the framework version too, here is the companion guide:

[Ways to check which Laravel version you are running](/check-laravel-version)

## Why the browser and CLI versions sometimes differ

This confusion is extremely common.

Your terminal may use one PHP binary while Apache, nginx, PHP-FPM, Valet, or Herd uses another.

If that happens:

1. run `php -v` in the terminal
2. check the browser version with `phpversion()` or `phpinfo()`
3. compare the results

If they are different, you are looking at two different PHP runtimes.

That is usually a configuration issue, not a PHP bug.

## Which method should you use?

Use this simple rule:

| Situation | Best method |
| --- | --- |
| You have terminal access | `php -v` |
| You need the web-server version | `phpversion()` |
| You need config details too | `phpinfo()` |
| You are already in a Laravel app | `php artisan about` |

## FAQ

### How do I check my PHP version on macOS?

Run:

```bash
php -v
```

### How do I check my PHP version on Ubuntu or Debian?

Run:

```bash
php -v
```

### How do I check my PHP version on Windows?

Run the same command in Command Prompt, PowerShell, or Windows Terminal:

```powershell
php -v
```

### How do I check the PHP version used by my website?

Use a temporary file with `phpversion()` or `phpinfo()` and open it in the browser. That shows the PHP runtime behind the web server, not just the one in your shell.

### Which PHP versions are still supported in 2026?

Check the official [PHP supported versions page](https://www.php.net/supported-versions.php) before planning an upgrade. As of March 19, 2026, PHP **8.5** and **8.4** are in active support, while older branches are further along in their lifecycle.

If you know your PHP version now and need the next step, these are the follow-up reads I would open:

- [Find the `php.ini` file that is actually affecting your setup](/php-ini-location)
- [Check which Laravel version is running too](/check-laravel-version)
- [Set up PHP on macOS or Windows with less friction](/laravel-herd)
- [Run PHP and Laravel more smoothly on macOS](/laravel-valet)
- [Set up Laravel on macOS without a messy local stack](/laravel-installation-macos)
