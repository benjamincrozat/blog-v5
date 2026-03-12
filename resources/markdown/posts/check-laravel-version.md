---
id: "01KKEW277XY6E6M4TTXXW6XH50"
title: "6 ways to check Laravel's version"
slug: "check-laravel-version"
author: "benjamincrozat"
description: "Knowing which Laravel version you are running is important before you start writing code on a new project. There are multiple ways to do so."
categories:
  - "laravel"
published_at: 2022-09-10T00:00:00+02:00
modified_at: 2026-03-12T18:37:53Z
serp_title: "6 ways to check Laravel's version (2025)"
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBTZDC7PDNW3W8W7ZKZAYAEF.jpeg"
sponsored_at: null
---
## The quickest way to check your Laravel version

**The quickest way I know to check your Laravel version is by using the `php artisan --version` command.**

Knowing which version of Laravel you are running and its [specific details](/laravel-versions) are crucial pieces of information, whether you're planning to upgrade, debug, or simply want to ensure compatibility with a specific feature.

However, there are other methods to get the version of Laravel. Here's a comprehensive guide with commands that work no matter if you use macOS, any Linux distribution like Ubuntu, Docker or WSL (Windows Subsystem for Linux).

## Using the php artisan about command

The Artisan `about` command not only displays the Laravel version, it also shows other helpful information about your project such as the PHP version you're running, Composer's version, cache drivers, etc.
If Artisan still feels a bit fuzzy, start with [Demystifying Artisan](/laravel-artisan).

However, it's important to note that the about command is only available in Laravel version 9.21 or later.

```
php artisan about
  
Laravel Version ........................................................ 11.0.8
PHP Version ............................................................. 8.3.3
Composer Version ........................................................ 2.7.1
```

![The php artisan about command in Laravel.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/73/conversions/CleanShot_2023-04-21_at_12.03.38_2x_ydqmbj-medium.jpg)

If you also want to confirm the runtime shown in that output, here are [6 ways to check your version of PHP](/check-php-version).

## Using the version() method

The [`app()`](https://laravel.com/docs/helpers#method-app) helper will give you access to many information, such as the Laravel version you are running. Try this simple code below:

```php
// 11.0.8
app()->version();
```

You could use it in a custom dashboard you created:

```blade
<ul>
    <li>PHP: {{ phpversion() }}</li>
    <li>Laravel: {{ app()->version() }}</li>
</ul>
```

## Via Composer in your terminal

Composer offers a handy command to check the version of a specific dependency. Run:

```bash
composer show laravel/framework
```

You will get an incredibly lengthy report about this dependency.

```
name     : laravel/framework
descrip. : The Laravel Framework.
keywords : framework, laravel
versions : * v11.0.8
released : 2024-03-21, this week
type     : library
license  : MIT License (MIT) (OSI approved) https://spdx.org/licenses/MIT.html#licenseText
homepage : https://laravel.com
source   : [git] https://github.com/laravel/framework.git 0379a7ccb77e2029c43ce508fa76e251a0d68fce
dist     : [zip] https://api.github.com/repos/laravel/framework/zipball/0379a7ccb77e2029c43ce508fa76e251a0d68fce 0379a7ccb77e2029c43ce508fa76e251a0d68fce
…
```

![The command `composer show` in action to check the version of Laravel.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/191/conversions/hLcwhyE2sPfLN94vR633i9MKlI8865-metaQ2xlYW5TaG90IDIwMjMtMTAtMDkgYXQgMTYuNDAuMzBAMngucG5n--medium.jpg)

## In the composer.json and composer.lock files

In your *composer.json*, you will be able to get the minimum version of Laravel your project is locked on:

```json
"require": {
    "php": "^8.2",
    "laravel/framework": "^11.0",
    "laravel/tinker": "^2.9"
},
```

As you can see, this project is locked on Laravel 11.0.8 or earlier.

But this might not be enough. Since versions earlier than 11.0.8 are supported, your project might use Laravel 11.0.33 or even 11.1.22!

Instead, search for "laravel/framework" inside your *composer.lock* file to get the exact Laravel version that's installed on your project :

```json
{
    "name": "laravel/framework",
    "version": "v11.0.8",
    "source": {
        "type": "git",
        "url": "https://github.com/laravel/framework.git",
        "reference": "0379a7ccb77e2029c43ce508fa76e251a0d68fce"
    },
}
```

## In the source code

Open your favorite code editor and search for *vendor/laravel/framework/src/Illuminate/Foundation/Application.php*. The exact version of Laravel you are using is written in the `VERSION` constant.

```php
class Application extends Container implements ApplicationContract, CachesConfiguration, CachesRoutes, HttpKernelInterface
{
    const VERSION = '11.0.8';
}
```

This is actually the constant `app()->version()` uses. 😀

```php
public function version()
{
    return static::VERSION;
}
```

Once you know which Laravel version is actually running, these next reads help with upgrades, release history, and the rest of your setup:

- [The history of Laravel's versions (2011-2025)](/laravel-versions)
- [Laravel 11: an easy and step by step upgrade guide](/laravel-11-upgrade-guide)
- [Laravel 10: the upgrade guide from version 9](/laravel-10-upgrade-guide)
- [6 ways to check your version of PHP](/check-php-version)
- [Demystifying Artisan: Laravel's magical command tool](/laravel-artisan)
- [PHP for Mac: get started fast using Laravel Valet](/laravel-valet)
