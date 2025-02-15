---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/23/dropbox_jvx5wo.png
Title: "Laravel Dropbox Driver package: how to install and use it"
Description: "Store and manage files on Dropbox and use it to back up your Laravel app automatically."
Published at: 2022-12-03
Modified at: 2022-12-20
Categories: laravel, packages
---

<img src="https://github.com/benjamincrozat/laravel-dropbox-driver/actions/workflows/run-tests.yml/badge.svg" class="inline" style="margin: 0" /> <img src="https://poser.pugx.org/benjamincrozat/laravel-dropbox-driver/v/stable" class="inline" style="margin: 0" /> <img src="https://poser.pugx.org/benjamincrozat/laravel-dropbox-driver/license" class="inline" style="margin: 0" /> <img src="https://poser.pugx.org/benjamincrozat/laravel-dropbox-driver/downloads" class="inline" style="margin: 0" />

Adding a new disk in the storage is easy. The only things I did was:
- Copy and paste code from the documentation and made it a package (https://laravel.com/docs/filesystem#custom-filesystems)
- Use the Flysystem adapter from Spatie, which Laravel is based on (https://github.com/spatie/flysystem-dropbox)

## Requirements

[Laravel Dropbox Driver](https://github.com/benjamincrozat/laravel-dropbox-driver) requires:
- **PHP 8.1+**
- **Laravel 9+**

## Installation

To install Laravel Dropbox Driver, run the command below:

```bash
composer require benjamincrozat/laravel-dropbox-driver
```

## Usage in your project

Add the following in *app/filesystems.php*:

```php
'disks' => [

    'dropbox' => [
        'driver' => 'dropbox',
        'token'  => env('DROPBOX_TOKEN'),
    ],

],
```

Then, in your *.env* file:

```
DROPBOX_TOKEN=your_access_token
```

### Get a token from Dropbox

Log in to your Dropbox account and create a new application to generate your access token.

https://www.dropbox.com/developers/apps/create

![Apps creation on Dropbox.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/114/conversions/Screenshot_2022-12-03_at_12.40.15_rynwtk-medium.jpg)

## License

Take this package and do whatever the f you want with it. That's basically what the [WTFPL](http://www.wtfpl.net/about/) license says.
