---
id: "01KKEW27E3DQ3AVNGYRB559YXJ"
title: "How to install Laravel UI"
slug: "laravel-ui"
author: "benjamincrozat"
description: "Install the `laravel/ui` package to scaffold Bootstrap, Vue, or React auth views in a Laravel app."
categories:
  - "laravel"
  - "packages"
published_at: 2023-11-12T00:00:00+01:00
modified_at: 2026-03-13T12:25:00Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/L7smjKxLResXG8U.jpg"
sponsored_at: null
---
## Introduction to the laravel/ui package

**To install Laravel UI, run `composer require laravel/ui`, then scaffold the frontend preset you want with `php artisan ui`.**

[laravel/ui](https://github.com/laravel/ui) gives you a basic starting point for Bootstrap, Vue, or React in a Laravel app, and it can also scaffold simple authentication views.

It is still maintained, but it is also older than [Laravel Jetstream](https://jetstream.laravel.com) and [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze), so it makes the most sense when you want the simpler package on purpose.

Here is how to install Laravel UI and when it still makes sense to use it.

## Installing laravel/ui

Getting started with laravel/ui is straightforward. Install it via the following command:

```bash
composer require laravel/ui
```

Next, you can install the frontend scaffolding of your choice. **Remember, the next command won't add any component to your app. It will just make your app ready for whatever front-end framework you want to use.** The laravel/ui package supports [Bootstrap](https://getbootstrap.com) without JavaScript or Bootstrap combined with [Vue.js](https://vuejs.org) or [React](https://react.dev):

```bash
php artisan ui bootstrap
php artisan ui vue
php artisan ui react
```

Finally, install and compile your front-end dependencies:

```bash
npm install
npm run dev
```

## Installing laravel/ui with authentication features

Installing the authentication part of laravel/ui is completely optional. If you want to use it, you can install it using the `--auth` flag:

```bash
php artisan ui bootstrap --auth
php artisan ui vue --auth
php artisan ui react --auth
```

Now, you have a basic user interface for user registration and authentication and you can customize it to your liking.

![Laravel UI in action.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-ui-8f4bd4d6406f1689fb50.jpg/public)

Before refreshing your browser, make sure to install and compile your front-end dependencies:

```bash
npm install
npm run dev
```

You now have everything you need to move forward on your project.

## Customizing the CSS and JavaScript laravel/ui provides

After installing laravel/ui, you can dive into customizing the CSS and JavaScript.

Laravel uses [Vite](https://vitejs.dev) out-of-the-box for handling these aspects.

If you are still using a CSS preprocessor like Sass or Less, Vite streamlines the process and you can [learn more about it in the official documentation of Laravel](https://laravel.com/docs/vite).

For JavaScript, Laravel allows flexibility. You can use Vue.js, React, or anything else and even go without JavaScript.

The setup with laravel/ui just makes it easier to integrate these technologies seamlessly into your project.

Here's what the Vite configuration file looks like when using Vue.js:

```js
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
            detectTls: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
```

If you are deciding how much frontend scaffolding you want Laravel to do for you, these are the next reads I would compare with it:

- [Add Vue to Laravel without overbuilding the frontend](/laravel-vue)
- [Add Alpine to Laravel when you just need lightweight interactivity](/alpine-js-laravel)
- [Swap npm out for Bun in Laravel without friction](/bun-laravel)
- [Add Tailwind to Laravel without setup guesswork](/tailwind-css-laravel)
- [See when Laravel Volt is the simpler Livewire option](/laravel-volt)
