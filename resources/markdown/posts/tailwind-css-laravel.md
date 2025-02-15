---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/188/wTyOjDdruQLDLu3DcBaYGpPAucmRsJ-metadGFpbHdpbmQtY3NzLWxhcmF2ZWwuanBn-.jpg
Title: "Add Tailwind CSS to any Laravel project"
Description: "See how easy it is to add Tailwind CSS to any Laravel project and start building an amazing user interface."
Published at: 2023-10-05
Categories: laravel, tailwind-css
---

## Introduction to Tailwind CSS in Laravel

Tailwind CSS is a great CSS framework based on the utility-first approach. I wrote extensively about it already ([Tailwind CSS: the ultimate guide to get started](/tailwind-css)) if you want to get up to speed.

Let's see how easy it is to add it in your Laravel project.

## How to add Tailwind CSS to any Laravel project

### Create a new Laravel project with Jetstream

If needed, you can create a new Laravel using [Jetstream](https://jetstream.laravel.com/introduction.html), which sets your frontend with Tailwind CSS and the JavaScript framework of your choice.

```bash
laravel new example --jet
```

Once you run `npm install` and `npm run dev`, using Tailwind CSS is a breeze!

### Add Tailwind CSS in Laravel via NPM/Yarn/Bun

If you are not creating a new Laravel project, you should leverage your JavaScript package manager, no matter if it's NPM, Yarn, or Bun.

Tailwind CSS requires other dependencies such as [PostCSS](https://postcss.org), and one of its plugins called [autoprefixer](https://github.com/postcss/autoprefixer). Actually, Tailwind CSS itself is a PostCSS plugin.

If you are using NPM, run the following command:

```bash
npm install autoprefixer postcss tailwindcss
```

If you are using Yarn:

```bash
yarn add autoprefixer postcss tailwindcss
```

If you are using Bun:

```bash
bun add autoprefixer postcss tailwindcss
```

### Publish Tailwind's configuration file in your Laravel codebase

Tailwind CSS is an extremely customizable framework. You will also need to configure where it should look to [purge all its unused classes](https://tailwindcss.com/docs/content-configuration) in order to slim down your final file size.

If you are using NPM, run the following command:

```bash
npx tailwindcss init -p
```

If you are using Yarn:

```bash
yarn tailwindcss init -p
```

If you are using Yarn:

```bash
bun run tailwindcss init -p
```

Here's what your newly generated *tailwind.config.js* file should look like:

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}
```

The `ìnit` command also generated another config file, 
postcss.config.js*, that instructs PostCSS to use Tailwind CSS and autoprefixer:

```js
module.exports = {
    plugins: {
        tailwindcss: {},
        autoprefixer: {},
    },
}
```

### Add Tailwind's directives to your CSS

In *resources/css/app.css*, add the following directives:

```css
@tailwind base;
@tailwind components;
@tailwind utilities;
/* This one is not necessary. Tailwind will 
automatically inject it at compile time. */
@tailwind variants;
```

Tailwind CSS will now add its utility classes in your final *app.css* file whenever you are compiling your project.

### Compile your project with Tailwind CSS

Using [Vite](https://vitejs.dev), Laravel offers by default to compile your assets using two commands.

This one allows you to automatically refresh your browser whenever you make a change to a file:

If you are using NPM, run the following command:

```bash
npm run dev
```

If you are using Yarn:

```bash
yarn dev
```

If you are using Bun:

```bash
bun run dev
```

And this one lets you compile your CSS (and JavaScript) for production:

If you are using NPM, run the following command:

```bash
npm run build
```

If you are using Yarn:

```bash
yarn build
```

If you are using Bun:

```bash
bun run build
```

### Enable Tailwind CSS by linking your CSS in your webpage

Laravel exposes a new Blade directive called `@vite`. It lets you automatically add the necessary `link` HTML tag to style your webpage:

```blade
<!DOCTYPE html>
<html>
    <head>
        …
		
        @vite('resources/css/app.css')
    </head>
    <body>
        …
    </body>
</html>
```

That's it, you're now done with the boring work. Go build something great!
