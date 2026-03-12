---
id: "01KKEW278CH0D2CYH3EQM587YN"
title: "How to create a custom filesystem adapter in Laravel"
slug: "custom-filesystem-adapter-laravel"
author: "benjamincrozat"
description: "Learn how to craft your own filesystem adapter in Laravel."
categories:
  - "laravel"
published_at: 2023-08-30T00:00:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/TislxRU6QkhCQSV.jpg"
sponsored_at: null
---
## Introduction

Laravel, with the help of Frank de Jonge's [Flysystem PHP package](https://github.com/thephpleague/flysystem), offers a simple and consistent API to work with various filesystems like local, SFTP, Amazon S3, and more.

It also makes it a breeze to extend and offer new custom filesystems to your app. Let's see how it works.

## Create a custom filesystem adapter in Laravel, step by step

To create a filesystem adapter in Laravel, you will need to do the following:

1. Create a new class that implements the `League\Flysystem\FilesystemAdapter` interface. This class, let say `App\Filesystem\CustomAdapter`, should implement all of the methods defined in the contract, such as `write()`, `read()`, `delete()`, and more.

2. Once you have implemented all the methods of your custom filesystem adapter, you can register it with Laravel in `app/Providers/AppServiceProvider.php`

3. Then, add an entry to the disks array in *config/filesystems.php*.

4. Finally, you can use your custom file system adapter in your Laravel application. Leverage the `Storage` Facade for convenience.

OK, let's see how all this looks like.

Here's an example of what a custom file system adapter class looks like:

```php
namespace App\Filesystem;

use League\Flysystem\Config;
use League\Flysystem\FilesystemAdapter;

class CustomAdapter implements FilesystemAdapter
{
    public function write(string $path, string $contents, Config $config) : void
    {
        //
    }

    public function read(string $path) : string
    {
        //
    }

    public function delete(string $path) : void
    {
        //
    }

    // There are more methods to implement.
}
```

Next, register your custom file system with Laravel:

```php
namespace App\Providers;
 
use App\Filesystem\CustomAdapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Contracts\Foundation\Application;
 
class AppServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        Storage::extend('custom', function (Application $app, array $config) {
            return new FilesystemAdapter(
                new Filesystem(new CustomAdapter, $config),
                $adapter,
                $config
            );
        });
    }
}
```

Then, this is how you can register your custom filesystem adapter in the *config/filesystems.php* configuration file:

```php
'disks' => [
    'custom' => [
        'driver' => App\Filesystem\CustomAdapter::class,
    ],
],
```

And finally, you can use your custom filesystem adapter in your Laravel code like this:

```php
use Illuminate\Support\Facades\Storage;

Storage::disk('my-custom-driver')->put('lorem.txt', 'Lorem ipsum dolor sit amet.');
```

If "How to create a custom filesystem adapter in Laravel" was useful, these related posts make strong next steps:

- [Let's build a custom Laravel Pulse card](/custom-laravel-pulse-card)
- [Laravel Dropbox Driver package: how to install and use it](/laravel-dropbox-driver)
- [Validation in Laravel made easy](/laravel-validation)
- [How to install Laravel on macOS](/laravel-installation-macos)
- [Demystifying Artisan: Laravel's magical command tool](/laravel-artisan)
- [Laravel 10 is out! Here are every new features and changes.](/laravel-10)
- [The history of Laravel's versions (2011-2025)](/laravel-versions)
- [6 ways to check Laravel's version](/check-laravel-version)
- [Laravel 9: the mindful upgrade guide](/laravel-9-upgrade-guide)
- [Add Tailwind CSS to any Laravel project](/tailwind-css-laravel)

