---
id: "01KKV9G24MNK41XG6FFCD34FNJ"
title: "PHP string contains: use str_contains() and strpos() safely"
slug: "php-string-contains"
author: "benjamincrozat"
description: "Check whether a PHP string contains a value with str_contains(), use strpos() safely on older PHP versions, and handle case sensitivity without false positives."
categories:
  - "php"
published_at: 2026-03-16T12:28:30Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-string-contains.png"
sponsored_at: null
---
## Introduction

**If you are on PHP 8.0 or later, use [`str_contains()`](https://www.php.net/str_contains) to check whether one string contains another.**

```php
if (str_contains('Laravel Forge', 'Forge')) {
    //
}
```

That is the clearest answer for modern PHP.

If you are on an older version, the fallback is [`strpos()`](https://www.php.net/strpos) with a strict comparison:

```php
if (strpos('Laravel Forge', 'Forge') !== false) {
    //
}
```

The strict comparison matters because `strpos()` returns `0` when the match starts at the beginning of the string, and `0` is easy to mistake for `false`.

## The best way to check if a string contains something in PHP

### PHP 8.0 and later: `str_contains()`

PHP 8.0 introduced [`str_contains()`](https://www.php.net/str_contains), which returns a plain boolean.

```php
var_dump(str_contains('Laravel Forge', 'Forge'));
var_dump(str_contains('Laravel Forge', 'forge'));

// true
// false
```

That is why it is the best default now: the intent is obvious, and there is no `0 !== false` edge case to remember.

### Older PHP: `strpos() !== false`

If you are supporting PHP 7 or older code, use `strpos()` with a strict comparison:

```php
var_dump(strpos('Laravel Forge', 'Lar'));
var_dump(strpos('Laravel Forge', 'Forge'));
var_dump(strpos('Laravel Forge', 'nope'));

// 0
// 8
// false
```

This is the safe version:

```php
if (strpos('Laravel Forge', 'Lar') !== false) {
    //
}
```

This is the buggy version:

```php
if (strpos('Laravel Forge', 'Lar')) {
    //
}
```

The second example fails because `strpos()` returns `0`, and plain `if (0)` is falsy.

## `str_contains()` vs `strpos()`

Here is the practical difference:

| Function | PHP version | Return value | Best use |
| --- | --- | --- | --- |
| `str_contains()` | PHP 8.0+ | `true` or `false` | modern contains checks |
| `strpos()` | all supported PHP versions | position or `false` | older PHP or when you need the index |

If you only need a yes/no answer, `str_contains()` is clearer.

If you also need the position of the match, `strpos()` is still the right tool.

```php
$position = strpos('Laravel Forge', 'Forge');

if ($position !== false) {
    echo $position;
}

// 8
```

## Case-sensitive vs case-insensitive checks

`str_contains()` is case-sensitive:

```php
var_dump(str_contains('Laravel Forge', 'forge'));

// false
```

If you need a case-insensitive contains check, [`stripos()`](https://www.php.net/stripos) is the straightforward option:

```php
var_dump(stripos('Laravel Forge', 'forge') !== false);
var_dump(stripos('Laravel Forge', 'LAR') !== false);

// true
// true
```

Use `stripos() !== false` with the same strict comparison rule as `strpos()`.

## Practical examples

### Check whether a URL contains a path segment

```php
$path = '/docs/laravel/forge';

if (str_contains($path, '/forge')) {
    echo 'Forge docs';
}
```

If you are working with raw request paths, [this current URL path guide](/php-current-url-path) covers the safer way to get the path first.

### Check for a tag or keyword in free text

```php
$text = 'Laravel Forge makes server provisioning easier.';

if (str_contains($text, 'Forge')) {
    echo 'Mentioned';
}
```

### Check the start of a string on older PHP

This is the classic `strpos()` trap:

```php
$text = 'Laravel Forge';

var_dump(strpos($text, 'Laravel') !== false);

// true
```

If you forget the strict comparison, that same check can fail because the match starts at position `0`.

## Important edge cases

### An empty needle matches every string

This surprises people the first time they see it:

```php
var_dump(str_contains('Laravel Forge', ''));

// true
```

An empty string is considered to be contained in every string, including another empty string.

If an empty search term should count as invalid input in your app, validate it before checking.

### `strpos()` can return `0`

This is worth repeating because it causes so many bugs:

```php
var_dump(strpos('Laravel Forge', 'Lar'));

// 0
```

`0` means "found at the beginning," not "not found."

### Case-insensitive checks need a different function

There is no built-in `str_icontains()` in PHP. For a case-insensitive contains check, use `stripos() !== false`.

## What if you need to split or replace instead?

Contains checks are often just the first step.

- If you need to split a string once the separator is found, use [explode()](/php-explode).
- If you need to replace part of the string, use [str_replace()](/php-str-replace).

That keeps each function doing one clear job.

## Conclusion

For modern PHP, the answer is simple: use `str_contains()` when you want a true-or-false contains check. Use `strpos() !== false` only when you need to support older PHP or when the match position itself matters.

If you are still shaping strings after that, these are the next reads I would keep open:

- [Replace part of a string without reaching for regex](/php-str-replace)
- [Split strings into arrays when a separator matters](/php-explode)
- [Join array values back into one string cleanly](/php-implode)
