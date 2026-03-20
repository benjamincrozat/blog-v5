---
id: "01KKEW27ANMYBTWJVHQ6HD7YA2"
title: "jQuery document ready: how and when to use it"
slug: "jquery-document-ready"
author: "benjamincrozat"
description: "Use jQuery document ready to run code after the DOM is parsed, then see when DOMContentLoaded or defer is the better modern choice."
categories:
  - "javascript"
  - "jquery"
published_at: 2024-02-10T23:00:00Z
modified_at: 2026-03-20T09:45:55Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/1YYGNDJtfZm8onM.jpg"
sponsored_at: null
---
## Introduction

Use jQuery document ready when you are maintaining an existing jQuery codebase and need code to run after the HTML has been parsed and the DOM is safe to query.

If you are writing new JavaScript, `DOMContentLoaded` or a deferred script is usually the better choice.

```js
$(function () {
    console.log('DOM is ready');
});
```

That shorthand is the recommended jQuery form. `$(document).ready(...)` still works, but the shorthand is cleaner. If you are writing new vanilla JavaScript, `DOMContentLoaded` or a `defer` script is usually the better choice.

## Quick answer

### Modern vanilla JavaScript version

```js
document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.menu-toggle')?.addEventListener('click', () => {
        document.querySelector('.menu')?.classList.toggle('is-open');
    });
});
```

If your script already uses `defer`, you often do not need either wrapper:

```html
<script src="/app.js" defer></script>
```

### jQuery version

```js
$(function () {
    $('.menu-toggle').on('click', function () {
        $('.menu').toggleClass('is-open');
    });
});
```

## What jQuery document ready actually does

jQuery runs your callback once the DOM is ready to be manipulated. It does **not** wait for images, fonts, or iframes to finish loading.

That makes it good for code like:

- binding event listeners
- reading or changing DOM elements
- initializing jQuery plugins
- toggling classes or attributes

## `$(function () {})` vs `$(document).ready()`

These do the same thing:

```js
$(function () {
    console.log('Ready');
});
```

```js
$(document).ready(function () {
    console.log('Ready');
});
```

The shorthand is cleaner, and it is the version I would use today in jQuery code.

If you see `$(document).on("ready", handler)`, treat it as legacy code. jQuery deprecated that form in 1.8 and removed it in 3.0.

## jQuery ready vs `window.load`

This is another common source of confusion:

- document ready: runs when the DOM is parsed
- `window.load`: runs later, after images and other assets finish loading

If you only need to attach handlers or update markup, document ready is the better fit.

```js
$(window).on('load', function () {
    console.log('Everything, including images, is loaded');
});
```

## The modern vanilla JavaScript alternative

If you do not need jQuery, use `DOMContentLoaded`:

```js
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM is ready');
});
```

For many modern setups, the best answer is even simpler: load your script with `defer` and let it run after the document is parsed.

```html
<script src="/app.js" defer></script>
```

With `defer`, code at the top level of `app.js` can usually access the DOM directly.

## When you still need jQuery document ready

It still makes sense when:

- you are maintaining an older jQuery codebase
- a theme or plugin expects jQuery
- scripts are injected in a way that does not use `defer`
- you want the familiar jQuery-ready behavior across old code

If the project is already jQuery-heavy, keeping one clear ready wrapper is usually better than mixing patterns randomly.

## Common mistakes

### Using the deprecated `ready` event form

Do not use this old pattern:

```js
$(document).on('ready', function () {
    console.log('Ready');
});
```

It was deprecated and should not be your default.

### Wrapping everything when the script is already deferred

If your script tag uses `defer`, a second ready wrapper can be unnecessary noise.

### Confusing DOM ready with full page load

If you need image dimensions before they load, document ready is too early. Use `load` instead.

## FAQ

### Is `$(document).ready()` deprecated?

The better jQuery form today is the shorthand `$(function () {})`, but `$(document).ready()` still works in jQuery projects.

### What is the JavaScript equivalent of jQuery document ready?

`DOMContentLoaded`.

### Do I need jQuery document ready if my script is at the end of `body`?

Usually no. If the script runs after the elements exist, you can often use plain code directly.

### Do I need it with `defer`?

Usually no. Deferred scripts run after the document is parsed.

## Conclusion

jQuery document ready is still useful when you are working inside an existing jQuery codebase, especially for event binding and plugin initialization. But if you are writing new JavaScript, `DOMContentLoaded` or a deferred script is usually the simpler and more modern answer.

If you are still working through the small set of jQuery patterns that matter most, these are the next reads I would keep open:

- [Loop through jQuery collections without guesswork](/jquery-each)
- [Handle jQuery click events without clunky inline handlers](/jquery-on-click)
- [Get the quickest possible start with jQuery](/jquery)
- [See when Alpine.js is enough for the interactivity you need](/alpine-js)
