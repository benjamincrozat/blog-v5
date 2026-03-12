---
id: "01KKEW27ATBZ9X179F022FPND2"
title: "Handle clicks from your users using jQuery"
slug: "jquery-on-click"
author: "benjamincrozat"
description: "Dive into the simplicity of handling click events with jQuery and learn how to achieve the same results using vanilla JavaScript."
categories:
  - "javascript"
  - "jquery"
published_at: 2024-02-13T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/DZmsk89Q3KiDJUR.jpg"
sponsored_at: null
---
## Introduction to click events in jQuery

Click events are a staple in web development. They're unavoidable and [jQuery](https://jquery.com) offers a straightforward way to handle them. And I will also show you how to do it using Vanilla JavaScript (which just means JavaScript without any dependency).

## The .click() method in jQuery

Using jQuery to handle click events is both simple and intuitive. The [`.click()`](https://api.jquery.com/click/) method offers a quick way to attach an event listener to DOM elements, responding to user interactions seamlessly. 

Example:

```html
<button>Click me!</button>
```

```javascript
$('button').click(function () {
  alert('Button clicked!')
})
```

This code snippet demonstrates how to display an alert when a button is clicked. Couldn't be simpler!

## Click events in Vanilla JavaScript

Vanilla JavaScript obviously allows you to do the same thing, just in a little bit more verbose way.

Example:

```javascript
document.querySelector('button').addEventListener('click', function () {
  alert('Button clicked!');
});
```

People can criticize jQuery all day long, but its syntax is unbeatable. Look how lengthy this code is!

If you are still thinking about "Handle clicks from your users using jQuery", open these next:

- [Understanding jQuery's .each() method](/jquery-each)
- [Get started with jQuery in 5 minutes](/jquery)
- [How and when to use jQuery's $(document).ready() method](/jquery-document-ready)
- [Alpine.js: a lightweight framework for productive developers](/alpine-js)
- [Bun package manager vs npm, Yarn, and pnpm in 2025](/bun-package-manager)
- [Add Vue.js to any Laravel project](/laravel-vue)
- [25 Laravel best practices, tips, and tricks](/laravel-best-practices)

