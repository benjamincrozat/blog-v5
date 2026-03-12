---
id: "01KKEW27N7017PH7V6DXV5QAYV"
title: "Tailwind CSS forms plugin: my 2025 guide for v4 and v3"
slug: "tailwind-css-forms-plugin"
author: "benjamincrozat"
description: "Discover how the Tailwind CSS forms plugin can reset your forms to a consistent state across all browsers and make styling easier."
categories:
  - "css"
  - "tailwind-css"
published_at: 2023-02-12T00:00:00+01:00
modified_at: 2025-09-28T05:36:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K67DS49MSH97HWXBD0HZMBDM.png"
sponsored_at: null
---
## Introduction

Tailwind CSS is a utility-first CSS framework for productive frontend developers.

If you have never heard of it, let me [introduce you to the marvelous world of pragmatic CSS frameworks](https://benjamincrozat.com/tailwind-css).

In this article, I’ll show you its forms plugin. It helps unify form appearance across browsers and makes them easier to customize.

## What is @tailwindcss/forms?

[@tailwindcss/forms](https://github.com/tailwindlabs/tailwindcss-forms) is an official plugin that resets forms to a consistent state across all browsers and makes them easy to style.

### What the plugin does and doesn’t style

The tailwind forms plugin targets common controls like text inputs, selects, textareas, checkboxes, and radios so they look consistent. Some uncommon controls, like `input[type="range"]`, are intentionally not styled. This avoids using a broad `input` selector that could cause unwanted side effects. See the full list in the [plugin README](https://github.com/tailwindlabs/tailwindcss-forms).

## Installation

Here is how to do a Tailwind v4 plugin install, plus a v3 fallback.

### Installation for Tailwind v4

Install as a dev dependency:

```bash
npm install -D @tailwindcss/forms
```

Or with Yarn:

```bash
yarn add -D @tailwindcss/forms
```

Register the plugin in your main stylesheet after importing Tailwind:

```css
/* app.css or main.css */
@import "tailwindcss";
@plugin "@tailwindcss/forms";
```

### Installation for Tailwind v3 (legacy)

Install as a dev dependency:

```bash
npm install -D @tailwindcss/forms
```

Or with Yarn:

```bash
yarn add -D @tailwindcss/forms
```

Then enable it in your Tailwind config:

```js
// tailwind.config.js
module.exports = {
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
```

Note: install as a dev dependency and rebuild your CSS as part of your normal build step.

## Usage in your forms

First, I recommend you try the [live demo](https://tailwindcss-forms.vercel.app/).

Important: styles apply only to specific input types. A plain `<input>` with no `type` will not pick up styles. Make sure to set a type like `type="text"` or one from the list below.

Once the forms plugin is ready, your forms get clean, accessible defaults.

Here are all the supported form elements:

- `input[type='text']`
- `input[type='password']`
- `input[type='email']`
- `input[type='number']`
- `input[type='url']`
- `input[type='date']`
- `input[type='datetime-local']`
- `input[type='month']`
- `input[type='week']`
- `input[type='time']`
- `input[type='search']`
- `input[type='tel']`
- `input[type='checkbox']`
- `input[type='radio']`
- `select`
- `select[multiple]`
- `textarea`

As mentioned in the README on the [official GitHub repository](https://github.com/tailwindlabs/tailwindcss-forms), you must at least use `type="text"` (or any of the types mentioned above) for the styles to take effect.

> This is a trade-off to avoid relying on a greedy `input` selector and unintentionally styling elements the plugin does not handle yet, like `input[type="range"]`.

Now you can style a select element: this is a simple way to style select with Tailwind.

```html
<select class="px-4 py-3 rounded-full shadow">
  ...
</select>
```

You can also change a checkbox color using text color utilities (tailwind checkbox color):

```html
<input type="checkbox" class="rounded text-green-400" />
```

Tip: Tailwind’s native accent color utilities work great too. For example, `accent-purple-600` on a checkbox or radio. See the [accent-color docs](https://tailwindcss.com/docs/accent-color).

## Use classes instead of global styles for your forms

In some cases, you may want a less opinionated approach for existing projects, so instead of global resets you can use the classes provided by the plugin.

The `strategy` option controls this: `base` generates global resets; `class` is opt-in.

| Base                    | Class              |
| ----------------------- | ------------------ |
| `[type='text']`         | `form-input`       |
| `[type='email']`        | `form-input`       |
| `[type='url']`          | `form-input`       |
| `[type='password']`     | `form-input`       |
| `[type='number']`       | `form-input`       |
| `[type='date']`         | `form-input`       |
| `[type='datetime-local']` | `form-input`     |
| `[type='month']`        | `form-input`       |
| `[type='search']`       | `form-input`       |
| `[type='tel']`          | `form-input`       |
| `[type='time']`         | `form-input`       |
| `[type='week']`         | `form-input`       |
| `textarea`              | `form-textarea`    |
| `select`                | `form-select`      |
| `select[multiple]`      | `form-multiselect` |
| `[type='checkbox']`     | `form-checkbox`    |
| `[type='radio']`        | `form-radio`       |

To opt into class-based styling in Tailwind CSS v4, configure the plugin like this:

```css
/* app.css */
@plugin "@tailwindcss/forms" {
  strategy: "base"; /* Only generate global styles. */
  strategy: "class"; /* Only generate classes. */
}
```

And in Tailwind CSS v3:

```js
// tailwind.config.js
module.exports = {
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'base', // Only generate global styles.
            strategy: 'class', // Only generate classes.
        }),
    ],
}
```

## Build a beautiful newsletter form

This blog’s interface was built with Tailwind CSS, and I also use the forms plugin. Why don't we create something like a newsletter form just to get our hands dirty?

![A newsletter form made with Tailwind CSS and its forms plugin.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/5lOOQ04LlOkH8RYdPzAMa5jJVxFLuZ6qflrxlsKz.png/public)

Let’s start with the label and input field ([live demo on Tailwind Play](https://play.tailwindcss.com/dS9TDg6Uav)):

```html
<input type="email" placeholder="homer@simpson.com" class="w-full rounded-md border-0 px-4 py-3 placeholder-gray-300 shadow" />

<button class="mt-2 block w-full rounded-md bg-gradient-to-r from-purple-300 to-purple-400 px-4 py-3 font-semibold text-white shadow-lg transition-all duration-500 hover:-hue-rotate-90">
	  Subscribe
</button>
```

1. `w-full`: makes the input take the full width.
2. `rounded-md`: makes the border rounded.
3. `border-0`: the Tailwind CSS forms plugin adds a border by default, so this removes it.
4. `px-4 py-3`: padding values that feel right here.
5. `placeholder-gray-300`: placeholder text is lighter, so it reads as a hint.
6. `shadow`: adds a small box shadow for depth.
7. `focus-visible:ring-2 focus-visible:ring-purple-400 focus-visible:ring-offset-2`: gives a clear focus style for accessibility.

Then, the button:

```html
<button
    class="mt-2 w-full rounded-md bg-gradient-to-r from-purple-300 to-purple-400 px-4 py-3 font-semibold text-white shadow-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-purple-400 focus-visible:ring-offset-2"
>
    Subscribe
</button>
```

1. `mt-2`: adds a bit of spacing.
2. `bg-gradient-to-r from-purple-300 to-purple-400`: a soft purple gradient from left to right.
3. `font-semibold`: slightly bolder text.
4. `text-white`: white text.
5. `shadow-lg`: a larger shadow that helps the button stand out.
6. `focus-visible:ring-2 ...`: a visible focus ring.

Accessibility note: always pair inputs with labels, do not rely on placeholder alone, and make sure focus states are visible. If you include checkboxes or radios, you can use `accent-*` classes like `accent-purple-600` for a clear, native color. See the [accent-color docs](https://tailwindcss.com/docs/accent-color).

## FAQ

### Why aren’t my inputs styled?

Add a specific `type` like `type="text"`. Check that the plugin is installed for your Tailwind version: v4 uses the `@plugin` directive in your CSS; v3 uses `plugins: [require('@tailwindcss/forms')]` in `tailwind.config.js`. Also note that inputs like `type="range"` are not styled. See the [plugin README](https://github.com/tailwindlabs/tailwindcss-forms).

### How do I opt out of global styles?

Use the plugin option `strategy: 'class'` so styles apply only when you add classes like `form-input`. This is the tailwind plugin strategy class vs base toggle.

### How do I change checkbox or radio color?

You can keep using `text-{color}` with the plugin, or use modern `accent-{color}` (for example, `accent-green-600`) for better native behavior. See the [accent-color docs](https://tailwindcss.com/docs/accent-color).

## Conclusion

The Tailwind CSS forms plugin gives you consistent, easy-to-style controls out of the box. Use `base` when you want global resets across your app; use `class` when you want opt-in control with utilities like the `form-input` class. Try the [official live demo](https://tailwindcss-forms.vercel.app/) and experiment with my [Tailwind Play example](https://play.tailwindcss.com/qZ5rc9oEMd).

If you are still smoothing out form UI after this plugin pass, these are the next Tailwind reads I would keep open:

- [Tighten your Tailwind habits before the CSS gets messy](/tailwind-css)
- [Make long-form content look better with Tailwind's typography plugin](/tailwind-css-typography-plugin)
- [Disable hover styles on touch devices without fighting Tailwind](/disable-hover-styles-mobile-tailwind-css)
