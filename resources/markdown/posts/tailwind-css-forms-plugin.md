---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/30/Screenshot_2023-02-14_at_14.07.45_s3dv4g.png
Title: "Tailwind CSS forms plugin: a step-by-step build guide"
Description: "Discover how the Tailwind CSS forms plugin can reset your forms to a consistent state across all browsers and make styling easier."
Published at: 2023-02-12
Categories: css, tailwind-css
---

## Introduction

Tailwind CSS is an utility-first CSS framework for productive front-end developers.

If you never heard about it, let me [introduce you to the marvelous world of pragmatic CSS frameworks](https://benjamincrozat.com/tailwind-css).

In this article, we will learn about its forms plugin, which helps unifying their appearance across browsers and customize them more easily.

## What is @tailwindcss/forms?

[@tailwindcss/forms](https://github.com/tailwindlabs/tailwindcss-forms) is an officially provided plugin that resets forms to a consistent state across all browsers and makes them easily stylable.

## Installation

The plugin can be installed with this simple NPM command:

```bash
npm install @tailwindcss/forms
```

Or with Yarn:

```bash
yarn add @tailwindcss/forms
```

Then, import the plugin in your *tailwind.config.js* file:

```js
// tailwind.config.js
module.exports = {
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
```

## Usage in your forms

First, I recommend you try the [live demo](https://tailwindcss-forms.vercel.app/).

Once the forms plugin is ready, all your forms will have basic styling with accessibility in mind.

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

As mentioned in the README on the [official GitHub repository](https://github.com/tailwindlabs/tailwindcss-forms), you must at least use `type="text"` (or any of the types mentioned above for the styles to take effect.

> This is a necessary trade-off to avoid relying on the overly greedy `input` selector and unintentionally styling elements we don't have solutions for yet, like `input[type="range"]`, for example.

From now on, you will be able to style `select` elements:

```html
<select class="px-4 py-3 rounded-full shadow">
    …
</select>
```

And even change a checkbox color using text color utilities:

```html
<input type="checkbox" class="rounded text-green-400" />
```

## Use classes instead of global styles for your forms

In some cases, you may want to be less hardcore. I'm thinking about existing projects, for instance. 

Therefore, instead of applying global styles, you could use the classes provided by the plugin.

| Base                      | Class              |
| ------------------------- | ------------------ |
| `[type='text']`           | `form-input`       |
| `[type='email']`          | `form-input`       |
| `[type='url']`            | `form-input`       |
| `[type='password']`       | `form-input`       |
| `[type='number']`         | `form-input`       |
| `[type='date']`           | `form-input`       |
| `[type='datetime-local']` | `form-input`       |
| `[type='month']`          | `form-input`       |
| `[type='search']`         | `form-input`       |
| `[type='tel']`            | `form-input`       |
| `[type='time']`           | `form-input`       |
| `[type='week']`           | `form-input`       |
| `textarea`                | `form-textarea`    |
| `select`                  | `form-select`      |
| `select[multiple]`        | `form-multiselect` |
| `[type='checkbox']`       | `form-checkbox`    |
| `[type='radio']`          | `form-radio`       |

And to make sure the plugin does not apply the global styles, you can pass parameters when initializing it:

```js
// tailwind.config.js
module.exports = {
    plugins: [
        require('@tailwindcss/forms')({
			// strategy: 'base',
			strategy: 'class',
		}),
    ],
}
```

## Build a beautiful newsletter form

This blog's user interface has been built with Tailwind CSS, and I make use of the forms plugin.

You may have noticed the form to subscribe to the newsletter on the home page.

![A newsletter form make with Tailwind CSS and its forms plugin.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/136/conversions/Screenshot_2023-02-12_at_18.48.42_nezn2e-medium.jpg)

Why don't we recreate it?

Let's take a look at the few easy steps ([live demo on Tailwind Play](https://play.tailwindcss.com/qZ5rc9oEMd)).

Let's start with the input field:

```html
<input
    type="email"
	placeholder="homer@simpson.com"
	class="w-full rounded-md border-0 px-4 py-3 placeholder-gray-300 shadow"
/>
```

1. `w-full`, to make sure the input takes the full width.
2. `rounded-md`, to make the border rounded.
3. `border-0`, because the Tailwind CSS forms plugin applies a border by default.
4. `px-4 py-3`, which are padding values (`padding: .75rem 1rem`) that look right for my form.
5. `placeholder-gray-300`, because the default placeholder text color doesn't look right.
6. And finally, `shadow`, which applies a small `box-shadow` that makes it look extremely cool.

Then, the button:

```html
<button
    class="mt-2 w-full rounded-md bg-gradient-to-r from-purple-300
    to-purple-400 px-4 py-3 font-semibold text-white shadow-lg"
>
    Subscribe
</button>
```

1. `mt-2`, because we need a bit of spacing.
2. `bg-gradient-to-r from-purple-300 to-purple-400`, for a beautiful purple gradient that starts from the left and goes to the right.
3. `font-semibold` for a slightly bolded text.
4. `text-white`, which is also self-explanatory.
5. `shadow-lg`, for a large shadow that makes the button more clickable.

