---
id: "01KKEW27AQ3PGNQCTJ0EQ4D9W2"
title: "jQuery .each(): syntax, examples, and gotchas"
slug: "jquery-each"
author: "benjamincrozat"
description: "Learn how jQuery's `.each()` method works, when to use it, and how it compares with native `forEach()`."
categories:
  - "javascript"
  - "jquery"
published_at: 2024-02-11T00:00:00+01:00
modified_at: 2026-03-20T12:45:00Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/eykiYuOt9yy50z4.jpg"
sponsored_at: null
---
## Introduction to jQuery's `.each()` method

`jQuery.each()` is for looping over a jQuery collection or an array-like list when you want access to both the index and the current item. If you only need a plain array loop, native `forEach()` is usually the simpler option.

## Syntax and usage

The method takes a function and runs it for each item in the set:

```js
$('selector').each(function (index, element) {
    // Your code goes here
});
```

In this snippet, `selector` targets the DOM elements you want to iterate over. The callback receives two arguments: `index`, the position of the current item in the set, and `element`, the item itself. If you need to break out early, `return false` stops the loop.

## Practical example

Let's say we want a Frequently Asked Questions section with only one question open at a time:

```js
$('summary').on('click', function () {
    const parent = $(this).parent('details')

    $('details').each(function () {
        if (! $(this).is(parent)) {
            $(this).removeAttr('open')
        }
    })
})
```

Not that hard, right? The `.each()` method comes in handy to find all the `details` elements and close them, excluding the one we clicked.

But you can achieve the same thing without jQuery, using just vanilla JavaScript.

## The equivalent in Vanilla JavaScript

As web development evolves, so does JavaScript. The modern ECMAScript standards have introduced methods that make DOM manipulation just as straightforward as jQuery once did. For instance, to replicate jQuery's `each` method example, you can use `forEach` on a [`NodeList`](https://developer.mozilla.org/en-US/docs/Web/API/NodeList).

Here's our practical example from above, but using Vanilla JavaScript:

```js
document.querySelectorAll('summary').forEach(summary => {
    summary.addEventListener('click', function () {
        const parent = this.parentNode

        document.querySelectorAll('details').forEach(details => {
            if (details !== parent) {
                details.removeAttribute('open')
            }
        })
    })
})
```

Here, `querySelectorAll` returns a `NodeList` of all `<summary>` tags, which we then iterate over with `forEach`, removing the `open` attribute from each `<details>` element besides the one we clicked on.

## Conclusion

While jQuery's `.each()` method is still useful, modern JavaScript often gives you the same result with less ceremony. Use jQuery when you are already inside a jQuery collection; use `forEach()` when you are working with native DOM APIs.

If you are still working through the core jQuery patterns instead of treating `.each()` in isolation, these are the next reads I would keep open:

- [Know when $(document).ready() still matters](/jquery-document-ready)
- [Handle jQuery click events without clunky inline handlers](/jquery-on-click)
- [Get the quickest possible start with jQuery](/jquery)
- [See when Alpine.js is enough for the interactivity you need](/alpine-js)
