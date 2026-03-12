---
id: "01KKEW276EXG2JH016XWX60YSZ"
title: "Alpine.js: a lightweight framework for productive developers"
slug: "alpine-js"
author: "benjamincrozat"
description: "Learn what Alpine.js is, how I set it up by CDN or Vite, when to use it vs jQuery, and which official plugins and devtools speed up my workflow."
categories:
  - "alpinejs"
  - "javascript"
published_at: 2023-01-26T00:00:00+01:00
modified_at: 2025-09-28T10:48:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K67Z210C84GQ7ZM93CPDMW5X.png"
sponsored_at: null
---
## Introduction

Front-end web development has increased in complexity over the past decade. Despite being well aware of this fact, we are still reaching for JavaScript frameworks because building good user interfaces is even more challenging than writing Vanilla JavaScript.

Luckily, Alpine.js is a good compromise. This Alpine.js tutorial shows how I use x-data, x-show, x-transition, and click.outside to build small interactive pieces.

### Who this is for

You want simple interactivity without a heavy build step. You’re comfortable with HTML and a bit of JavaScript, and you’re curious when Alpine.js vs jQuery makes sense.

## What is Alpine.js and how does it benefit you

**[Alpine.js](https://alpinejs.dev) is a tiny JavaScript framework for building interactive user interfaces.**

Imagine Vue.js, but smaller, easier and pragmatic. Alpine relies on a small set of directives and magic helpers you sprinkle into your HTML.

This is all it takes to build an Alpine.js dropdown:

```html
<div x-data="{ open: false }" @click.outside="open = false">
    <button
        @click="open = ! open"
        class="dropdown-trigger"
        :aria-expanded="open"
        aria-controls="dropdown-menu"
    >
        Toggle
    </button>
    
    <div id="dropdown-menu" x-cloak x-show="open" x-transition>
        …
    </div>
</div>
```

Tip: add a tiny CSS rule to prevent a flash of unstyled content when using x-cloak.

```css
[x-cloak] { display: none !important; }
```

No component file and no build process are required. You can see what’s going on by looking at the HTML.

## Alpine.js vs jQuery: gently modernize how you write JavaScript

In my experience, teams keep jQuery for habit, AJAX helpers, DOM queries, and quick effects. If you want a number, as of August 31, 2025, jQuery appears on about 72.8% of websites according to [W3Techs](https://w3techs.com/technologies/comparison/js-jquery).

Alpine.js keeps things easy like jQuery but feels modern. Here’s how I switch common patterns:
- First, jQuery’s `$()` isn’t needed. Use `document.querySelector()` and `document.querySelectorAll()`.
- Second, make AJAX requests with the [Fetch API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch): `fetch('https://example.com/api/bar')`.

If you need conditional styles and transitions, here’s an Alpine.js dropdown with accessibility in mind:

```html
<div x-data="{ open: false }" @click.outside="open = false">
    <button
        class="dropdown-trigger"
        :class="{ 'dropdown-trigger--active': open }"
        @click="open = ! open"
        :aria-expanded="open"
        aria-controls="menu"
    >
        Toggle
    </button>
  
    <ul x-cloak x-show="open" x-transition>
        …
    </ul>
</div>
```

I’ve often seen messy codebases built around jQuery with behavior scattered everywhere. Alpine.js helps by keeping behavior close to the markup.

## Setting up Alpine.js in any project only takes a copy-and-paste

Alpine.js is one of the easiest frameworks to set up. Just copy-and-paste this code snippet into the `<head>` of your page:

```html
<!DOCTYPE html>
<html>
    <head>
        …
        <style>[x-cloak] { display: none !important; }</style>
        <script defer src="https://unpkg.com/alpinejs@3.14.9/dist/cdn.min.js"></script>
    </head>
    <body>
        …
    </body>
</html>
```

Using a version range like `@3.x.x` causes a redirect to the latest matching version. I prefer to pin a specific version in production to avoid redirects and improve cache hit rates; see [Unpkg](https://unpkg.com) for details.

Same thing for plugins. **Make sure they come before Alpine.js** when using a CDN:

```html
<script defer src="https://unpkg.com/@alpinejs/intersect@3.14.9/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/persist@3.14.9/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/@alpinejs/focus@3.14.9/dist/cdn.min.js"></script>
<script defer src="https://unpkg.com/alpinejs@3.14.9/dist/cdn.min.js"></script>
```

### CDN vs bundling

- CDN: great for quick demos or small pages. I typically add one script tag and go.
- Bundling: better for apps. Use a build tool so you can tree-shake and register plugins before `Alpine.start()`.

## Set up Alpine.js in a Laravel project using Vite

First, install Alpine.js in your project.

```
npm install alpinejs
```

Then, in your main JavaScript file (in a Laravel project, it’s *resources/js/app.js*), import Alpine and boot it up.

```js
import Alpine from 'alpinejs'

Alpine.start()
```

You can assign your Alpine object to a global variable. It’s useful to play with it in the devtools.

```js
import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()
```

If you’re using plugins, register them before calling `Alpine.start()`.

```js
import Alpine from 'alpinejs'
import Intersect from '@alpinejs/intersect'

Alpine.plugin(Intersect)

window.Alpine = Alpine

Alpine.start()
```

Make sure Blade includes your Vite entry. I usually add `@vite('resources/js/app.js')` in the layout’s `<head>`, and I confirm that `app.js` is listed as a Vite entry in `vite.config.js`. See the [Laravel Vite documentation](https://laravel.com/docs/12.x/vite) for examples.

## Don’t reinvent the wheel, use the official plugins

The creator of Alpine.js provides plugins that handle common tasks.

- [Mask](https://alpinejs.dev/plugins/mask) formats user input on the fly.
- [Intersect](https://alpinejs.dev/plugins/intersect) runs code once the user scrolls to a given point.
- [Persist](https://alpinejs.dev/plugins/persist) adds the `$persist` helper to save state.
- [Focus](https://alpinejs.dev/plugins/focus) handles focus; `x-trap` is great for modals.
- [Collapse](https://alpinejs.dev/plugins/collapse) animates height transitions.
- [Morph](https://alpinejs.dev/plugins/morph) morphs HTML while preserving state.

These Alpine plugins extend the framework for real tasks. For example, Mask helps format phone numbers, Intersect helps with lazy loading, and Focus improves accessibility.

## Alpine.js also has dev tools

You can inspect Alpine components right in your browser. Install the official [Alpine.js DevTools for Chrome](https://chromewebstore.google.com/detail/alpinejs-devtools/fopaemeedckajflibkpifppcankfmbhk) or the [Firefox extension](https://addons.mozilla.org/en-US/firefox/addon/alpinejs-devtools/). If you want more features, there’s also a paid [Alpine.js DevTools Pro](https://alpinejs.pro/) option.

## Copy and paste components from libraries

As you know now, Alpine.js is a minimal JavaScript framework. One of its key features is its simplicity.

This is achieved through a declarative syntax and a small set of directives that can be used to create dynamic behaviors right from your HTML.

This philosophy makes it easy for developers to create reusable components that can be easily integrated into a variety of projects without the need for complex configuration.

Here are a bunch of websites sharing Alpine.js components, mostly for free:

- [Alpine UI Components](https://alpinejs.dev/components)
- [Alpine Toolbox](https://www.alpinetoolbox.com)
- [HyperJS](https://js.hyperui.dev) (an Alpine-focused example repository)

These resources provide a wide range of pre-built components, from simple toggles to complex data tables. Using these components can significantly speed up your development process and ensure consistency across your projects.

## Learn, contribute and follow

- [Alpine.js documentation](https://alpinejs.dev/start-here) provides a comprehensive overview of its features and directives. It’s well-written and includes plenty of examples to help you get started quickly.
- If you like Alpine.js and would like to contribute to the project, its [GitHub repository](https://github.com/alpinejs/alpine) is the best place to start. You can report issues, suggest features, or even submit pull requests to improve the framework.
- [Alpine.js on Twitter](https://twitter.com/Alpine_JS) is where you want to be for the latest news about the framework. Follow to stay updated on new releases, tips, and community highlights.

By engaging with the Alpine.js community through these channels, you’ll not only improve your skills but also contribute to the growth of this lightweight yet powerful framework.

## Conclusion

I reach for Alpine.js when I need small, interactive UI pieces without heavy tooling. For quick pages I load it from a CDN and pin versions; for apps I bundle it with Vite and register plugins before `Alpine.start()`. Give the dropdown example a try, then explore the official plugins and install the Alpine.js devtools to speed up your workflow.

If you want a few more frontend rabbit holes after this:

- [Add Alpine.js to any Laravel project](/alpine-js-laravel)
- [Add Vue.js to any Laravel project](/laravel-vue)
- [Get started with jQuery in 5 minutes](/jquery)

