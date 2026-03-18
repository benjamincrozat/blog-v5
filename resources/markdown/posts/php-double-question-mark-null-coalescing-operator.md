---
id: "01KKEW27KDH2PAGXTRASWW3D5N"
title: "PHP double question mark (??) explained"
slug: "php-double-question-mark-null-coalescing-operator"
author: "benjamincrozat"
description: "Learn what the PHP double question mark operator (`??`) does, how it differs from `?:`, and when to use `??=`."
categories:
  - "php"
published_at: 2023-09-03T00:00:00+02:00
modified_at: 2026-03-13T12:25:00Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/rct9UVekTs7Wv08.jpg"
sponsored_at: null
---
## Introduction

**The PHP double question mark operator, `??`, is the null coalescing operator.** It lets you use a fallback value when something is unset or `null`.

PHP added `??` in PHP 7.0 and `??=` in PHP 7.4. Here is what they do, how they differ from `?:`, and when each one is the better choice.

## The null coalescing operator (??), or the double question mark

The [null coalescing operator](https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.null-coalesce-op), or double question mark, was introduced in PHP 7.0 and helps you write cleaner, more readable code. It's represented by two question marks `??`.

Let's say you want to get a value from a variable, but if that variable is not set or is `null`, you want to use a default value instead. You can use the `??` operator to do this in one step.

For example:

```php
$name = $_GET['name'] ?? 'Unknown';
```

This line of code will set `$name` to `$_GET['name']` if it is set and not null. Otherwise, it will set `$name` to "Unknown". Because `??` uses isset semantics, it does not trigger notices for undefined variables or array keys.

You can also chain them together like this:

```php
$foo = $foo ?? $bar ?? 'baz';
```

This will check `$foo` first, then `$bar`, and use "baz" if neither is set and not null. Note that `??` preserves values like `0`, `false`, and `''`. For example, `0 ?? 1` returns `0`, while `0 ?: 1` returns `1`.

- Quick contrast: the shorthand ternary (Elvis) operator `?:` checks truthiness, while `??` checks only for null or unset and avoids notices for undefined keys.

## The null coalescing assignment operator (??=), or the double question mark equals

PHP 7.4 introduced a new shortcut, `??=` (double question mark equals), also called the [null coalescing assignment operator](https://wiki.php.net/rfc/null_coalesce_equal_operator). It sets a variable to a value only if it is not set or is null.

Here is a simple example from a utility function:

```php
function do_something(DateTime $from, ?DateTime $to = null)
{
    // Using the ternary operator.
    $to = $to ? $to : new DateTime();

    // Using the Elvis operator.
    $to = $to ?: new DateTime();

    // Using the null coalescing assignment operator.
    $to ??= new DateTime();

    // Do something.
}
```

This sets `$to` to a new `DateTime` instance only if it is not already set (or is `null`). For forward compatibility with PHP 8.4's deprecation of implicitly nullable parameters, make the parameter explicitly nullable as shown with `?DateTime $to = null` ([RFC](https://wiki.php.net/rfc/deprecate-implicitly-nullable-types)). Also, `new DateTime()` is the same as `new DateTime('now')`, so the no-argument form is shorter ([manual](https://www.php.net/manual/en/datetime.construct.php)). If you prefer immutability, you can use `DateTimeImmutable` instead.

## Key differences: ?? vs ?:

- `?:` (the Elvis operator) checks truthiness and can override `0`, `''`, or `false`.
- `??` checks only for `null` or unset and avoids notices.

## Common pitfalls and operator precedence

- `??` has low precedence. When mixing it with concatenation, arithmetic, logical operators, or the ternary operator, add parentheses. Example pattern: `$greeting = 'Hi ' . ($name ?? 'friend');`. See the operator precedence list for details ([reference](https://www.docs4dev.com/docs/php/latest/language.operators.comparison.html)).
- With arrays or user input, prefer `??` to avoid an undefined index notice. This is a clean alternative to `isset(...)` checks (think "isset vs null coalescing"). If you are on PHP 8.0+, also look at the [nullsafe operator](https://www.php.net/manual/en/migration80.new-features.php) for safe property access.

## FAQ

### Does `??` trigger notices for undefined keys?

No. It uses isset semantics, so `$_GET['name'] ?? 'Unknown'` will not raise a notice even if `'name'` is missing.

### What is the difference between `??` and `?:` in PHP?

`??` checks only for null or unset. `?:` checks truthiness and may treat `0`, `''`, or `false` as false and use the fallback.

## Conclusion

Use the PHP null coalescing operator (??) to read values with safe defaults, and use the null coalescing assignment (??=) to set a value only when it is missing. Keep in mind that `??` differs from `?:` by checking for null or unset, not truthiness, and that parentheses help when you mix operators. I reach for `??=` when I want to provide a default in place and keep the code simple.

If you are tidying the everyday operators that make PHP code easier to read, these are the next reads I would keep open:

- [Stop foreach from blowing up on the wrong input](/invalid-argument-supplied-for-foreach)
- [See what PHP 8.5 changes before you upgrade](/php-85)
- [Count arrays correctly before you branch on them](/php-array-length)
