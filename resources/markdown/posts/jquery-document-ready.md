---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/d56e56a4-e848-42b3-a1c3-f7937c213a0b
Title: "How and when to use jQuery's $(document).ready() method"
Description: "In JavaScript, running code at the wrong time can lead to errors or unpredictable behavior. Let me show you the fix using jQuery."
Published at: 2024-02-11
Categories: javascript, jquery
---

## Introduction to jQuery's $(document).ready() method

One of the reasons [jQuery](https://jquery.com) became so popular was how it simplified interacting with the DOM, including waiting for it to be fully loaded before running any JavaScript code. In jQuery, the `$(document).ready()` method is the go-to way to ensure your scripts run only after the HTML document is ready to be manipulated. This is crucial because trying to manipulate DOM elements before the document is fully loaded can lead to errors or unpredictable behavior.

Here's a simple example of how you might use jQuery's `$(document).ready()` method:

```js
$(document).ready(() => {
    // Your code here will run once the DOM is fully loaded.
    console.log("Document is ready!");
});
```

This method waits for the DOM to be ready and ensures that your JavaScript code runs at the right time. It's straightforward, easy to use, and has been a staple in web development projects for years.

## The Modern Vanilla JavaScript way

In modern JavaScript, the `DOMContentLoaded` event serves a similar purpose to jQuery's document ready method. This event fires when the initial HTML document has been completely loaded and parsed, without waiting for stylesheets, images, and subframes to finish loading. Here's how you can use it:

```js
document.addEventListener('DOMContentLoaded', () => {
    // Your code here will run once the DOM is fully loaded.
    console.log('Document is ready with vanilla JavaScript!');
});
```

This approach is native to JavaScript, meaning it doesn't require any libraries to work. It's a clean and efficient way to run your JavaScript code at the right time, ensuring that the DOM elements you want to manipulate are fully loaded.

## Conclusion

Both jQuery's `$(document).ready()` and the vanilla JavaScript `DOMContentLoaded` event offer ways to ensure your JavaScript code runs after the DOM is fully loaded. jQuery provides a simple, cross-browser way to accomplish this, making it a great choice for many projects, especially those already using jQuery for other purposes. On the other hand, the native JavaScript approach with `DOMContentLoaded` is lightweight and doesn't require an external library, making it an attractive option for projects looking to minimize dependencies.

Ultimately, the choice between jQuery and modern vanilla JavaScript depends on the specific needs and constraints of your project. Both methods are valid and useful tools in a web developer's toolkit. Understanding both allows you to make informed decisions about how to best achieve your project's goals.
