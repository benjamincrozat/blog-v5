---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/51/css_ifo3ux.png
Title: "Is CSS case-sensitive?"
Description: "CSS, including selectors and property names, is case-sensitive; use lowercase for consistency and to avoid issues."
Published at: 2023-08-29
Categories: css
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
