---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/194/LpHvHmvUKRwWojXHVS9uThtKeNLqWv-metabGFyYXZlbC12dWUuanBn-.jpg
Title: "Add Vue.js to any Laravel project"
Description: "Let me walk you through adding Vue.js to your Laravel project and be done with it in 5 minutes."
Published at: 2023-10-13
Categories: vuejs, laravel, javascript
---

## Introduction to Vue.js in Laravel

[Vue.js](https://vuejs.org) is a JavaScript framework for building user interfaces.

While it's flexible enough to be integrated into any web project (Rails, Symfony, WordPress, etc.), it's one of the preferred choices of Laravel developers, especially when coupled to [Inertia.js](https://inertiajs.com).

That being said, figuring out how to set up your bundling process while using a major JavaScript framework is insanely complicated.

Therefore, I decided to write this short guide that walks you through adding Vue.js to your Laravel project.

## Install Vue.js in Laravel via NPM

First, add Vue and the plugin that will enable a seemless integration with Vite (the default bundler used by Laravel).

```bash
npm install vue @vitejs/plugin-vue
```

## Configure Vite for Vue.js in Laravel

In the previous step, we added a crucial plugin that enables support for Vue in Vite. We now must make use of it.

In *vite.config.js*, make the following modifications:

```js
import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
})
```

**Important**: The alias from `vue` to `vue.esm-bundler.js` instructs Vite to use a version of Vue.js that also bundles the compiler which will allow us to write HTML instead of [`render()` functions](https://vuejs.org/guide/extras/render-function.html) (thankfully!).

## Initialize Vue.js

Inside *resources/js/app.js*, initialize Vue by adding the following:

```js
import { createApp } from 'vue'

const app = createApp()

app.mount('#app')
```

1. We import Vue and create a variable for the `createApp()` function.
2. Then, we instantiate Vue by calling the function and store it in a constant called `app` (you will see later why).
3. Finally, we mount our Vue.js application inside a `#app` element that we will create.

Now, do not forget to include your JavaScript using the `@vite` directive and create a `<div>` tag with an "app" ID in your HTML.

```html
<!DOCTYPE html>
<html>
	<head>
		…
		
		@vite(['resources/js/app.js'])
	</head>
	<body>
		<div id="app">
			<!-- Vue.js components will be processed here. -->
		</div>
	</body>
</html>
```

## Make sure Vue.js is operational

Create a component called *Counter* in *resources/js/components/Counter.vue*:

```html
<script setup>
    import { ref } from 'vue'

    const count = ref(0)
</script>

<template>
    {{ count }}

    <button @click="count++">
        Add
    </button>
</template>
```

Register *Counter.vue* to let Vue know of its existence:

```js
import { createApp } from 'vue'
import Counter from './components/Counter.vue'

const app = createApp()

app.component('counter', Counter)

app.mount('#app')
```

Then, call it in the `div#app` we set up earlier:

```html
<div id="app">
    <counter />
</div>
```

## Compile your code

The only step left if to compile your code and preview the result in your browser.

Run the following command:

```bash
npm run dev
```

That's all there is to it! Check your browser and it all should be working. 

You've successfully added Vue.js to your Laravel project and you can now start having fun by building something amazing!
