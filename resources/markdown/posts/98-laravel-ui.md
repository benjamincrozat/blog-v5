---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/252/01HF2W5D0334RMN2NHMAY1RB8G.jpg
Title: "Get started with laravel/ui; kickstart your project."
Description: "Let me show you how to leverage the laravel/ui package to scaffold authentication features on top of your favorite frontend framework."
Published at: 2023-11-12
Modified at: 2024-01-25
Categories: laravel, packages
---

## Introduction to the laravel/ui package

When working on a web project destined to get some users, having a user-friendly interface is necessary.

That's where [laravel/ui](https://github.com/laravel/ui) comes into play. It offers a basic yet effective starting point for incorporating some CSS and a JavaScript framework into your Laravel projects. It even supports scaffolding pages related to the authentication of your users.

Now, before you continue, **please note that while Laravel UI is still maintained, it's also an old package that has better alternatives such as [Laravel Jetstream](https://jetstream.laravel.com) and [Laravel Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)**. For instance, Laravel UI does not support as many authentication features as these two.

But I noticed that people are still looking for it, so why not write a small article about it anyway?

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

![Laravel UI in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/251/conversions/01HF2W1C4VPWG75KMVAJT14QC7-medium.jpg)

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
