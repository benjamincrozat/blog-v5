---
id: "01KKNVJCS4ZFE40DY4VVAYCW8Q"
title: "How to install Alpine.js in any project"
slug: "how-to-install-alpine-js"
author: "benjamincrozat"
description: "Install Alpine.js in any project with a CDN or a package manager, then verify it works with a tiny component."
categories:
  - "alpinejs"
  - "javascript"
published_at: 2026-03-14T09:39:45Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
## Introduction

Need to install [Alpine.js](https://alpinejs.dev/essentials/installation) in a plain HTML page, a static site, or any app that already ships JavaScript?

You have two good options:
- use a CDN when you want the fastest possible setup
- use a package manager when your project already has a bundler

This guide shows both paths, explains when to choose each one, and gives you a tiny component to confirm Alpine is working.

## Choose the right install method first

Before touching any code, make this decision:

| Option | Best for | What you do |
| --- | --- | --- |
| CDN | Small sites, prototypes, server-rendered pages, quick experiments | Paste one script tag into your HTML |
| Package manager | Apps that already use Vite, Webpack, Parcel, or another bundler | Install `alpinejs`, import it, and call `Alpine.start()` |

If you are not already bundling JavaScript, the CDN route is usually the simplest one.

## Install Alpine.js with a CDN

The [official Alpine installation guide](https://alpinejs.dev/essentials/installation) shows the simplest option first: add Alpine with a `defer` script tag in the `<head>` of your page.

I recommend pinning a real version in production instead of using `@3.x.x`. As of March 14, 2026, the latest npm release is `3.15.8`, so your HTML can look like this:

```html
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Alpine.js test</title>

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.15.8/dist/cdn.min.js"></script>
    </head>
    <body>
        <div x-data="{ open: false }">
            <button @click="open = ! open">Toggle</button>
            <p x-show="open">Alpine is running.</p>
        </div>
    </body>
</html>
```

Three details matter here:
- `defer` makes sure Alpine loads after the HTML has been parsed.
- The version is pinned, so production does not change unexpectedly.
- Alpine directives only work once you add a component root such as `x-data`.

If you just want Alpine on one page, this is usually enough.

## Install Alpine.js with a package manager

If your project already bundles JavaScript, install the `alpinejs` package instead of loading it from a CDN.

```bash
npm install alpinejs
```

If you use `pnpm`, Yarn, or Bun, install the same package name with your usual package manager command.

Then import Alpine in your main JavaScript entry file and start it:

```js
import Alpine from 'alpinejs'

window.Alpine = Alpine

Alpine.start()
```

This works well in projects using Vite, Webpack, Parcel, and similar build tools. The important part is that your main JavaScript file is loaded on the page only once.

The Alpine docs also note two easy mistakes to avoid:
- register any extensions or plugins before `Alpine.start()`
- do not call `Alpine.start()` more than once on the same page

If you need the Laravel-specific version of this setup, I wrote a separate guide on [installing Alpine.js in Laravel](/alpine-js-laravel).

## Test that Alpine.js works

Once Alpine is loaded, drop this tiny counter into your page:

```html
<div x-data="{ count: 0 }">
    <button @click="count++">Add</button>
    <span x-text="count"></span>
</div>
```

Click the button a few times. If the number increases, your installation is working.

This is close to the minimal example used in Alpine's [Start Here guide](https://alpinejs.dev/start-here), and it is enough to confirm that directives like `x-data`, `@click`, and `x-text` are wired correctly.

## Common Alpine.js installation mistakes

If Alpine still does nothing, one of these is usually the reason:

- You forgot the `defer` attribute on the CDN script.
- You imported Alpine but never called `Alpine.start()`.
- Your bundled JavaScript file is not actually included on the page.
- You called `Alpine.start()` twice.

When I troubleshoot Alpine setups, I check those four things first.

## Conclusion

If you want the quickest possible setup, use the CDN version. If your project already has a JavaScript build step, install the package and start Alpine from your entry file.

That is really the whole decision. Pick the path that matches your project, test it with the small counter above, and then move on to the component you actually want to build.

If you want the next step after setup instead of more installation advice, these are the pages I would open next:

- [See where Alpine is genuinely useful before adding more directives](/alpine-js)
- [Use the Laravel-specific setup when your app already runs Blade and Vite](/alpine-js-laravel)
- [Compare Alpine's HTML-first approach with old-school jQuery habits](/jquery)
