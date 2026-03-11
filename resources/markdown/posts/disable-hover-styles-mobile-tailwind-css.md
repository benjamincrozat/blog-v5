---
id: "01KKEW278XGNSW080EP87A57FF"
title: "Disable hover styles on mobile with Tailwind CSS"
slug: "disable-hover-styles-mobile-tailwind-css"
author: "benjamincrozat"
description: "Learn how to disable hover styles on mobile devices with Tailwind CSS."
categories:
  - "css"
  - "tailwind-css"
published_at: 2023-12-17T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/nvzZwQMRZfyrU30.jpg"
sponsored_at: null
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