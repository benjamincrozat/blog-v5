---
id: "01KKEW27ABXMCE4MS11QSP40C6"
title: "How to publish API and broadcasting routes in Laravel 11"
slug: "install-route-files-laravel"
author: "benjamincrozat"
description: "The new minimalist application skeleton in Laravel 11 comes with less route files. Here's how to install them."
categories:
  - "laravel"
published_at: 2024-02-23T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/4r9PfeUOe96pgfI.png"
sponsored_at: null
---
Starting from Laravel 11, new projects get to experience a slimmer skeleton. Parts of the efforts to make it happen was to remove some of the route files which can be overwhelming for new developers.

That being said, as your application grows, you might need to create a RESTful API or broadcast events into channels for an app leveraging WebSockets.

To publish the API routes file in Laravel 11 and up, use:
  
```bash
php artisan install:api
```

This command will create the *routes/api.php* file, but also install [Laravel Sanctum](https://laravel.com/docs/sanctum), create some migrations, and add a *config/sanctum.php* file.

And to publish the broadcasting channels routes file, use:

```bash
php artisan install:broadcasting
```

After running the command, Artisan will also ask you if you want to install [Laravel Reverb](https://reverb.laravel.com).

If you want a few more Laravel rabbit holes after this:

- [How to publish config files in Laravel 11](/publish-config-files-laravel)
- [Laravel 11 is out! Here are every new big changes and features.](/laravel-11)
- [Secure your REST API in 5 minutes with Laravel Sanctum](/laravel-sanctum-api-tokens-authentication)





