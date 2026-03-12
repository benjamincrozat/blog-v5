---
id: "01KKEW2785HF8ADVK526FB2CJC"
title: "The lh and rlh CSS units: simple spacing that follows your text"
slug: "css-lh-rlh"
author: "benjamincrozat"
description: "Learn how to use CSS lh and rlh units for spacing that scales with text, plus examples, Tailwind tips, and full browser support."
categories:
  - "css"
published_at: 2025-10-02T14:47:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6N8KW62CBDPA0KPBHVYECH2.webp"
sponsored_at: null
---
## TL;DR

`1lh` equals one line of the current element’s text. `1rlh` equals one line of the root element’s text. Use them to size margins, padding, and blocks in “number of lines,” not pixels. Support is strong in modern browsers: Chrome 109+ / Edge 109+ / Firefox 120+ / Safari 16.4+ (Samsung Internet 21+ for `lh` and 22+ for `rlh`). Check the live tables on **[Can I use: `lh`](https://caniuse.com/mdn-css_types_length_lh)** and **[Can I use: `rlh`](https://caniuse.com/mdn-css_types_length_rlh)**.

## Introduction to lh and rlh

I like layouts where spacing grows with the font, not against it. The CSS `lh` and `rlh` units do exactly that. They let me say “make this gap one line tall” instead of guessing pixels. It keeps rhythm tight and reading easy. The idea is simple, and the pay-off is big.

## What the lh and rlh units mean

* **`lh`**: one **line-height** of the element where you use it. So `2lh` is two lines tall, `0.5lh` is half a line. Defined in the **[CSS Values & Units Level 4](https://www.w3.org/TR/css-values-4/#lh)** spec.
* **`rlh`**: one **line-height of the root** (usually `<html>`). It’s the `rem`-style cousin of `lh`, defined here: **[CSS Values & Units Level 4 (`rlh`)](https://www.w3.org/TR/css-values-4/#rlh)**.

You can use them anywhere a `<length>` is allowed. Note: real line boxes can be a bit taller when content is funky (emoji, tall glyphs). Treat `lh` as the *intended* line height. (Good explainer here: **[Web-features explorer: `lh`](https://web-platform-dx.github.io/web-features-explorer/features/lh/)**.)

## Why I use them

* **Type scales, spacing follows.** Change `font-size` or `line-height`, and the gaps update too.
* **Cleaner vertical rhythm.** Paragraph margins, section padding, and scroll offsets match the text grid.
* **Fewer magic numbers.** Less “24px because it looked okay on my laptop.”

## Quick cheats you can paste

```css
/* 1) Set the site rhythm once */
html { line-height: 1.5; }     /* unitless is best */
:root { --rhythm: 1rlh; }      /* one root line */

/* 2) Paragraph spacing that tracks the font */
p { margin-block: 1lh; }

/* 3) Headings and subtle tweaks */
h1 { margin-block: 1.5rlh; }
small { display:block; margin-block-start: 0.5lh; }

/* 4) Buttons sized by text, not pixels */
.button {
  line-height: 1;
  padding-block: 0.5lh;
  padding-inline: 0.75lh;
}

/* 5) Anchor offsets that feel right */
:target { scroll-margin-top: 2rlh; }

/* 6) Safe fallback for very old browsers */
@supports (margin-block: 1lh) {
  article p { margin-block: 1lh; }
}
@supports not (margin-block: 1lh) {
  article p { margin-block: 1.5em; }
}
```

## Gotchas (keep these in mind)

* **Line boxes can grow.** Tall emoji or inline images can exceed the theoretical line height. If you need a hard multi-line clamp, use [`line-clamp`](https://developer.mozilla.org/en-US/docs/Web/CSS/line-clamp) (and legacy `-webkit-line-clamp` combo where needed).
* **Define the root rhythm.** Set `html { line-height: … }` once so `rlh` is consistent across the app.

## Browser compatibility for lh and rlh

Modern engines ship these. Here are the versions that matter right now:

| Browser             | `lh` support | `rlh` support |
| ------------------- | ------------ | ------------- |
| Chrome              | 109+         | 111+          |
| Edge                | 109+         | 111+          |
| Firefox             | 120+         | 120+          |
| Safari (macOS)      | 16.4+        | 16.4+         |
| Safari (iOS/iPadOS) | 16.4+        | 16.4+         |
| Samsung Internet    | 21+          | 22+           |

**Sources:** **[Can I use: `lh`](https://caniuse.com/mdn-css_types_length_lh)**, **[Can I use: `rlh`](https://caniuse.com/mdn-css_types_length_rlh)**.

## How I roll lh and rlh into a project

1. Set a unitless root line height:

   ```css
   html { line-height: 1.5; }
   :root { --space-line: 1rlh; }
   ```
2. Use `lh`/`rlh` for spacing, not pixels:

   ```css
   p { margin-block: 1lh; }
   h1 { margin-block: 1.5rlh; }
   :target { scroll-margin-top: 2rlh; }
   ```
3. Give yourself a fallback for older browsers:

   ```css
   @supports not (margin-block: 1lh) {
     p { margin-block: 1.5em; }
   }
   ```
4. Prefer logical properties (`margin-block`, `padding-block`) so it also works in vertical writing modes.

## How I roll lh and rlh into a project using Tailwind CSS

Tailwind plays well with `lh`/`rlh` thanks to **arbitrary values**.

**Base setup**

```css
/* globals.css */
@layer base {
  html { line-height: 1.5; }      /* site rhythm */
  :root { --space-line: 1rlh; }   /* optional token */
}
```

**Use in markup (no plugin needed)**
Arbitrary values are first-class: see **[Tailwind: utility classes](https://tailwindcss.com/docs/styling-with-utility-classes)** and **[scroll-margin utilities](https://tailwindcss.com/docs/scroll-margin)**.

```html
<!-- Paragraph spacing -->
<p class="mb-[1lh]">...</p>

<!-- Headings -->
<h1 class="my-[1.5rlh]">Title</h1>

<!-- Buttons sized by text -->
<button class="leading-none py-[0.5lh] px-[0.75lh]">Buy</button>

<!-- Smooth anchor offsets -->
<h2 id="features" class="scroll-mt-[2rlh]">Features</h2>

<!-- Token-based if you prefer vars -->
<section class="pt-[var(--space-line)] pb-[calc(2*var(--space-line))]">...</section>
```

**Optional: tiny utility with fallback**

```css
/* globals.css */
@layer utilities {
  .mb-lh { margin-block: 1.5em; }         /* fallback */
  @supports (margin-block: 1lh) {
    .mb-lh { margin-block: 1lh; }         /* modern override */
  }
}
```

## Extra reading (short and sweet)

If you want more background and examples from the platform teams, check the **[CSS Values & Units Level 4](https://www.w3.org/TR/css-values-4/)** spec and WebKit’s write-up **[“Polishing your typography with line height units”](https://webkit.org/blog/16831/line-height-units/)**. For quick references, see **[MDN: `<length>`](https://developer.mozilla.org/en-US/docs/Web/CSS/length)** and **[web.dev: November 2023 platform updates](https://web.dev/blog/web-platform-11-2023)** (Firefox added `lh`/`rlh` then).

## Conclusion

`lh` and `rlh` let me size space in “lines,” not pixels. That simple shift makes layouts scale with the text and keeps pages readable with less effort. Browser support is strong across modern engines (and you can always double-check the latest versions on **[Can I use: `lh`](https://caniuse.com/mdn-css_types_length_lh)** and **[Can I use: `rlh`](https://caniuse.com/mdn-css_types_length_rlh)**) so you can ship this today with a tiny `@supports` fallback for the long tail.

If you want that same text-driven sizing logic to spill into the rest of your UI, these are good next reads:

- [Style labels on focus the right way in CSS](/label-focus-css)
- [Style an HTML dialog's backdrop with Tailwind CSS](/dialog-backdrop-styling-tailwind-css)
- [Tailwind CSS typography plugin: a step-by-step build guide](/tailwind-css-typography-plugin)
- [Tailwind CSS forms plugin: my 2025 guide for v4 and v3](/tailwind-css-forms-plugin)
- [5 Tailwind CSS best practices for 2025](/tailwind-css)
