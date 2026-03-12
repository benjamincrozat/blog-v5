---
id: "01KKEW27AKW6KBA6NP0VNR8Y8Q"
title: "Get started with jQuery in 5 minutes"
slug: "jquery"
author: "benjamincrozat"
description: "Dive into the basics of jQuery, learn how to include it in your project, and create your first component in just a few minutes."
categories:
  - "javascript"
  - "jquery"
published_at: 2024-02-12T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/zmJfnqellMEWmJY.png"
sponsored_at: null
---
## Introduction to jQuery

[jQuery](https://jquery.com) is a fast, small, and feature-rich JavaScript library. It makes things like HTML document traversal and manipulation, event handling, and animation much simpler with an easy-to-use API that works across a multitude of browsers (especially the old ones).

Nowadays, we might think jQuery has been long dead, but it's not. It's still the dominant JavaScript library and the big majority of web developers are using it.

Therefore, why wouldn't I write articles about it?

## Include jQuery in your HTML using the official CDN

To start using jQuery in your web projects, you first need to include it in your HTML. The easiest way to do this is by using the official Content Delivery Network (CDN). Simply add the following script tag in the `<head>` section of your HTML document:

```html
<!DOCTYPE html>
<html>
  <head>
      …

      <!-- In your development environment, use this version to ease debugging. -->
      <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
      <!-- In a production environment, use the minified version for optimal performances. -->
      <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  </head>
  <body>
      …
  </body>
</html>
```

If you are worried about using an outdated version of jQuery, the people behind it don't recommend using a URL that always points to the latest version of the library for [various reasons](https://blog.jquery.com/2014/07/03/dont-use-jquery-latest-js/), but mainly for stability (you don't want your code to break because it doesn't work with the newest major version for instance).

## Use the slim version of jQuery

Did you know there's a slim version of jQuery? It excludes the Ajax and animation effects parts, which are not necessary in a world where the [native fetch JavaScript API](https://developer.mozilla.org/en-US/docs/Web/API/Fetch_API/Using_Fetch) is widely supported, as well as [CSS transitions](https://developer.mozilla.org/en-US/docs/Web/CSS/transition) and [animations](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_animations/Using_CSS_animations). To use the slim version of jQuery, add `.slim` after the version number:

```html
<!-- In your development environment, use this version to ease debugging. -->
<script src="https://code.jquery.com/jquery-3.7.1.slim.js"></script>
<!-- In a production environment, use the minified version for optimal performances. -->
<script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>
```

## Create your first jQuery component

Now that you've included jQuery, let's create a simple component: a button that hides itself when clicked. Add the following HTML and jQuery script to your document:

```html
<button id="hide-button">Hide me!</button>
```

Then, in your JavaScript:

```javascript
// Run the code when the document is ready to
// avoid errors and unpredictable behavior.
$(document).ready(function () {
    // Listen for clicks on the button.
    $('#hide-button').click(function () {
        // Hide the button, there contained in the "this" variable.
        $(this).hide();
    });
});
```

As you saw, this code uses jQuery to attach a click event to the button with the ID `hide-button`. When the button is clicked, jQuery's `hide()` method is called on the element, making it disappear from the page (behind the scenes, it's simply adding the `display: none` value to the `style` attribute).

## What does "$" mean in jQuery?

The dollar sign ($) that you have to use in jQuery is simply a JavaScript variable with a funky name that makes writing code faster. You could instead use the `jQuery` variable.

## Conclusion

That's it! You've now seen how jQuery can make JavaScript programming easier and more intuitive, especially for tasks like manipulating HTML elements and handling events. Start experimenting with more components and explore the vast possibilities jQuery offers.

If you are learning jQuery as a small practical toolkit instead of a whole philosophy, these are the next reads I would open:

- [Know when $(document).ready() still matters](/jquery-document-ready)
- [Handle jQuery click events without clunky inline handlers](/jquery-on-click)
- [Loop through jQuery collections without guesswork](/jquery-each)
- [See when Alpine.js is enough for the interactivity you need](/alpine-js)
