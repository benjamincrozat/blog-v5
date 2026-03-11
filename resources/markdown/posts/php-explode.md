---
id: "01KKEW27KRDMRV4YFYVNAP1TZG"
title: "PHP explode(): How you can split strings into arrays"
slug: "php-explode"
author: "benjamincrozat"
description: "Learn PHP’s explode() to split strings into arrays. See syntax, limit behavior (0 and negative), edge cases, and alternatives like preg_split and str_getcsv."
categories:
  - "php"
published_at: 2023-11-08T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/5qCHWcOwVfYznTF.jpg"
sponsored_at: null
---
## Introduction

PHP’s [explode()](https://www.php.net/explode) splits a string into an array using a separator you choose. It is fast and easy, and it is the tool most people use to break a list like "a, b, c" into parts. In this guide, I show you how `explode()` works, how the limit parameter really behaves, common edge cases, and when to use preg_split or str_getcsv instead.

Quick start:

```php
$devices = explode(', ', 'apple tv, apple watch, imac, iphone, macbook pro');

print_r($devices);

// Array ( [0] => apple tv [1] => apple watch [2] => imac [3] => iphone [4] => macbook pro )
```

## Syntax and parameters

### explode(string $separator, string $string, int $limit = PHP_INT_MAX): array

```php
array explode(string $separator, string $string, int $limit = PHP_INT_MAX)
```
- separator: The boundary string to split on.
- string: The input text to split.
- limit (optional): Controls the number of array elements returned.
  - If limit > 0: return at most limit elements; the last element contains the rest of the string.
  - If limit = 0: treated as 1 (not “no limit”).
  - If limit < 0: return all parts except the last |limit| parts.

Note on missing separators:
- If the separator is not found, explode() returns an array with the original string as a single element. With a negative limit, it returns an empty array.

### What limit > 0, 0, and < 0 actually do

- limit > 0: Split up to that many elements; the last element gets the remainder.
- limit = 0: Works like 1; you get a one-element array.
- limit < 0: Drop that many parts from the end and return the rest.

## Examples

### Basic split by comma and by space

```php
// Comma + space
$devices = explode(', ', 'apple tv, apple watch, imac, iphone, macbook pro');

print_r($devices);

// Array ( [0] => apple tv [1] => apple watch [2] => imac [3] => iphone [4] => macbook pro )

// Single space
$words = explode(' ', 'split this line');

print_r($words);

// Array ( [0] => split [1] => this [2] => line )
```

### Using limit > 0, 0, and < 0 (with outputs)

```php
// limit > 0 (2): last element holds the remainder
$parts = explode(', ', 'a, b, c, d', 2);

print_r($parts);

// Array ( [0] => a [1] => b, c, d )
```

```php
// limit = 0: treated as 1
$parts = explode(', ', 'a, b, c', 0);

print_r($parts);

// Array ( [0] => a, b, c )
```

```php
// limit < 0 (-1): drop the last element
$parts = explode(', ', 'a, b, c, d', -1);

print_r($parts);

// Array ( [0] => a [1] => b [2] => c )
```

### Edge cases: missing delimiter; leading/trailing delimiters

```php
// Separator not found.
$missing = explode('|', 'no pipes here');

print_r($missing);

// Array ( [0] => no pipes here )

// Separator not found with negative limit → empty array.
$empty = explode('|', 'still no pipes', -1);

print_r($empty);

// Array ( )
```

```php
// Leading and trailing delimiters produce empty elements.
$around = explode(',', ',a,b,');

print_r($around);

// Array ( [0] =>  [1] => a [2] => b [3] =>  )
```

## Common pitfalls and gotchas

### Empty separator (PHP 8+ ValueError)

In PHP 8+, passing an empty string as the separator throws a ValueError. Always pass a non-empty separator.

```php
explode('', 'abc'); // ValueError in PHP 8+
```

### Parameter order (explode vs implode)

[explode()](https://www.php.net/explode) takes (separator, string) and has never supported reversed order. A common mistake is to swap them. By contrast, implode() accepts (glue, array) and also supports (array, glue).

```php
// Wrong:
explode($string, $separator)

// Right:
$parts = explode($separator, $string);
```

### Trimming whitespace (array_map + trim)

When splitting CSV-like text, trim each part to remove stray spaces.

```php
$raw = ' a , b ,  c ';

$parts = array_map('trim', explode(',', $raw));

print_r($parts);

// Array ( [0] => a [1] => b [2] => c )
```

## explode vs alternatives

### preg_split for regex or multiple delimiters

Use preg_split() when you need regex support or several delimiters at once (for example, split on commas, semicolons, or any whitespace). See the [preg_split manual](https://www.php.net/manual/en/function.preg-split.php).

```php
$text = "one   two\tthree\nfour";

$parts = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

print_r($parts);

// Array ( [0] => one [1] => two [2] => three [3] => four )
```

### str_getcsv for real CSV

If your data has quotes or escaped commas, use str_getcsv() so quoted fields stay intact. See the [str_getcsv manual](https://www.php.net/manual/en/function.str-getcsv.php).

```php
$csv = '"Last, First",30,Developer';

$row = str_getcsv($csv);

print_r($row);

// Array ( [0] => Last, First [1] => 30 [2] => Developer )
```

## FAQs

### What happens if the separator is not found?

You get an array with the original string as the only element. If you use a negative limit, [explode()](https://www.php.net/explode) returns an empty array.

### Why does limit = 0 act like 1?

By design, PHP treats 0 as 1 for this function. It never means “no limit.” If you want “no limit,” omit the parameter or pass a positive number large enough.

### How do I use explode with spaces or newlines?

For a single space, use explode(' ', $string). For multiple spaces or newlines, use [preg_split()](https://www.php.net/preg_split) with a regex like /\s+/ to handle any whitespace.

### How do I split into characters?

Use [str_split()](https://www.php.net/preg_split) to break a string into individual characters.

## Conclusion

`explode()` splits strings by a separator and returns an array. Remember the key rules: limit > 0 returns up to that many parts (with the remainder in the last part), limit = 0 acts like 1, and limit < 0 drops parts from the end. For regex or many delimiters, prefer `preg_split()`; for true CSV data, prefer `str_getcsv()`. If you need the reverse, join arrays with [implode()](https://www.php.net/implode). I hope this helped you use explode() with confidence. For full details, see the [explode() manual](https://www.php.net/explode).