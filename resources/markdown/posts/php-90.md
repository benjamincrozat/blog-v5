---
id: "01KKEW27JRB4E9XDVB5DNAZXM6"
title: "PHP 9 release date and what to fix now"
slug: "php-90"
author: "benjamincrozat"
description: "PHP 9 has no release date yet. See the breaking changes already targeting it and what to fix now before PHP 9 lands."
categories:
  - "php"
published_at: 2023-11-03T00:00:00+01:00
modified_at: 2026-03-12T11:39:11+01:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/uZ3tbALpiLH7S8H.png"
sponsored_at: null
---
## Introduction

PHP 9 has no release date yet, and PHP 8.6 is next.

There is still no official release date for PHP 9. What we do have is better than guesswork: the [PHP RFC index](https://wiki.php.net/rfc), the public [PHP 9 milestone in php-src](https://github.com/php/php-src/milestone/6), the [PHP 8.5 release page](https://www.php.net/releases/8.5/en.php), and the [PHP 8.6 preparation tasks list](https://wiki.php.net/todo/php86).

Here is the short version: PHP 9 is still ahead of us, several breaking changes are already pointing to it, and you can start fixing the risky parts now.

## PHP 9 release date and status

There is no announced PHP 9 release date today.

Here is the current picture:

- PHP 8.5 shipped on November 20, 2025 according to the [official release page](https://www.php.net/releases/8.5/en.php).
- PHP 8.6 already has an official [release schedule](https://wiki.php.net/todo/php86) and its current GA date is November 19, 2026.
- PHP 9 appears as a future target on the [RFC index](https://wiki.php.net/rfc) and in the public [php-src milestone](https://github.com/php/php-src/milestone/6), but that milestone still has no due date.

That means PHP 9 is not the next train. PHP 8.6 is.

## What changed since the last update

The old version of this post was still talking about PHP 9 as a distant idea. Since then, two things changed:

1. [PHP 8.5 is out](/php-85), so we now have one more release worth of deprecations and cleanup work behind us.
2. [PHP 8.6 is taking shape](/php-86), and the official schedule makes it clear that 8.6 comes before any PHP 9 release.

That changes the angle of the post. The main value is no longer speculation about a date. It is understanding which removals and error promotions are already visible, so you can fix your code early.

## Breaking changes already pointing to PHP 9

These are the clearest PHP 9 candidates you should care about today because they already have RFCs or are explicitly tracked for PHP 9.

### Undefined variables should become errors

The [undefined variable error promotion RFC](https://wiki.php.net/rfc/undefined_variable_error_promotion) says the change moved to PHP 9. In plain English, code that currently limps along with a warning should fail fast instead.

For example, this kind of typo should stop immediately:

```php
echo $userName;
```

That is a good change. A warning is easy to miss. A hard failure is much easier to find in development and CI.

### Undefined property reads should become errors

The same idea applies to properties. The [undefined property error promotion RFC](https://wiki.php.net/rfc/undefined_property_error_promotion) aims to turn reads from missing properties into `Error` exceptions in PHP 9.

This kind of code is the classic example:

```php
$user = new stdClass();

echo $user->email;
```

If your code relies on loose, dynamic shapes, now is a good time to move to explicit properties, typed DTOs, or `property_exists()` checks where they make sense.

### `${}` string interpolation is going away

The [dollar-brace interpolation RFC](https://wiki.php.net/rfc/deprecate_dollar_brace_string_interpolation) already mapped the end state: `${foo}` and `${expr}` should become compile errors in PHP 9.

So replace this old form:

```php
$name = 'Benjamin';

echo "Hello ${name}";
```

With one of these instead:

```php
echo "Hello $name";
echo "Hello {$name}";
```

This is usually a quick search-and-replace win.

### Arrays will not auto-create from `false`

The [autovivification on false RFC](https://wiki.php.net/rfc/autovivification_false) says that writing to `false` as if it were an array should throw an `Error` in PHP 9.

Today, some code still does this:

```php
$items = false;
$items[] = 'php';
```

That is fragile because `false` is not an array. The safe fix is to initialize the variable correctly from the start:

```php
$items = [];
$items[] = 'php';
```

### `Serializable` is being phased out

The [phase out `Serializable` RFC](https://wiki.php.net/rfc/phase_out_serializable) says support for the `Serializable` interface should be dropped in PHP 9.

If you still have code like this:

```php
class Payload implements Serializable
{
    // ...
}
```

Plan a move to `__serialize()` and `__unserialize()`. That work is much easier to do now than during a future major-version scramble.

### GET and POST session IDs are on the way out

The [Deprecate GET/POST sessions RFC](https://wiki.php.net/rfc/deprecate-get-post-sessions) takes aim at transparent session IDs in URLs and form data. Its stated goal is to remove that support entirely in PHP 9.

Most modern apps already use cookies only, so this is mainly a cleanup item for legacy systems. Still, it is worth checking older code, custom session settings, or inherited apps.

## Other PHP 9 cleanup work to watch

Not everything is fully locked yet, but the [RFC index](https://wiki.php.net/rfc) also lists a few more PHP 9-oriented cleanup topics.

Two practical ones stand out:

- The [implicitly nullable types RFC](https://wiki.php.net/rfc/deprecate-implicitly-nullable-types) says this older syntax could be removed in PHP 9. This was already deprecated in [PHP 8.4](/php-84), so `function foo(string $name = null)` should become `function foo(?string $name = null)`.
- The RFC index also still tracks follow-up cleanup around overloaded signatures, resource-to-object migration, and saner increment and decrement behavior.

I would treat these as “watch closely” items rather than promises. They matter, but they are not as concrete as the error-promotion and removal work above.

## What to fix now before PHP 9

You do not need a PHP 9 binary to start preparing.

This is the boring but effective plan:

1. Run your app and test suite on the newest PHP 8.x version you support.
2. Turn warnings and deprecations into work items instead of letting them pile up.
3. Replace `${}` interpolation with `$var` or `{$var}`.
4. Stop relying on undefined variables and undefined property reads.
5. Replace `Serializable` with `__serialize()` and `__unserialize()`.
6. Initialize arrays explicitly instead of depending on `false`.

If you want a better upgrade path, start with [PHP 8.5](/php-85) and keep an eye on [PHP 8.6](/php-86). That is where the cleanup work becomes visible first.

## Can you test PHP 9 today?

Not as a real release with a published schedule.

For now, the best way to get ready is to follow the RFCs, watch the public PHP 9 milestone, and keep your code clean on current PHP 8.x releases. That will save you the most time when PHP 9 finally gets a date.

If you want a few more PHP rabbit holes after this:

- [PHP 8.6: release date and confirmed features](/php-86)
- [Is PHP dead? Usage statistics and market share (2025)](/php-is-dead-2025)
- [PHP 8.3 is out, now! Here's what's new and changed.](/php-83)

