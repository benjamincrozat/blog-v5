---
id: "01KKEW2789SQMNXB0MDFZ83Y88"
title: "Is CSS case-sensitive?"
slug: "css-property-names-values-case-sensitive"
author: "benjamincrozat"
description: "CSS, including selectors and property names, is case-sensitive; use lowercase for consistency and to avoid issues."
categories:
  - "css"
published_at: 2023-08-29T00:00:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/35EQFTHC3WhmcbE.png"
sponsored_at: null
---
## Introduction

**CSS itself is not case-sensitive, but it can be if you are using an XHTML document type.**

Let's dive deeper and learn about all the nuances.

## The case-sensitiveness of CSS in detail

- **Selectors**: The HTML elements, classes, and IDs that you reference in your CSS file are case-sensitive based on the document type.  
For example, in HTML, the element names written in the HTML are case-insensitive, meaning `<div>` and `<DiV>` are considered the same. However, in XHTML (an XML-based version of HTML), case matters, so `<div>` and `<DiV>` would be considered different elements.
Similarly, class and ID selectors are case-sensitive in all document types. So, if you have an element with `id="example"`, you must reference it in your CSS file as `#example` and not `#Example`.
- **Properties**: Property names defined by the W3C standards are case-insensitive. So, `background-color` and `BackGround-COLOR` are considered the same. That being said, it is recommended to use lowercase for property names to maintain readability and consistency.
- **Property Values**: In general, property values are case-insensitive, except for font names and URLs. So, `red` and `RED` are considered the same color. **However, the values of the `font-family`, `url`, and `attr` are case-sensitive.**

## Conclusion
	
In summary, it's best practice to always use lowercase for HTML elements, attributes, and CSS properties and values (except for font names and URLs) to ensure consistency and avoid any potential issues.

If you are cleaning up the small CSS rules that save you from silly bugs later, these are solid next reads:

- [Tighten your Tailwind habits before the CSS gets messy](/tailwind-css)
- [Make labels react cleanly when fields get focus](/label-focus-css)
- [Style dialog backdrops cleanly with Tailwind utilities](/dialog-backdrop-styling-tailwind-css)
- [Use lh and rlh when spacing should follow your text](/css-lh-rlh)
- [Make long-form content look better with Tailwind's typography plugin](/tailwind-css-typography-plugin)
