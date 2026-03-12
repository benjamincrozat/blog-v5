---
id: "01KKEW27MQYFTXBKYCER08Q7VF"
title: "How to publish config files in Laravel 11"
slug: "publish-config-files-laravel"
author: "benjamincrozat"
description: "The new minimalist application skeleton in Laravel 11 comes with no configuration files. Here's how to publish them."
categories:
  - "laravel"
published_at: 2024-02-23T00:00:00+01:00
modified_at: 2024-03-21T00:00:00+01:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/q7ICgodAWF9QAwi.png"
sponsored_at: null
---
To publish additional configuration files in Laravel 11, use:

```bash
php artisan config:publish
```

Laravel will then ask you to choose which configuration file you want to publish.

And by the way, you can also publish them all at once using:

```bash
php artisan config:publish --all
```

Why is this necessary now? Because starting from [Laravel 11](https://laravel.com/docs/11.x/releases), new projects get to experience a slimmer skeleton. Part of the efforts to make it happen was:
- Cleaning up the configuration files (some values were actually [removed](https://github.com/laravel/laravel/commit/f437205a5e11e6fd5ea64e4adc30ab155131c79f)).
- Updating the _.env.example_ file with more environment variables to make the framework more configurable in one place.
- Reducing the amount of published files, which can be overwhelming for new developers. For instance, the _cors.php_, _hashing.php_, and _view.php_ files are missing.

If you are still adapting to the slimmer Laravel 11 skeleton after this, these are the next reads I would keep nearby:

- [How to publish API and broadcasting routes in Laravel 11](/install-route-files-laravel)
- [How to customize middleware in Laravel 11+](/customize-middleware-laravel-11)
- [Laravel 11: an easy and step by step upgrade guide](/laravel-11-upgrade-guide)
