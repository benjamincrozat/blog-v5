---
id: "01KKEW27C26MTETWGEFRNJEVGK"
title: "Laravel Dropbox Driver package: how to install and use it"
slug: "laravel-dropbox-driver"
author: "benjamincrozat"
description: "Store and manage files on Dropbox and use it to back up your Laravel app automatically."
categories:
  - "laravel"
  - "packages"
published_at: 2022-12-03T00:00:00+01:00
modified_at: 2022-12-20T00:00:00+01:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/Gn6ORVDJ1GvrYie.png"
sponsored_at: null
---
<img src="https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-dropbox-driver-49dbc9e1c9b2ea179c34.svg/public" class="inline" style="margin: 0" /> <img src="https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-dropbox-driver-c328658047ce292887ad.svg/public" class="inline" style="margin: 0" /> <img src="https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-dropbox-driver-b04b20e6e45f1cbdf921.svg/public" class="inline" style="margin: 0" /> <img src="https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-dropbox-driver-907a797d7e7d33b46d41.svg/public" class="inline" style="margin: 0" />

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

![Apps creation on Dropbox.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-dropbox-driver-45c417c0d4737e871343.jpg/public)

## License

Take this package and do whatever the f you want with it. That's basically what the [WTFPL](http://www.wtfpl.net/about/) license says.

If you are still thinking about "Laravel Dropbox Driver package: how to install and use it", open these next:

- [See what Laravel Pulse can surface before users do](/laravel-pulse)
- [See when laravel/ui is still the right starter choice](/laravel-ui)
- [Protect your API with Laravel Sanctum before it gets exposed](/laravel-sanctum-api-tokens-authentication)
- [Build better Artisan prompts without extra ceremony](/laravel-prompts)
- [See when Laravel Volt is the simpler Livewire option](/laravel-volt)
- [Make Eloquent models easier for your IDE to understand](/laravel-lift)
- [Steal ideas for Laravel Pulse cards worth building](/best-laravel-pulse-custom-cards)
- [See the Laravel 10 changes that matter in real projects](/laravel-10)
- [See what to clean up before moving to Laravel 9](/laravel-9-upgrade-guide)
- [Build a custom Laravel filesystem driver when the defaults fall short](/custom-filesystem-adapter-laravel)

