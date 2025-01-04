---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/196/z0tPraLQOcwIbGEhB7AcGYZZdvlHaJ-metabGl2ZXdpcmUtanMtNDA0LW5vdC1mb3VuZC5qcGc%3D-.jpg
Title: "Fix the /livewire/livewire.js 404 not found error"
Description: "Learn how to fix the 404 not found error occurring for /livewire/livewire.js."
Published at: 2023-09-21
Modified at: 2023-10-17
Categories: livewire
---

**To fix the 404 not found error your browser receives for */livewire/livewire.js* (most likely on your production server), there are high chances that you will have to look at your web server's configuration for the concerned site.**

## Why /livewire/livewire.js returns a 404 not found error

Livewire serves its JavaScript itself. If you run `php artisan route:list` you will see a route matching */livewire/livewire.js*.

```
GET|HEAD  livewire/livewire.js ................................. Livewire\Mechanisms › FrontendAssets@returnJavaScriptAsFile
```

For all I know, your web server won't mind about this. But problems can occur if you ever decide to, for instance, set custom headers for JavaScript files.

Here's my Nginx configuration:

```
location ~* \.(css|gif|ico|jpeg|jpg|js|png|svg|webp|woff2)$ {
    expires 7d;
    add_header Cache-Control "public, no-transform";

    try_files $uri =404;
}
```

The problem occurs because the configuration you provide is set up in such a way that when a request was made for */livewire/livewire.js*, Nginx attempts to serve it as a static file and was checking if it existed on the filesystem.

But it doesn't! This file is served by Livewire. Nginx can't find it, so it responds with a 404 error.

Luckily, the fix is easy.

## How to fix /livewire/livewire.js 404 not found error

### Using Nginx

Right before the location block that sets your custom headers, add this one, which will prevent Nginx from interfering with this specific location. 

```
location = /livewire/livewire.js {
    expires off;
    try_files $uri $uri/ /index.php?$query_string;
}
```

### By bundling Livewire into your JavaScript

In Livewire v3, the code is [automatically injected](https://livewire.laravel.com/docs/installation#manually-including-livewires-frontend-assets) unless you instructed otherwise.

Fortunatelly, it's possible to not leverage the route that Livewire exposes for its JavaScript, and [do things in a more traditional way](https://livewire.laravel.com/docs/installation#manually-bundling-livewire-and-alpine).

Go into your *resources/js/app.js* file, and add this code:

```js
import { Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm'

Livewire.start()
```

Livewire will be initialized and it will also start [Alpine.js](/alpine-js), which is included by default in Livewire v3.
