---
id: "01KKEW276GTB5FS3SPNP5266QE"
title: "Add Alpine.js to any Laravel project"
slug: "alpine-js-laravel"
author: "benjamincrozat"
description: "Alpine.js is a great companion for a Laravel app. Let's see how you can add it in any project."
categories:
  - "alpinejs"
  - "javascript"
  - "laravel"
published_at: 2023-10-13T00:00:00+02:00
modified_at: 2025-07-04T21:38:00+02:00
serp_title: "Add Alpine.js to any Laravel project (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/c97J916y5MdRkpQ.jpg"
sponsored_at: null
---
## Introduction

[Alpine.js](https://alpinejs.dev) is a fantastic way to start adding reactivity to your user interface. [I wrote about this minimalist framework](/alpine-js) if you're not familiar with it yet.

Today, we'll learn how to add Alpine.js into an existing Laravel project. Of course, this will work on new projects too. Let's dive in!

## Use Alpine.js via a CDN

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

## Install Alpine.js in Laravel

If you'd like to control the number of HTTP requests on your page and don't mind using build tools, you might prefer to bundle the framework into your JavaScript.

```bash
npm install alpinejs
```

## Set up Alpine.js

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

## Add minimal Alpine.js code

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

## Compile your assets and check your browser

If you did everything correctly, Alpine.js should now be up and running. Compile your assets and check your browser!

```bash
npm run dev
```

Done! Now, go build something amazing with Alpine.js and Laravel!

## Conclusion

Adding Alpine.js to your Laravel project is a straightforward process, whether you choose to use a CDN or bundle it with your assets. This lightweight framework can significantly enhance the interactivity of your Laravel applications without the complexity of larger JavaScript frameworks.

Remember to explore Alpine.js's documentation for more advanced features and best practices as you integrate it into your Laravel projects. Happy coding!

If you are shaping the frontend layer of a Laravel app right now, these next reads cover the companion tools and alternatives worth looking at:

- [Alpine.js: a lightweight framework for productive developers](/alpine-js)
- [Add Vue.js to any Laravel project](/laravel-vue)
- [Add Tailwind CSS to any Laravel project](/tailwind-css-laravel)
- [Use Bun as Your Package Manager in Any Laravel Project](/bun-laravel)
- [The laravel/ui package: my 2025 guide](/laravel-ui)
