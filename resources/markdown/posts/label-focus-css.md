---
id: "01KKEW27AW03VEEAAYZEWD4WNC"
title: "Style labels on focus the right way in CSS"
slug: "label-focus-css"
author: "benjamincrozat"
description: "You can’t rely on label:focus. Here’s how I actually style labels on input focus using :focus-within, :has(), and sibling selectors, with accessible defaults."
categories:
  - "css"
published_at: 2025-10-07T16:30:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6ZR3MDZ6K5HC6AB4PARQQSV.png"
sponsored_at: null
---
## TL;DR

I almost never use `label:focus`. Labels aren’t focusable by default and putting them in the tab order is bad for keyboard users. What I ship instead:

1. **Label wraps the input** → `label:focus-within { … }` (labels react when descendants are focused). See [`:focus-within`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-within).
2. **Label comes before the input** → `label:has(+ input:focus-visible) { … }` with [`:has()`](https://developer.mozilla.org/en-US/docs/Web/CSS/:has) and [`:focus-visible`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-visible).
3. **Input comes before the label** → `input:focus-visible + label { … }` using the [adjacent sibling combinator](https://developer.mozilla.org/en-US/docs/Web/CSS/Adjacent_sibling_combinator).

I use [`:focus-visible`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-visible) instead of `:focus` to avoid noisy rings on mouse clicks. `:has()` is mainstream now (Chrome 105+, Safari 15.4+, Firefox 121+) per the MDN compatibility tables on [`:has()`](https://developer.mozilla.org/en-US/docs/Web/CSS/:has#browser_compatibility).

## Why `label:focus` rarely does what you think

Focus goes to interactive controls (inputs, buttons, links), not labels. You *can* make almost any element focusable, but adding [`tabindex`](https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/tabindex) to labels creates extra Tab stops and hurts flow. If I need the label to reflect focus, I style it based on the input’s state instead. If you need a refresher on proper association, see the [`<label>` element](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/label).

## Pattern 1: Label wraps the input (implicit label)

This is my go-to when I control the markup.

```html
<label class="field">
  <span class="field__label">Email</span>
  <input type="email" required>
</label>
```

```css
/* Highlight the label text when the input inside is focused */
.field:focus-within .field__label { 
  color: var(--accent); 
}

/* Give keyboard users a clear focus only when it matters */
.field input:focus-visible { 
  outline: 2px solid currentColor; 
  outline-offset: 2px; 
}
```

`label:focus-within` lights up when any descendant is focused. See [`:focus-within`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-within).

## Pattern 2: Label comes **before** the input

```html
<label class="field__label" for="search">Search</label>
<input id="search" class="field__input" type="search">
```

```css
/* If the adjacent input is keyboard-focused, style the preceding label */
.field__label:has(+ .field__input:focus-visible) {
  color: var(--accent);
}
```

`:has()` can “look forward” to the next sibling via `+`. Check [`:has()`](https://developer.mozilla.org/en-US/docs/Web/CSS/:has) and the compatibility table there for exact versions. If you can’t rely on `:has()`, wrap both nodes and use [`:focus-within`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-within) on the wrapper.

## Pattern 3: Input comes **before** the label

```html
<input id="agree" class="checkbox__input" type="checkbox">
<label for="agree" class="checkbox__label">I agree</label>
```

```css
/* Adjacent (or use ~ for non-adjacent siblings) */
.checkbox__input:focus-visible + .checkbox__label { 
  text-decoration: underline; 
}
```

This uses the [adjacent sibling combinator](https://developer.mozilla.org/en-US/docs/Web/CSS/Adjacent_sibling_combinator). For non-adjacent siblings, there’s the [general sibling combinator `~`](https://developer.mozilla.org/en-US/docs/Web/CSS/General_sibling_combinator).

## Floating labels I actually ship

```html
<label class="float">
  <input required placeholder=" ">
  <span>Email</span>
</label>
```

```css
.float { position: relative; display: grid; }
.float > input { padding: 1rem .75rem .25rem; }
.float > span {
  position: absolute; left: .75rem; top: .8rem; 
  transition: transform .15s ease, font-size .15s ease, opacity .15s;
}

/* On focus or when the field isn’t empty */
.float:focus-within > span,
.float > input:not(:placeholder-shown) + span {
  transform: translateY(-.6rem);
  font-size: .75rem;
  opacity: .75;
}
```

This relies on [`:focus-within`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-within) and [`:placeholder-shown`](https://developer.mozilla.org/en-US/docs/Web/CSS/:placeholder-shown). No JavaScript.

## My accessibility rules

* I use [`:focus-visible`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-visible), not just `:focus`, so keyboard users get a clear ring without spamming mouse users. This lines up with [WCAG’s “Focus Visible”](https://www.w3.org/WAI/WCAG21/Understanding/focus-visible.html).
* I don’t add [`tabindex="0"`](https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/tabindex) to labels. If I ever need to focus one programmatically for tooling, I use `tabindex="-1"` and keep the actual Tab order clean.
* I always associate labels explicitly (`for` + `id`) or by wrapping the input. Both are valid and assistive-tech friendly per the [`<label>` docs](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/label).

## Browser support cheat sheet

* [`:focus-within`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-within): widely supported.
* [`:focus-visible`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-visible): widely supported and designed for keyboard focus.
* [`:has()`](https://developer.mozilla.org/en-US/docs/Web/CSS/:has#browser_compatibility): Chrome 105+, Safari 15.4+, Firefox 121+.

If you need a feature-query fallback, branch on support with [`@supports`](https://developer.mozilla.org/en-US/docs/Web/CSS/@supports):

```css
/* Modern */
label:has(+ input:focus-visible) { color: var(--accent); }

/* Fallback idea */
@supports not (selector(:has(*))) {
  /* re-order DOM or wrap and use :focus-within */
}
```

## Tailwind CSS versions of these patterns

Tailwind gives you variants for `focus-visible`, `focus-within`, `peer`, `group`, and arbitrary variants that let you target `:has()` or `@supports`. See the Tailwind docs on [state variants](https://tailwindcss.com/docs/hover-focus-and-other-states), [focus-visible](https://tailwindcss.com/docs/hover-focus-and-other-states#focus-visible), [focus-within](https://tailwindcss.com/docs/hover-focus-and-other-states#focus-within), [group](https://tailwindcss.com/docs/hover-focus-and-other-states#group), [peer](https://tailwindcss.com/docs/hover-focus-and-other-states#peer), and [arbitrary variants](https://tailwindcss.com/docs/hover-focus-and-other-states#arbitrary-variants). You can also gate features with the [`supports-[…]` variant](https://tailwindcss.com/docs/hover-focus-and-other-states#supports).

### 1) Label wraps input → `group` + `group-focus-within`

```html
<label class="group block">
  <span class="block group-focus-within:text-accent-600">Email</span>
  <input
    type="email"
    class="mt-1 block w-full
           focus-visible:outline focus-visible:outline-2 focus-visible:outline-current" />
</label>
```

The `group` marks the parent; `group-focus-within:*` styles children when anything inside is focused.

### 2) Label before input → `:has()` with an arbitrary variant

```html
<label class="block has-[+input:focus-visible]:text-accent-600" for="search">Search</label>
<input id="search" class="block w-full focus-visible:outline focus-visible:outline-2" type="search">
```

That `has-[+input:focus-visible]` compiles down to `&:has(+ input:focus-visible)`.

**Optional safety net with a feature query**

If you want to scope the `:has()` styling to supporting browsers using Tailwind:

```html
<label
  class="block
         supports-[selector(:has(*))]:has-[+input:focus-visible]:text-accent-600">
  Search
</label>
<input id="search" class="block w-full focus-visible:outline focus-visible:outline-2" type="search">
```

The `supports-[…]` variant emits an `@supports(...)` wrapper.

### 3) Input before label → `peer` + `peer-focus-visible`

```html
<input id="agree" type="checkbox" class="peer">
<label for="agree" class="peer-focus-visible:underline">I agree</label>
```

`peer` marks the input; the label reacts to its state as a subsequent sibling.

### Floating label with Tailwind (zero JS)

```html
<label class="relative grid group">
  <input
    required placeholder=" "
    class="pt-4 pb-1 px-3 block w-full
           focus-visible:outline focus-visible:outline-2 focus-visible:outline-current" />
  <span
    class="pointer-events-none absolute left-3 top-3
           transition-transform transition-opacity
           group-focus-within:-translate-y-2 group-focus-within:text-xs group-focus-within:opacity-75
           ">
    Email
  </span>
</label>
```

This mirrors the plain-CSS version using `group-focus-within`. If you prefer, you can also drive it with a `has-[input:not(:placeholder-shown)]` arbitrary variant on the wrapper.

## Copy-paste recipes

**Label wraps input**

```css
label:focus-within .label-text { color: var(--accent); }
```

**Label before input**

```css
label:has(+ input:focus-visible) { color: var(--accent); }
```

**Input before label**

```css
input:focus-visible + label { color: var(--accent); }
```

**Highlight a whole row on focus**

```css
.form-row:focus-within { outline: 2px solid var(--accent); outline-offset: 3px; }
```

Tailwind equivalents: `group-focus-within:*`, `has-[+input:focus-visible]:*`, `peer-focus-visible:*`, and `supports-[…]` for feature queries. See [state variants](https://tailwindcss.com/docs/hover-focus-and-other-states).

## FAQ

### Why doesn’t `label:focus` work for me?

Labels aren’t focusable by default and the browser sends focus to the associated control. Style the label based on the input’s focus using [`:focus-within`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-within), [`:has()`](https://developer.mozilla.org/en-US/docs/Web/CSS/:has), or [sibling selectors](https://developer.mozilla.org/en-US/docs/Web/CSS/Adjacent_sibling_combinator).

### Is `:has()` safe to ship now?

Yes for mainstream targets. Check the MDN table for versions on [`:has()`](https://developer.mozilla.org/en-US/docs/Web/CSS/:has#browser_compatibility). If you still support older browsers, add a [`:focus-within`](https://developer.mozilla.org/en-US/docs/Web/CSS/:focus-within) or sibling-selector fallback.

### Should I ever add `tabindex="0"` to a label?

Almost never. It adds a useless stop in the tab order. Keep focus on the control and reflect that state on the label. See [`tabindex`](https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/tabindex).

If you want a few more frontend rabbit holes after this:

- [Tailwind CSS forms plugin: my 2025 guide for v4 and v3](/tailwind-css-forms-plugin)
- [Style an HTML dialog's backdrop with Tailwind CSS](/dialog-backdrop-styling-tailwind-css)
- [5 Tailwind CSS best practices for 2025](/tailwind-css)





