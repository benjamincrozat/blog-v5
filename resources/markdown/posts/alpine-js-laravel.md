---
id: "01KKEW276GTB5FS3SPNP5266QE"
title: "How to install Alpine.js in Laravel"
slug: "alpine-js-laravel"
author: "benjamincrozat"
description: "Install Alpine.js in Laravel with Vite or a CDN, then verify it works with a tiny component."
categories:
  - "alpinejs"
  - "javascript"
  - "laravel"
published_at: 2023-10-13T00:00:00+02:00
modified_at: 2026-03-13T11:30:00Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/c97J916y5MdRkpQ.jpg"
sponsored_at: null
---
## Introduction

Need to install [Alpine.js](https://alpinejs.dev) in a Laravel project? The two fastest options are a CDN for simple pages or `npm install alpinejs` if you already use Vite. I also wrote a broader [Alpine.js introduction](/alpine-js) if you want the bigger picture first.

This guide shows both setup paths, the exact `app.js` snippet, and a quick way to verify everything is working.

## Install Alpine.js in Laravel with a CDN

Alpine.js is such a simple framework that it can be dropped into any web page using the CDN of your choice.

```html
<!DOCTYPE html>
<html>
    <head>
        <!-- Other head elements. -->

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body>
        <!-- Your content. -->
    </body>
</html>
```

That's it. And you can even add plugins that way:

```html
<!DOCTYPE html>
<html>
    <head>
        <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.x.x/dist/cdn.min.js"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    </head>
    <body>
        <!-- Your content. -->
    </body>
</html>
```

You could already stop reading this article. If you are missing the good old days when everybody used jQuery and build tools were only for hipsters, you should be happy!

**Pro tip: The URLs in this example redirect to the latest version of the framework and plugin. For production use, it's recommended to specify a fixed version number instead of using the `@3.x.x` syntax to ensure consistency.**

## Install Alpine.js in Laravel with npm and Vite

If you'd like to control the number of HTTP requests on your page and don't mind using build tools, you might prefer to bundle the framework into your JavaScript.

```bash
npm install alpinejs
```

## Set up Alpine.js in `resources/js/app.js`

Now, we must import Alpine and create an instance of the framework.

In *resources/js/app.js*, make the following modifications:

```js
import Alpine from 'alpinejs'

// If you want Alpine's instance to be available globally.
window.Alpine = Alpine

Alpine.start()
```

That's it. Simple, right?

To use plugins, first install one. For example, let's add the Intersect plugin:

```bash
npm install @alpinejs/intersect
```

Then, tell Alpine to use the plugin:

```js
import Alpine from 'alpinejs'
import Intersect from '@alpinejs/intersect'

window.Alpine = Alpine

Alpine.plugin(Intersect)
Alpine.start()
```

## Add a minimal Alpine.js component

We're almost there!

Include your JavaScript using the `@vite` directive and add this basic component to test Alpine.js:

```blade
<!DOCTYPE html>
<html>
    <head>
        <!-- Other head elements. -->
        
        @vite(['resources/js/app.js'])
    </head>
    <body>
        <div x-data="{ count: 0 }">
            <button @click="count++">Add</button>
            <span x-text="count"></span>
        </div>
    </body>
</html>
```

Yes, this is an old-fashioned counter to demonstrate that the framework is working.

I know, very original, right? 😅

## Compile your assets and verify Alpine.js works

If you did everything correctly, Alpine.js should now be up and running. Compile your assets and check your browser!

```bash
npm run dev
```

Done! Now, go build something amazing with Alpine.js and Laravel!

## Conclusion

Adding Alpine.js to your Laravel project is a straightforward process, whether you choose to use a CDN or bundle it with your assets. This lightweight framework can significantly enhance the interactivity of your Laravel applications without the complexity of larger JavaScript frameworks.

Remember to explore Alpine.js's documentation for more advanced features and best practices as you integrate it into your Laravel projects. Happy coding!

If you are shaping the frontend layer of a Laravel app right now, these next reads cover the companion tools and alternatives worth looking at:

- [See when Alpine.js is enough for the interactivity you need](/alpine-js)
- [Add Vue to Laravel without overbuilding the frontend](/laravel-vue)
- [Add Tailwind to Laravel without setup guesswork](/tailwind-css-laravel)
- [Swap npm out for Bun in Laravel without friction](/bun-laravel)
- [See when laravel/ui is still the right starter choice](/laravel-ui)
