---
id: "01KKEW278VP5VASSAJG962YA1M"
title: "Style an HTML dialog's backdrop with Tailwind CSS"
slug: "dialog-backdrop-styling-tailwind-css"
author: "benjamincrozat"
description: "Discover how to style an HTML dialog's backdrop using Tailwind CSS."
categories:
  - "css"
  - "tailwind-css"
published_at: 2023-11-02T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/NlfygLpBI4ybWcu.jpg"
sponsored_at: null
---
## How to style the backdrop of the HTML dialog element using Tailwind CSS

**To style a native HTML dialog's backdrop, use the `backdrop:` modifier introduced in Tailwind CSS 3.1.**

```html
<dialog class="backdrop:bg-black/50 backdrop:backdrop-blur-md">
	<p>Lorem ipsum dolor sit amet.</p>
</dialog>
```

[I made a working CodePen](https://codepen.io/benjamincrozat/pen/poGERgV) for those curious to see how all this works.

(Using the class to add a backdrop filter to the dialog's backdrop is a bit weird, but it works!)

## Browser support for the HTML dialog element

I was surprised to see how well this relatively new dialog HTML element is supported.

At the time I'm writing these lines, the `dialog` element is supported by:
- Firefox 98+
- Firefox for Android 118+
- Google Chrome 37+
- Google Chrome for Android 128+
- Opera 24+
- Safari/Mobile Safari 15.4+

[Check out the support chart on Can I use](https://caniuse.com/dialog).

If this article on "Style an HTML dialog's backdrop with Tailwind CSS" hit the mark, keep the momentum going here:

- [5 Tailwind CSS best practices for 2025](/tailwind-css)
- [Tailwind CSS typography plugin: a step-by-step build guide](/tailwind-css-typography-plugin)
- [Tailwind CSS forms plugin: my 2025 guide for v4 and v3](/tailwind-css-forms-plugin)
- [Disable hover styles on mobile with Tailwind CSS](/disable-hover-styles-mobile-tailwind-css)
- [Add Tailwind CSS to any Laravel project](/tailwind-css-laravel)
- [The lh and rlh CSS units: simple spacing that follows your text](/css-lh-rlh)
- [Style labels on focus the right way in CSS](/label-focus-css)
- [25 Laravel best practices, tips, and tricks](/laravel-best-practices)

