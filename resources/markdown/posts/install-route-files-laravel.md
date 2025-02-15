---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/3f28ce77-bd37-4215-bf91-f850c5c563de
Title: "How to publish API and broadcasting routes in Laravel 11"
Description: "The new minimalist application skeleton in Laravel 11 comes with less route files. Here's how to install them."
Published at: 2024-02-23
Categories: laravel
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
