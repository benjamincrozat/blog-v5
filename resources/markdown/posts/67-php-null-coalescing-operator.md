---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/61/crazy-monitors-guy_ru8pgz.jpg
Title: "PHP's double question mark, or the null coalescing operator"
Description: "Discover how to simplify your PHP code with the null coalescing and null coalescing assignment operators."
Published at: 2023-09-03
Modified at: 2023-09-19
Categories: php
---

## The null coalescing operator (??), or the double question mark

The [null coalescing operator](https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.null-coalesce-op), or double question mark, was introduced in PHP 7.0 and is a handy shortcut that helps you write cleaner and more readable code. It's represented by two question marks `??`.

Let's say you want to get a value from a variable, but if that variable is not set or is `null`, you want to use a default value instead. You can use the `??` operator to do this in one step.

For example:

```php
$name = $_GET['name'] ?? 'Unknown';
```

This line of code will set `$name` to `$_GET['name']` if it's set and not null. Otherwise, it will set `$name` to "Unknown".

You can also chain them together like this:

```php
$foo = $foo ?? $bar ?? 'baz';
```

This will check `$foo` first, then `$bar`, and use "baz" if neither are set and not null.

## The null coalescing assignment operator (??=), or the double question mark equals

PHP 7.4 introduced a new shortcut, `??=` (double question mark equals), also called the [null coalescing assignment operator](https://wiki.php.net/rfc/null_coalesce_equal_operator). This is used when you want to set a variable to a new value only if it's currently not set or null.

It's hard to make up a good example, but here's a simplified one from this very blog's codebase:

```php
function do_something(DateTime $from, DateTime $to = null)
{
    // Using the ternary operator.
    $to = $to ? $to : new DateTime('now');

    // Using the Elvis operator.
    $to = $to ?: new DateTime('now');

    // Using the null coalescing assignment operator.
    $to ??= new DateTime('now');

    // Do something.
}
```

This will set `$to` to a new `DateTime` instance only if it's not already set (or `null` in that case).
