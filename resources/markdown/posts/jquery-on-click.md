---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/e4ed420c-e4f6-4ce9-b05a-994b89d39431
Title: "Handle clicks from your users using jQuery"
Description: "Dive into the simplicity of handling click events with jQuery and learn how to achieve the same results using vanilla JavaScript."
Published at: 2024-02-13
Categories: javascript, jquery
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
