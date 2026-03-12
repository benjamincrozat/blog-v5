---
id: "01KKEW27H4W10DJG83GXF8EMJ5"
title: "Fix the /livewire/livewire.js 404 not found error"
slug: "livewire-js-404-not-found"
author: "benjamincrozat"
description: "Learn how to fix the 404 not found error occurring for /livewire/livewire.js."
categories:
  - "livewire"
published_at: 2023-09-21T00:00:00+02:00
modified_at: 2023-10-17T00:00:00+02:00
serp_title: "Fix the /livewire/livewire.js 404 not found error (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/CZyOIx4Jh55u1dx.jpg"
sponsored_at: null
---
## Introduction

**If you're hitting a frustrating 404 error when requesting `/livewire/livewire.js`, especially on your production server, the good news is the fix is typically straightforward. Let's dig in!**

## Why `/livewire/livewire.js` returns a 404 error

Livewire serves its JavaScript dynamically. Run `php artisan route:list` and you'll spot this route:

```
GET|HEAD  livewire/livewire.js .......... Livewire\Mechanisms › FrontendAssets@returnJavaScriptAsFile
```

However, certain server configurations—like mine below—can mistakenly try to handle Livewire's JavaScript file as a static asset:

Here's my Nginx setup:

```
location ~* \.(?:css|js|mjs|map|jpg|jpeg|gif|png|svg|webp|ico|ttf|woff2?)$ {
    expires 30d;
    access_log off;
    add_header Cache-Control "public, immutable";
}
```

Since `/livewire/livewire.js` isn't an actual static file, Nginx ends up giving you a disappointing 404.

Luckily, we can easily resolve this.

## How to fix `/livewire/livewire.js` 404 errors

### Fix for Nginx

Just before the code we saw above, drop in this little snippet:

```
location = /livewire/livewire.js {
    expires off;
    try_files $uri $uri/ /index.php?$query_string;
}
```

Now, Nginx gracefully hands this request back to Laravel.

You could also have a regex that matches all JavaScript files but livewire.min.js:

location ~* ^(?!/livewire/livewire\.min\.js$).*\.(?:css|js|mjs|map|jpg|jpeg|gif|png|svg|webp|ico|ttf|woff2?)$ {
    expires 30d;
    access_log off;
    add_header Cache-Control "public, immutable";
}

### Apache fix (.htaccess)

If you're an Apache user, update your `.htaccess` with:

```apacheconf
RewriteCond %{REQUEST_URI} ^/livewire/livewire\.js$
RewriteRule ^ index.php [L,NC]
```

It tells Apache, "Let Laravel handle this one, buddy."

## Using Livewire in a sub-directory or with a CDN?

If your Laravel app sits in a sub-directory, adjust `config/livewire.php` to correctly route assets:

```php
// config/livewire.php
'asset_url' => env('APP_URL') . '/subdirectory',
```

If you're serving assets via a CDN like Cloudflare, make sure you exclude `/livewire/*` from caching, or you'll have other headaches!

## A better way: bundle Livewire into your JavaScript

By default, Livewire v3 injects its JavaScript automatically. However, for more control, you can bundle it traditionally.

If you're using **Vite** (which I recommend), adjust `resources/js/app.js`:

```js
import { createLaravelVitePlugin } from 'laravel-vite-plugin'
import 'laravel-vite-plugin/plugins/livewire'
import { Livewire } from 'livewire'

Livewire.start()
```

Then disable automatic injection in your config:

```php
// config/livewire.php
'inject_assets' => false,
```

Using **Laravel Mix**? Here's your quick fix:

```js
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm'

Livewire.start()
```

(Note: Be cautious, as paths might shift slightly between updates.)

## Quick troubleshooting checklist

Still seeing 404s? Run these quick checks:

* Confirm the route exists: `php artisan route:list | grep livewire.js`
* Clear caches and restart the server: `php artisan optimize:clear` & server reload.
* Directly visit `/livewire/livewire.js`. If you see anything other than a 200 status, revisit your web server configuration.

## Conclusion

The `/livewire/livewire.js` issue typically boils down to server config confusion. But armed with these tweaks, you're set for smooth sailing.

If you are still smoothing out the rough edges of a Livewire install after fixing the asset path, these are the next reads I would open:

- [Create a SPA in seconds using wire:navigate in Livewire v3](/livewire-spa-wire-navigate)
- [How to force re-render a Livewire v3 component](/re-render-livewire-component)
- [Prevent a Livewire component from re-rendering](/prevent-render-livewire)
- [Laravel Volt: simplify how you write Livewire components](/laravel-volt)
