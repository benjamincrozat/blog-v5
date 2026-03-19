---
id: "01KKEW27N7017PH7V6DXV5QAYV"
title: "How to use @tailwindcss/forms in Tailwind CSS"
slug: "tailwind-css-forms-plugin"
author: "benjamincrozat"
description: "Learn how to install and use @tailwindcss/forms in Tailwind CSS v4 and v3, then choose between the base and class strategies."
categories:
  - "css"
  - "tailwind-css"
published_at: 2023-02-11T23:00:00Z
modified_at: 2026-03-19T22:59:10Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K67DS49MSH97HWXBD0HZMBDM.png"
sponsored_at: null
---
## Introduction

[`@tailwindcss/forms`](https://github.com/tailwindlabs/tailwindcss-forms) is the official Tailwind CSS forms plugin. It resets form elements like inputs, selects, textareas, checkboxes, and radios so they are easier to style with utilities.

If you searched for `tailwindcss forms` or `tailwind forms`, this is the package you want.

## Quick answer

Install the plugin:

```bash
npm install -D @tailwindcss/forms
```

Then register it.

In Tailwind CSS v4:

```css
@import "tailwindcss";
@plugin "@tailwindcss/forms";
```

In Tailwind CSS v3:

```js
module.exports = {
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
```

After that, normal form elements get cleaner defaults that are much easier to customize.

## What @tailwindcss/forms does

The plugin gives common form controls a consistent starting point across browsers. That includes elements like:

- text inputs
- email inputs
- selects
- textareas
- checkboxes
- radio buttons

It does **not** try to style every possible input type. That is intentional. The plugin focuses on the controls it can reset predictably without broad selectors creating surprises elsewhere.

## Install @tailwindcss/forms in Tailwind CSS v4

Install the package:

```bash
npm install -D @tailwindcss/forms
```

Register it in your main stylesheet:

```css
/* app.css */
@import "tailwindcss";
@plugin "@tailwindcss/forms";
```

That is the whole setup for the default behavior.

## Install @tailwindcss/forms in Tailwind CSS v3

Install the package:

```bash
npm install -D @tailwindcss/forms
```

Then enable it in `tailwind.config.js`:

```js
module.exports = {
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
```

Rebuild your CSS after changing the config.

## Use @tailwindcss/forms with the default base strategy

The default plugin behavior is the `base` strategy. That means it applies global resets to supported form elements automatically.

```html
<input type="email" class="rounded-md px-4 py-3 shadow-sm" />

<select class="rounded-md px-4 py-3 shadow-sm">
    <option>France</option>
</select>
```

This is the easiest option when you want better-looking forms across the whole project without adding special plugin classes everywhere.

## Use @tailwindcss/forms with the class strategy

If you do **not** want global resets, switch to the `class` strategy and opt in manually.

### Tailwind CSS v4 class strategy

```css
@import "tailwindcss";
@plugin "@tailwindcss/forms" {
  strategy: "class";
}
```

Then use the generated classes:

```html
<input type="text" class="form-input rounded-md px-4 py-3 shadow-sm" />
<select class="form-select rounded-md px-4 py-3 shadow-sm"></select>
<textarea class="form-textarea rounded-md px-4 py-3 shadow-sm"></textarea>
```

### Tailwind CSS v3 class strategy

```js
module.exports = {
    plugins: [
        require('@tailwindcss/forms')({
            strategy: 'class',
        }),
    ],
}
```

Use this strategy when you are adding the plugin to an existing project and you do not want it to restyle every form control globally.

## Base vs class: which strategy should you choose?

| Strategy | Best when | Tradeoff |
| --- | --- | --- |
| `base` | You want the plugin to improve form controls project-wide | Global resets can affect more existing markup |
| `class` | You want opt-in control with classes like `form-input` | You must remember to add the plugin classes manually |

For new projects, `base` is usually the easiest starting point. For older codebases or design systems, `class` can be safer.

## Common examples with @tailwindcss/forms

### Style a text input

```html
<input
    type="text"
    class="w-full rounded-md px-4 py-3 shadow-sm focus:ring-2 focus:ring-sky-500"
    placeholder="Your email"
/>
```

### Style a select

```html
<select class="rounded-md px-4 py-3 shadow-sm">
    <option>Starter</option>
    <option>Pro</option>
</select>
```

### Style a checkbox or radio

```html
<input type="checkbox" class="rounded text-emerald-600" />
<input type="radio" class="text-sky-600" />
```

You can also use Tailwind's native `accent-*` utilities when you want a simpler color override.

## Why @tailwindcss/forms is not working

If your inputs are not changing, check these first:

- the plugin is installed and registered for your Tailwind version
- you rebuilt your CSS after the config change
- your input has a supported `type`, such as `text`, `email`, or `password`
- you are not using `strategy: 'class'` without adding `form-input`, `form-select`, or similar classes

A plain `<input>` without a supported `type` is a very common reason people think the plugin failed.

## FAQ

### Is @tailwindcss/forms an official Tailwind plugin?

Yes. It is maintained by Tailwind Labs.

### Do I need @tailwindcss/forms in Tailwind CSS v4?

Only if you want its form reset behavior. Tailwind CSS v4 does not include it automatically.

### Should I use base or class?

Use `base` for project-wide defaults. Use `class` when you want opt-in control.

### Does the plugin style every input type?

No. It intentionally focuses on the common form controls it can style predictably.

## Conclusion

`@tailwindcss/forms` is the Tailwind package most people mean when they search for `tailwindcss forms`. Install it, register it for your Tailwind version, and then choose between `base` for global resets or `class` for opt-in styling. Once that is in place, styling form controls with Tailwind utilities becomes much easier.

If you are still smoothing out form UI after this plugin pass, these are the next Tailwind reads I would keep open:

- [Tighten your Tailwind habits before the CSS gets messy](/tailwind-css)
- [Make long-form content look better with Tailwind's typography plugin](/tailwind-css-typography-plugin)
- [Disable hover styles on touch devices without fighting Tailwind](/disable-hover-styles-mobile-tailwind-css)
