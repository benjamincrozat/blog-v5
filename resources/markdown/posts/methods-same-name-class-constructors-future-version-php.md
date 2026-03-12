---
id: "01KKEW27H9T4PT61FXRZAY343A"
title: "Methods with the same name as their class will not be constructors in a future version of PHP"
slug: "methods-same-name-class-constructors-future-version-php"
author: "benjamincrozat"
description: "Learn why and how to fix \"Methods with the same name as their class will not be constructors in a future version of PHP\" warnings."
categories:
  - "php"
published_at: 2022-10-08T00:00:00+02:00
modified_at: 2022-11-23T00:00:00+01:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/GKo1V0cxCdQj6aE.jpg"
sponsored_at: null
---
**This warning message occurs because class constructors can't have the same name as their class. You can fix this by changing it to `__construct()`**.

1. Grab your favorite code editor and **search for class definitions across your project**;
2. Check for constructor methods with the **same name as the class and change it to `__construct`**.

Your modifications should look like this:

```diff
class Foo
{
-    public function Foo()
+    public function __construct()
    {
    }
}
```

That's it, it’s as simple as that.

But did you know the story behind this change?

In PHP 4, as you know, a constructor was declared with the same name as its class. It was still working in PHP 5, **was deprecated in PHP 7.0**, and **removed in PHP 8.0**. That is why you must rename your constructors before migrating to version 8 or greater.

For posterity, you can read more about it on the official PHP documentation: [PHP deprecated features in version 7.0.x](https://www.php.net/manual/en/migration70.deprecated.php#migration70.deprecated.php4-constructors)

You can also see the PHP RFC that led to this: [PHP RFC: Remove PHP 4 Constructors](https://wiki.php.net/rfc/remove_php4_constructors)

If you are cleaning up old PHP before the next upgrade forces the issue, these are the follow-up reads I would keep nearby:

- [PHP 8.6: release date and confirmed features](/php-86)
- [PHP 9 release date and what to fix now](/php-90)
- [PHP 8.3's Override attribute in a nutshell](/php-83-override-attribute)
- [6 ways to check your version of PHP](/check-php-version)
- [Fix "using $this when not in object context" now](/using-this-when-not-in-object-context)
