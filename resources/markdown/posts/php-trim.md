---
id: "01KM62PMJNY8FAZ0P544HWBA15"
title: "How to trim strings in PHP with trim()"
slug: "php-trim"
author: "benjamincrozat"
description: "Use PHP trim() to clean input, remove edge characters, and understand why hidden whitespace or Unicode spaces can survive."
categories:
  - "php"
published_at: 2026-03-20T16:55:01+00:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-trim.png"
sponsored_at: null
---
## Introduction

**Use [`trim()`](https://www.php.net/trim) when you need to remove whitespace or other characters from both ends of a string in PHP.**

```php
$name = trim($_POST['name'] ?? '');
```

That is the quick answer, but there are a few gotchas worth knowing early:

- `trim()` only affects the beginning and end of the string
- it returns a new string instead of changing the original variable
- the second argument is a character mask, not an exact substring
- Unicode whitespace such as non-breaking spaces may survive, which is where [`mb_trim()`](https://www.php.net/manual/en/function.mb-trim.php) becomes useful on PHP 8.4+

This guide focuses on those real-world cases instead of stopping at the syntax.

## How to use `trim()` in PHP

The signature looks like this:

```php
trim(string $string, string $characters = " \n\r\t\v\0"): string
```

What the arguments mean:

- `$string`: the input you want to clean up
- `$characters`: the optional set of characters PHP should strip from both ends

Without the second argument, `trim()` removes the default edge whitespace characters:

- ordinary spaces
- tabs
- new lines
- carriage returns
- NUL bytes
- vertical tabs

That makes it useful for form input, CLI input, file lines, and request path cleanup.

## The most useful `trim()` examples

### Clean form input before validation

This is the everyday use case:

```php
$name = trim($_POST['name'] ?? '');
```

If the field may be missing, the `?? ''` matters. It gives `trim()` a string instead of `null`.

If you are still sorting out those tiny PHP checks around missing inputs, [this `isset()` guide](/php-isset) is a useful companion.

### Remove the trailing newline from a file line

```php
$line = trim(fgets($handle));
```

That removes the `\n` or `\r\n` that often comes from file reads.

If you only want to remove the right side and leave leading spaces alone, use [`rtrim()`](https://www.php.net/rtrim) instead.

### Remove slashes from both ends of a path

```php
$path = trim('/docs/laravel/', '/');

echo $path;

// docs/laravel
```

This is handy when you need a normalized path segment.

If you are starting from a full URL rather than a plain path, [parse the URL first](/php-parse-url) instead of trimming blindly.

### Remove quotes or other wrapper characters

```php
$value = trim('"Laravel"', "\"'");

echo $value;

// Laravel
```

This works because the second argument can contain multiple characters PHP should strip from both ends.

### Remove ASCII control characters

The PHP manual notes that you can trim a range of characters too:

```php
$value = "\x00\x1FHello\x1E";

echo trim($value, "\x00..\x1F");

// Hello
```

That is useful when input arrives from a messy external source.

## The biggest `trim()` gotcha: the second argument is a character mask

This is the part that trips people up most often.

The second argument is **not** treated as one exact prefix or suffix. PHP treats it as a set of individual characters to strip while they keep appearing at either edge.

Example:

```php
echo trim('foobar', 'bar');

// foo
```

That does **not** mean “remove the exact word `bar` once.” It means “keep removing `b`, `a`, or `r` from the left and right edges until none remain.”

The same rule explains this result:

```php
echo trim('abcHelloabc', 'abc');

// Hello
```

That behavior is powerful once you know it, but it is also why `trim()` can feel surprising at first.

## Why `trim()` sometimes seems not to work

Most confusion comes from one of these three situations.

### It only trims the edges

`trim()` does not touch whitespace in the middle of the string:

```php
echo trim("Hello   World");

// Hello   World
```

If the extra whitespace is inside the string, `trim()` is the wrong tool.

### It returns a new string

This does nothing useful:

```php
$name = ' Laravel ';

trim($name);

echo $name;

//  Laravel
```

You need to assign the result:

```php
$name = trim($name);
```

### The whitespace is Unicode, not plain ASCII

The classic example is a non-breaking space copied from HTML or a CMS.

```php
$value = "\u{00A0}Laravel\u{00A0}";

var_dump(trim($value) === $value);
var_dump(mb_trim($value));

// true
// string(8) "Laravel"
```

On PHP 8.4+, [`mb_trim()`](https://www.php.net/manual/en/function.mb-trim.php) is the cleaner answer for multibyte whitespace.

If you are on an older PHP version, a Unicode-aware fallback looks like this:

```php
$value = preg_replace('/^[\p{Z}\s]+|[\p{Z}\s]+$/u', '', $value);
```

That is usually only worth it when you know copied content or multilingual text is involved.

## `trim()` vs `ltrim()` vs `rtrim()`

These functions are the same basic tool with different sides enabled:

| Function | What it removes |
| --- | --- |
| `trim()` | both ends |
| [`ltrim()`](https://www.php.net/ltrim) | left side only |
| [`rtrim()`](https://www.php.net/rtrim) | right side only |

Examples:

```php
echo trim('  Laravel  ');  // Laravel
echo ltrim('  Laravel  '); // Laravel
echo rtrim('  Laravel  '); //   Laravel
```

Use `trim()` when you want a general cleanup step.

Use `ltrim()` or `rtrim()` when one side is meaningful and should stay untouched.

## When `trim()` is the right tool

`trim()` is a good default when:

- you are cleaning user input before checking whether it is blank
- you want to normalize a path or identifier by removing wrapper characters
- you are stripping line endings from file or CLI input
- the whitespace is ordinary ASCII edge whitespace

It is not the right tool when:

- you need to remove whitespace in the middle of the string
- you need to remove one exact substring rather than a set of characters
- you are dealing with Unicode edge whitespace and are on PHP 8.4+ where `mb_trim()` is clearer

## Conclusion

`trim()` is simple once you know its boundaries. It removes edge whitespace well, but it does not change the middle of the string, it does not mutate the original variable, and its second argument is a character mask rather than an exact word match.

If you are still cleaning up PHP input after this, these are the next reads I would keep open:

- [Check whether a value exists before you clean it](/php-isset)
- [Parse URLs safely before trimming path pieces](/php-parse-url)
- [Extract part of a string once the edges are clean](/php-substr)
