---
id: "01KKV9RKMYRHHTXEKB0QEF4T9S"
title: "PHP substr(): practical examples and edge cases"
slug: "php-substr"
author: "benjamincrozat"
description: "Use PHP substr() to extract part of a string, understand negative offsets and lengths, and know when mb_substr() is the safer choice."
categories:
  - "php"
published_at: 2026-03-16T12:29:00+00:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-substr.png"
sponsored_at: null
---
## Introduction

**Use [`substr()`](https://www.php.net/substr) when you need to extract part of a string in PHP.**

```php
echo substr('Laravel', 0, 4);

// Lara
```

The function is simple once you know what the three arguments mean:

- the string you start from
- the offset where extraction begins
- the optional length

The confusing parts are usually negative offsets, negative lengths, and multibyte strings like `café` or `こんにちは`. That is what this guide focuses on.

## How to use `substr()` in PHP

The signature looks like this:

```php
substr(string $string, int $offset, ?int $length = null): string
```

What each argument does:

- `$string`: the original string
- `$offset`: where to start
- `$length`: how many characters to keep

If you omit the length, `substr()` returns everything from the offset to the end of the string.

## Basic `substr()` examples

### Get the first few characters

```php
echo substr('Laravel', 0, 4);

// Lara
```

Start at position `0` and keep `4` characters.

### Get everything after a position

```php
echo substr('Laravel', 4);

// vel
```

This is useful when you know where the interesting part begins and you want the rest of the string.

### Start from the end with a negative offset

```php
echo substr('Laravel', -3);

// vel
```

A negative offset counts backward from the end of the string.

## Negative lengths and offsets

This is where most articles get vague. These are the rules that matter in practice.

### Negative offset: start from the end

```php
echo substr('Laravel', -4, 3);

// ave
```

`-4` means "start four characters from the end."

### Negative length: stop before the end

```php
echo substr('Laravel', 0, -1);

// Larave
```

A negative length tells PHP to leave characters off the end.

That makes `substr()` handy when you want to remove a file extension, trim a known suffix, or drop the last separator after building a string.

### When the range does not make sense

```php
var_dump(substr('Laravel', 2, -20));

// string(0) ""
```

If the requested slice falls outside the real string boundaries, PHP returns an empty string.

## Common edge cases

### Offset beyond the end returns an empty string

```php
var_dump(substr('Laravel', 20));

// string(0) ""
```

This is not an error. You just get an empty string back.

### Length longer than the remaining string is fine

```php
echo substr('Laravel', 2, 20);

// ravel
```

PHP stops at the end of the string. It does not pad the result.

### `substr()` can return an empty string, not `false`

In current PHP, a lot of old examples still talk about `false` here. For ordinary string slicing, what you will usually see is an empty string when nothing can be extracted.

That means `=== ''` is often the check you want if an empty result matters in your code.

## `substr()` vs `mb_substr()`

This is the most important practical warning in the whole article.

`substr()` works on bytes, not user-visible characters. That is fine for plain ASCII text, but it breaks multibyte UTF-8 strings.

### ASCII works as expected

```php
echo substr('Laravel', 0, 3);

// Lar
```

### Multibyte text can break

```php
var_dump(substr('café', 0, 4));
var_dump(mb_substr('café', 0, 4));

// string(4) "caf�"
// string(5) "café"
```

The same problem shows up even more clearly with non-Latin scripts:

```php
var_dump(substr('こんにちは', 0, 3));
var_dump(mb_substr('こんにちは', 0, 3));

// string(3) "こ"
// string(9) "こんに"
```

If the input can contain UTF-8 text that users actually read, `mb_substr()` is usually the safer default.

## Practical examples

### Remove a known file extension

```php
$filename = 'report.pdf';

echo substr($filename, 0, -4);

// report
```

This works because `.pdf` is four characters long.

### Get the last part of a slug or identifier

```php
$slug = 'laravel-forge';

echo substr($slug, -5);

// forge
```

### Extract a prefix for quick matching

```php
$code = 'INV-2026-0042';

echo substr($code, 0, 3);

// INV
```

If your next step is checking whether that prefix exists rather than extracting it, [this string contains guide](/php-string-contains) is the better fit.

## `substr()` vs related functions

- Use `substr()` when you already know the position you want to cut at.
- Use [strpos()](https://www.php.net/strpos) when you first need to find the position of something.
- Use [explode()](/php-explode) when you are splitting by a separator.
- Use [str_replace()](/php-str-replace) when you want to replace part of the string instead of extracting it.

That keeps the code easier to read because each function has one clear job.

## Conclusion

`substr()` is the right tool when you need part of a string and you already know the offset. The main things to remember are that negative offsets count from the end, negative lengths stop before the end, and UTF-8 text is where `mb_substr()` usually becomes the safer choice.

If you are still working through string handling after this, these are the next reads I would keep open:

- [Check whether a string contains something without the old `strpos()` bug](/php-string-contains)
- [Split strings into arrays when a separator matters](/php-explode)
- [Join pieces back into one string cleanly](/php-implode)
