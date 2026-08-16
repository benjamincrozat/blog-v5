---
id: "01KKEW2789SQMNXB0MDFZ83Y88"
title: "Is CSS case-sensitive?"
slug: "css-property-names-values-case-sensitive"
author: "benjamincrozat"
description: "CSS property names and standard keywords usually ignore case, but class and ID values, custom properties, and many identifiers are case-sensitive."
categories:
  - "css"
published_at: 2023-08-29T00:00:00+02:00
modified_at: 2026-08-16T16:52:55Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/35EQFTHC3WhmcbE.png"
sponsored_at: null
---
**CSS is not simply case-sensitive or case-insensitive.** Standard property names and CSS-defined keywords are usually ASCII case-insensitive. Case still matters for HTML class and ID values, custom property names, and many identifiers you define yourself.

## The quick answer

| CSS or HTML part | Does case matter? | Example |
| --- | --- | --- |
| Standard CSS property names | No | `COLOR` and `color` are the same property |
| CSS-defined keyword values | Usually no | `RED` and `red` are the same color |
| Class and ID values in normal HTML | Yes | `.card` does not match `class="Card"` |
| Custom property names | Yes | `--brand` and `--Brand` are different properties |
| Many custom identifiers | Yes | Treat names you create as exact unless their property says otherwise |
| HTML element and attribute names | No | `DIV` and `div` refer to the same HTML element |
| XML and XHTML names | Yes | Their element and attribute names are case-sensitive |

The [CSS Syntax specification](https://www.w3.org/TR/css-syntax-3/#intro) defines CSS keywords and property names as ASCII case-insensitive unless another specification says otherwise. Custom properties are the important exception: the [CSS Custom Properties specification](https://www.w3.org/TR/css-variables-1/#defining-variables) says their names are case-sensitive.

## A copy-paste example

```html
<div id="Panel" class="Card" data-state="Ready">
    Case matters in some places.
</div>

<style>
    DIV {
        COLOR: RED;
    }

    .card {
        font-weight: bold;
    }

    #panel {
        text-decoration: underline;
    }

    [data-state="ready" i] {
        outline: 2px solid currentColor;
    }

    :root {
        --Brand: royalblue;
        --brand: darkorange;
    }

    div {
        border: 2px solid var(--Brand);
    }
</style>
```

In a normal HTML document, `DIV`, `COLOR`, and `RED` work despite the uppercase letters. The `.card` and `#panel` rules do not match `Card` and `Panel`. The attribute selector does match because its `i` flag requests an ASCII case-insensitive comparison. The two custom properties are separate names, so `var(--Brand)` uses `royalblue`.

MDN's [attribute selector reference](https://developer.mozilla.org/en-US/docs/Web/CSS/Reference/Selectors/Attribute_selectors) covers the `i` flag and the case-sensitive values of attributes such as `class`, `id`, and `data-*`.

## Where the common shortcut fails

Saying “CSS values are case-insensitive except for fonts and URLs” is too broad. A value can contain a CSS-defined keyword, a custom identifier, an HTML attribute value, or a URL handled by a server. Those parts follow different rules.

For URLs, CSS does not decide whether `/Logo.svg` and `/logo.svg` point to the same file. The server and file system do. For identifiers you create, keep the spelling exact unless the relevant property explicitly defines another rule.

## Should you write CSS in lowercase?

Yes. Lowercase property names and keywords are easier to scan and match common tooling. Just remember that lowercase is a style convention, not what makes standard CSS properties valid.

If you are cleaning up the small CSS rules that save you from silly bugs later, these are solid next reads:

- [Tighten your Tailwind habits before the CSS gets messy](/tailwind-css)
- [Make labels react cleanly when fields get focus](/label-focus-css)
- [Style dialog backdrops cleanly with Tailwind utilities](/dialog-backdrop-styling-tailwind-css)
