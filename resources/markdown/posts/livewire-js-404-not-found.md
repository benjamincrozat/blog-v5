---
id: "01KKEW27H4W10DJG83GXF8EMJ5"
title: "Fix Livewire JavaScript 404 errors in v3 and v4"
slug: "livewire-js-404-not-found"
author: "benjamincrozat"
description: "Fix Livewire JavaScript 404 errors by checking the v3 or v4 asset route, Nginx rules, route cache, Blade directives, and published assets."
categories:
  - "livewire"
published_at: 2023-09-20T22:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/CZyOIx4Jh55u1dx.jpg"
sponsored_at: null
---
If Livewire's JavaScript returns a 404, first check which Livewire major your app uses. The URL changed in Livewire 4:

| Livewire version | Default JavaScript route |
| --- | --- |
| Livewire 4 | `/livewire-{hash}/livewire.js` |
| Livewire 3 | `/livewire/livewire.js` |

Run these commands before changing Nginx or Apache:

```bash
composer show livewire/livewire
php artisan route:list --path=livewire
```

I checked the current behavior with Livewire 4.4.0. Its asset and update routes include a hash derived from the app, so your exact URL will differ from another project's URL.

## Fix the Livewire 4 JavaScript 404 on Nginx

Livewire 4 serves JavaScript through Laravel at a route such as `/livewire-a1b2c3d4/livewire.js`. A broad Nginx rule for `.js` files can intercept that request and look for a file that does not exist in `public/`.

Pass Livewire's hashed routes back to Laravel before your static-asset rule:

```nginx
location ~ ^/livewire-[a-f0-9]+/ {
    try_files $uri $uri/ /index.php?$query_string;
}
```

This is the pattern in the current [Livewire 4 installation and troubleshooting guide](https://livewire.laravel.com/docs/4.x/installation#livewire-javascript-not-loading-404-error).

Reload Nginx after validating the configuration:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

The service name can differ on managed servers. Use your host's normal Nginx reload command if `nginx.service` is not how it is installed.

## Fix `/livewire/livewire.js` on Livewire 3

Livewire 3 uses the older `/livewire/livewire.js` route. If Nginx treats every `.js` request as a static file, add an exact exception before that rule:

```nginx
location = /livewire/livewire.js {
    try_files $uri $uri/ /index.php?$query_string;
}
```

Do not copy the v3 path into a Livewire 4 app. Confirm the route your installed package actually registered.

## Clear a stale route cache

If the web server reaches Laravel but the route is still missing, clear only the route cache:

```bash
php artisan route:clear
php artisan route:list --path=livewire
```

Use `route:clear` here instead of clearing every Laravel cache. It tests the likely cause without flushing unrelated application data.

## Check the Blade asset directives

Livewire injects its assets automatically when a page contains a Livewire component. I still prefer explicit directives in the layout because they make the asset placement obvious:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body>
        {{ $slot }}

        @livewireScripts
    </body>
</html>
```

If `inject_assets` is `false` in `config/livewire.php`, these directives are required.

## Manually bundle Livewire only when you need control

Most apps should let Livewire serve its own assets. Manual bundling is useful when you need Alpine plugins or a custom initialization order.

Replace `@livewireScripts` with `@livewireScriptConfig`, then import the ESM build in `resources/js/app.js`:

```js
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm'

// Register Alpine plugins here.

Livewire.start()
```

Rebuild your frontend after every Livewire Composer update when you use this setup:

```bash
npm run build
```

## Publishing Livewire assets is a last resort

If your architecture requires Nginx or a CDN to serve a real file instead of a Laravel route, publish the assets:

```bash
php artisan livewire:publish --assets
```

Published files can become stale after `composer update`. Add the official post-update hook if you choose this route:

```json
{
    "scripts": {
        "post-update-cmd": [
            "@php artisan vendor:publish --tag=livewire:assets --ansi --force"
        ]
    }
}
```

Livewire's docs call published assets unnecessary for most applications. Fixing the route is the simpler default.

## A quick diagnosis order

1. Run `composer show livewire/livewire`.
2. Run `php artisan route:list --path=livewire`.
3. Open the exact JavaScript URL from the rendered `<script>` tag.
4. If Laravel has the route, fix the Nginx or Apache rule intercepting it.
5. If the route is absent, run `php artisan route:clear` and check package discovery.
6. If automatic injection is disabled, add `@livewireStyles` and `@livewireScripts`.
7. Publish or bundle assets only when your deployment really needs that setup.

If you are still smoothing out a Livewire installation, these are the next useful reads:

- [See how far `wire:navigate` can take a Livewire app](/livewire-spa-wire-navigate)
- [Force a Livewire refresh when state gets stuck](/re-render-livewire-component)
- [Stop a Livewire component from re-rendering when it should not](/prevent-render-livewire)
- [See when Laravel Volt is the simpler Livewire option](/laravel-volt)
