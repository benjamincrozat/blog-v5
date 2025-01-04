---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/f89fc0a0-564d-498d-aafa-a27292378376
Title: "How to publish config files in Laravel 11"
Description: "The new minimalist application skeleton in Laravel 11 comes with no configuration files. Here's how to publish them."
Published at: 2024-02-23
Modified at: 2024-03-21
Categories: laravel
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
