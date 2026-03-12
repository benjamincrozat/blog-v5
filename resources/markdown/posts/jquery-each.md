---
id: "01KKEW27AQ3PGNQCTJ0EQ4D9W2"
title: "Understanding jQuery's .each() method"
slug: "jquery-each"
author: "benjamincrozat"
description: "Learn how to use jQuery's `.each()` method to iterate over DOM elements and arrays, and discover a modern vanilla JavaScript alternative."
categories:
  - "javascript"
  - "jquery"
published_at: 2024-02-11T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/eykiYuOt9yy50z4.jpg"
sponsored_at: null
---
## Introduction to jQuery's .each() method

Ah, [jQuery](https://jquery.com). It's been a cornerstone of web development for years, offering a simplified way to manipulate the DOM, handle events, and perform AJAX requests. One of its most beloved features? The [`.each()`](https://api.jquery.com/each/) method. This little gem allows developers to iterate over both arrays and DOM elements effortlessly, applying functions to each item in the set.

## Syntax and usage

jQuery's `each` method has a straightforward syntax. It takes a function as an argument, which is executed for each item in the set. Here's a quick look:

```js
$('selector').each(function(index, element) {
    // Your code goes here
});
```

In this snippet, `selector` targets the DOM elements you want to iterate over. The function then receives two arguments: `index`, the position of the current item in the set, and `element`, the item itself.

## Practical example

Let's say we want a Frequently Asked Questions section with only one question open at a time:

```js
$('summary').click(() => {
    var parent = $(this).parent('details')
  
    $('details').each(() => {
        if (! $(this).is(parent)) {
            $(this).removeAttr('open')
        }
    })
})
```

Not that hard, right? The `.each()` method comes in handy to find all the details elements and close them, excluding the one we clicked in.

But what if I told you that you could achieve the same thing without jQuery, using just vanilla JavaScript?

## The equivalent in Vanilla JavaScript

As web development evolves, so does JavaScript. The modern ECMAScript standards have introduced methods that make DOM manipulation just as straightforward as jQuery once did. For instance, to replicate jQuery's `each` method example, you can use `forEach` on a [`NodeList`](https://developer.mozilla.org/en-US/docs/Web/API/NodeList).

Here's our practical example from above, but using Vanilla JavaScript:

```js
document.querySelectorAll('summary').forEach(summary => {
    summary.addEventListener('click', () => {
        var parent = this.parentNode

        document.querySelectorAll('details').forEach(details => {
            if (details !== parent) {
                details.removeAttribute('open')
            }
        })
    })
})
```

Here, `querySelectorAll` returns a `NodeList` of all `<details>` tags, which we then iterate over with `forEach`, removing the `open` attribute from each `<details>` elements besides the one we clicked on.

## Conclusion

While jQuery's `.each()` method offers a simple and effective way to iterate over elements, modern JavaScript provides equally powerful alternatives. As developers, staying updated with these advancements allows us to write cleaner, more efficient code. Whether you're a jQuery enthusiast or a vanilla JavaScript advocate, understanding both approaches enhances your toolkit for web development challenges.

To build on "Understanding jQuery's .each() method", start with these related reads:

- [How and when to use jQuery's $(document).ready() method](/jquery-document-ready)
- [Handle clicks from your users using jQuery](/jquery-on-click)
- [Get started with jQuery in 5 minutes](/jquery)
- [Alpine.js: a lightweight framework for productive developers](/alpine-js)
- [Bun package manager vs npm, Yarn, and pnpm in 2025](/bun-package-manager)
- [Add Vue.js to any Laravel project](/laravel-vue)
- [25 Laravel best practices, tips, and tricks](/laravel-best-practices)

