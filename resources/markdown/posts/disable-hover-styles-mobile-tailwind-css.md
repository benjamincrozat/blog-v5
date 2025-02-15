---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/5da17645-04a7-499a-bce2-d4d93677f43e
Title: "Disable hover styles on mobile with Tailwind CSS"
Description: "Learn how to disable hover styles on mobile devices with Tailwind CSS."
Published at: 2023-12-17
Categories: css, tailwind-css
---

## Introduction to the sticky hover problem on mobile

A common issue encountered is the "sticky hover" problem on mobile devices. This occurs when elements styled with hover effects in CSS retain these styles after being tapped, until the user interacts with another element.

This behavior, which differs from how hover effects traditionally work on desktops with a mouse, can lead to a less intuitive user experience.

## How Tailwind CSS fixes this problem

Tailwind CSS addresses this issue by modifying how hover styles are applied. The solution involves using CSS media queries to ensure hover styles are only activated on devices that support hover, like those with a mouse. 

This is achieved through a simple yet effective update in the CSS:

**The old approach:**

```css
.hover\:underline:hover {
  text-decoration-line: underline;
}
```

**The new approach:**

```css
@media (hover: hover) and (pointer: fine) {
  .hover\:underline:hover {
    text-decoration-line: underline;
  }
}
```

This change ensures that hover styles are only active on devices that can accurately detect hover events, effectively solving the sticky hover problem on mobile devices.

## How to enable hoverOnlyWhenSupported in Tailwind CSS

To use this new approach in your Tailwind CSS projects, you need to enable the `hoverOnlyWhenSupported` flag in your Tailwind configuration. This is done in the `tailwind.config.js` file as follows:

```javascript
module.exports = {
  future: {
    hoverOnlyWhenSupported: true
  }
}
```

By setting this flag, you opt into the new behavior, which will become the default in the upcoming Tailwind CSS v4.

It's important to note that this is a breaking change and may affect existing styles, particularly for mobile devices.
