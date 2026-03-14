---
id: "01KKEW277XY6E6M4TTXXW6XH50"
title: "Check Laravel version: the quickest commands"
slug: "check-laravel-version"
author: "benjamincrozat"
description: "Check your Laravel version with php artisan --version, php artisan about, Composer, composer.lock, or app()->version(), depending on what access you have."
categories:
  - "laravel"
published_at: 2022-09-10T00:00:00+02:00
modified_at: 2026-03-14T10:22:32Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBTZDC7PDNW3W8W7ZKZAYAEF.jpeg"
sponsored_at: null
---
## The quickest way to check your Laravel version

**To check your Laravel version quickly, run `php artisan --version`.**

That gives you the fastest answer. If you want more project details, use `php artisan about`. If Artisan is unavailable, Composer, `composer.lock`, and `app()->version()` are the most reliable fallbacks.

If that is not available or you want more context, there are several other reliable ways to get the Laravel version on macOS, Linux, Docker, and WSL.

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

![The php artisan about command in Laravel.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/check-laravel-version-fea4f5066c54954018e6.jpg/public)

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

![The command `composer show` in action to check the version of Laravel.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/check-laravel-version-6b3d388a32e6e4a7e68f.jpg/public)

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

If you are checking the version because an upgrade, package install, or local mismatch is coming next, these are the tabs worth opening after this one:

- [See where your version fits in Laravel's release history](/laravel-versions)
- [Check what changes before you move to Laravel 11](/laravel-11-upgrade-guide)
- [Plan a safer upgrade from Laravel 9 to 10](/laravel-10-upgrade-guide)
- [Check whether your PHP version is part of the problem](/check-php-version)
- [Use the Artisan command you just ran with more confidence](/laravel-artisan)
- [Switch PHP versions on macOS without breaking your flow](/laravel-valet)
