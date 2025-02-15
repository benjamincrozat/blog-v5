---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/62/confused_xxboi4.jpg
Title: "The easiest way to get the current URL path in PHP"
Description: "Discover how to fetch the current URL path in PHP thanks to an useful superglobal variable."
Published at: 2023-09-03
Modified at: 2023-10-01
Categories: php
---

## Get the path from the current URL

**You can get the current URL path in PHP using the `$_SERVER` superglobal. Here is a straightforward way to do it:**

```php
<?php

echo $_SERVER['REQUEST_URI'];
```

The `REQUEST_URI` key will give you the current URL path along with the query string (if any).

For example, if the current URL is https://www.example.com/foo?bar=baz, the above code will output `/foo?bar=baz`.

## $_SERVER contains everything about the URL's path

Now that you have the solution, let's break it down:

- `$_SERVER` is a superglobal exposed by PHP, which means it is available in all scopes throughout a script. It contains information about headers, paths, and script locations. And [it's not the only superglobal you can use](https://www.php.net/manual/en/language.variables.superglobals.php).
- `['REQUEST_URI']` is one of the elements of the `$_SERVER` superglobal that contains the URI (Uniform Resource Identifier). It includes both the path and the query string.

Use `var_dump()` on `$_SERVER` and see for yourself all the valuable information it contains.

## Use cases for the URL's path

- **Generating breadcrumbs**: Breadcrumbs are a secondary navigation aid that helps users understand their location in an application. PHP lets you use the current URL path to generate dynamic breadcrumbs.
- **Highlighting the current page in a navigation menu**: You can use the current URL path to compare with the menu items and highlight the active one dynamically.
- **Redirection**: You may want to redirect users to the same page after they perform an action (e.g., submit a form).
