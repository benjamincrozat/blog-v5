---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/11/guy-coding-3_lpz0qy.jpg
Title: "Methods with the same name as their class will not be constructors in a future version of PHP"
Description: "Learn why and how to fix \"Methods with the same name as their class will not be constructors in a future version of PHP\" warnings."
Published at: 2022-10-08
Modified at: 2022-11-23
Categories: php
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

