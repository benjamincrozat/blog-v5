---
id: "01KKEW27KRDMRV4YFYVNAP1TZG"
title: "PHP explode(): split a string into an array"
slug: "php-explode"
author: "benjamincrozat"
description: "Use PHP explode() to split a string by a fixed delimiter, understand limit behavior, and know when preg_split() or str_getcsv() is the better tool."
categories:
  - "php"
published_at: 2023-11-07T23:00:00Z
modified_at: 2026-03-19T22:55:03Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/5qCHWcOwVfYznTF.jpg"
sponsored_at: null
---
## Introduction

Use [`explode()`](https://www.php.net/manual/en/function.explode.php) when you need to split a string by a fixed delimiter like a comma, pipe, slash, or space.

```php
$tags = explode(', ', 'php, laravel, mysql');

print_r($tags);

// Array ( [0] => php [1] => laravel [2] => mysql )
```

That is the main job of `explode()`: turn one string into an array. The two things that usually trip people up are the `limit` argument and choosing `explode()` when the input is not really fixed-delimiter text.

## PHP explode() syntax

```php
explode(string $separator, string $string, int $limit = PHP_INT_MAX): array
```

- `$separator`: the string to split on
- `$string`: the input string
- `$limit`: optional control over how many pieces you get back

Quick rules:

- Positive `limit`: returns at most that many elements, with the last one containing the remainder
- `limit = 0`: treated as `1`
- Negative `limit`: returns everything except the last `abs($limit)` parts

## The most useful explode() examples

### Split a comma-separated string

```php
$emails = explode(',', 'ana@example.com,ben@example.com,cam@example.com');

print_r($emails);

// Array ( [0] => ana@example.com [1] => ben@example.com [2] => cam@example.com )
```

If your input contains spaces after commas, trim the pieces:

```php
$emails = array_map('trim', explode(',', 'ana@example.com, ben@example.com, cam@example.com'));

print_r($emails);

// Array ( [0] => ana@example.com [1] => ben@example.com [2] => cam@example.com )
```

### Split only once

This is handy when you want the first token and everything after it.

```php
$line = 'name: Benjamin Crozat';
$parts = explode(':', $line, 2);

print_r($parts);

// Array ( [0] => name [1] =>  Benjamin Crozat )
```

With `2`, the second element keeps the rest of the string intact.

### Drop the last piece with a negative limit

```php
$path = 'posts/php/explode/draft';
$parts = explode('/', $path, -1);

print_r($parts);

// Array ( [0] => posts [1] => php [2] => explode )
```

This is useful when you want to remove a known suffix.

### Split on line breaks or mixed whitespace

`explode()` is great for one exact delimiter. If the input may contain multiple spaces, tabs, or newlines, [`preg_split()`](https://www.php.net/manual/en/function.preg-split.php) is usually the better choice.

```php
$text = "php   laravel\tmysql\nredis";
$parts = preg_split('/\s+/', trim($text));

print_r($parts);

// Array ( [0] => php [1] => laravel [2] => mysql [3] => redis )
```

## Common explode() edge cases

### What happens if the separator is not found?

You still get an array back. It just contains the original string as its only element.

```php
$result = explode('|', 'no pipes here');

print_r($result);

// Array ( [0] => no pipes here )
```

If you use a negative limit and the separator is missing, PHP returns an empty array.

```php
$result = explode('|', 'no pipes here', -1);

print_r($result);

// Array ( )
```

### Leading or trailing delimiters create empty elements

```php
$result = explode(',', ',php,laravel,');

print_r($result);

// Array ( [0] =>  [1] => php [2] => laravel [3] =>  )
```

If that is not what you want, trim the string first or filter empty values afterward:

```php
$result = array_values(array_filter(explode(',', ',php,laravel,'), 'strlen'));

print_r($result);

// Array ( [0] => php [1] => laravel )
```

### An empty separator throws in PHP 8+

As of PHP 8.0, `explode('', 'abc')` throws a `ValueError`.

```php
explode('', 'abc'); // ValueError
```

### Parameter order matters

`explode()` is always `explode($separator, $string)`.

```php
// Wrong
explode($string, $separator);

// Right
explode($separator, $string);
```

## explode() vs the main alternatives

Use `explode()` when the delimiter is fixed and simple. Use something else when the real problem is more complex.

| Use case | Best choice | Why |
| --- | --- | --- |
| Split by one exact delimiter like `,` or `|` | `explode()` | Fast, readable, built for this |
| Split by spaces, tabs, or multiple delimiters | [`preg_split()`](https://www.php.net/manual/en/function.preg-split.php) | Regex handles variable separators |
| Parse real CSV with quotes or escaped commas | [`str_getcsv()`](https://www.php.net/manual/en/function.str-getcsv.php) | Keeps quoted values intact |
| Split into characters | [`str_split()`](https://www.php.net/manual/en/function.str-split.php) | Works character by character |

### Use str_getcsv() for CSV, not explode()

This is a very common mistake. `explode(',', $string)` breaks as soon as a field contains a quoted comma.

```php
$csv = '"Crozat, Benjamin",Developer,Remote';
$parts = str_getcsv($csv);

print_r($parts);

// Array ( [0] => Crozat, Benjamin [1] => Developer [2] => Remote )
```

### Use preg_split() when whitespace is inconsistent

If the input can contain one space, three spaces, tabs, or newlines, `explode(' ', $string)` is too brittle.

```php
$text = "php   laravel\tmysql";
$parts = preg_split('/\s+/', trim($text));

print_r($parts);

// Array ( [0] => php [1] => laravel [2] => mysql )
```

## FAQ

### Does explode() remove spaces automatically?

No. If you split a comma-separated string like `'a, b, c'` with `explode(',', $string)`, the second and third values keep their leading spaces. Use `array_map('trim', ...)` if needed.

### Why does explode() return empty strings?

Because delimiters at the start, end, or repeated in the middle create empty array elements. That behavior is normal.

### Does explode() work with regular expressions?

No. If you need regex matching, use [`preg_split()`](https://www.php.net/manual/en/function.preg-split.php).

### What is the opposite of explode() in PHP?

[`implode()`](https://www.php.net/manual/en/function.implode.php). It joins array elements into one string.

## Conclusion

`explode()` is the right PHP function when you want to split a string by one known delimiter. Reach for it first for commas, pipes, slashes, and similar fixed separators. If the input is really CSV, inconsistent whitespace, or pattern-based text, switch to the more appropriate tool instead of forcing `explode()` to do a job it was not built for.

If you are still cleaning up string handling in PHP, these are the next reads I would keep open:

- [Join array values back into a string with implode()](/php-implode)
- [Clean up strings with str_replace without weird edge cases](/php-str-replace)
- [Parse URLs safely before splitting paths by hand](/php-parse-url)
