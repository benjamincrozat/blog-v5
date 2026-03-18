---
id: "01KKEW27MJ9HYRQFW9G3BG0KXY"
title: "PHP str_replace(): examples, gotchas, and alternatives"
slug: "php-str-replace"
author: "benjamincrozat"
description: "Use PHP str_replace() to replace one string, many strings, or array values, and know when str_ireplace() or preg_replace() is the better fit."
categories:
  - "php"
published_at: 2023-07-10T00:00:00+02:00
modified_at: 2026-03-18T21:02:00+00:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-str-replace.png"
sponsored_at: null
---
## Introduction

**Use [`str_replace()`](https://www.php.net/str_replace) when you want to replace exact text in PHP.**

```php
$title = 'Laravel 12 is out';

echo str_replace('12', '13', $title);

// Laravel 13 is out
```

That is the quick answer most searchers want.

The useful part is knowing when `str_replace()` stops being the right tool:

- `str_ireplace()` is for case-insensitive matching
- `preg_replace()` is for patterns, not exact strings
- array replacements run left to right, which can create surprising results
- if the replacement array is shorter than the search array, PHP uses empty strings for the leftovers

This guide focuses on those practical edges instead of stopping at one line of syntax.

## How to use str_replace() in PHP

[`str_replace()`](https://www.php.net/str_replace) replaces every occurrence of one exact string with another exact string.

Its signature is:

```php
str_replace(
    string|array $search,
    string|array $replace,
    string|array $subject,
    int &$count = null
) : string|array
```

- `$search`: the text to find
- `$replace`: the replacement text
- `$subject`: the string or array you want to change
- `$count`: optional; PHP stores the number of replacements here

The important detail is “exact string.” If you need patterns or capture groups, skip straight to `preg_replace()`.

## Replace one string with another

This is the normal case:

```php
$message = 'Hello, unknown person!';

echo str_replace('unknown person', 'Benjamin', $message);

// Hello, Benjamin!
```

PHP replaces all matching occurrences, not just the first one.

## Replace multiple values in one call

You can pass arrays to avoid stacking several calls:

```php
$sentence = '1st, 2nd, and 3rd.';

echo str_replace(
    ['1st', '2nd', '3rd'],
    ['first', 'second', 'third'],
    $sentence
);

// first, second, and third.
```

That is usually easier to read than nesting multiple `str_replace()` calls.

## Replace text inside an array of strings

The subject can also be an array:

```php
$sentences = [
    'Laravel makes PHP pleasant.',
    'Symfony also makes PHP pleasant.',
];

var_dump(
    str_replace(
        'PHP',
        'web development',
        $sentences
    )
);

// [
//     'Laravel makes web development pleasant.',
//     'Symfony also makes web development pleasant.',
// ]
```

That is handy when you are cleaning imported values, labels, or batches of messages.

## Count how many replacements happened

The optional fourth parameter is useful when you want both the changed value and the number of replacements:

```php
$count = 0;

echo str_replace('a', 'b', 'banana', $count);
var_dump($count);

// bbnbnb
// int(3)
```

That can save you from running a second check just to see whether anything changed.

## `str_replace()` vs `str_ireplace()` vs `preg_replace()`

This is the comparison most tutorials leave too vague.

Use [`str_replace()`](https://www.php.net/str_replace) for exact, case-sensitive replacement:

```php
echo str_replace('php', 'PHP', 'php is fun');

// PHP is fun
```

Use [`str_ireplace()`](https://www.php.net/str_ireplace) when case should not matter:

```php
echo str_ireplace('php', 'PHP', 'Php is fun');

// PHP is fun
```

Use [`preg_replace()`](https://www.php.net/preg_replace) when the match is a pattern:

```php
echo preg_replace('/\d+/', '#', 'Version 8.4.16');

// Version #.#.#
```

My rule of thumb is:

- exact text: `str_replace()`
- exact text, case-insensitive: `str_ireplace()`
- patterns or capture groups: `preg_replace()`

## Gotchas worth knowing before you ship

### Multiple replacements are processed left to right

This one surprises people:

```php
echo str_replace(['A', 'B', 'C'], ['B', 'C', 'D'], 'ABC');

// DDD
```

Why `DDD` instead of `BCD`?

Because PHP replaces `A` with `B`, then sees that new `B` and replaces it with `C`, and so on.

If you need one-pass character translation, [`strtr()`](https://www.php.net/strtr) is often the safer fit.

### A shorter replacement array means empty strings

If the replacement array has fewer items than the search array, PHP uses an empty string for the rest:

```php
echo str_replace(['quick', 'brown'], ['slow'], 'quick brown fox');

// slow  fox
```

That is easy to miss when those arrays are built dynamically.

### `str_replace()` is fine for exact UTF-8 text

[`str_replace()`](https://www.php.net/str_replace) is binary-safe, so replacing an exact UTF-8 sequence is fine:

```php
echo str_replace('é', 'e', 'café');

// cafe
```

The more relevant Unicode caveat is with [`str_ireplace()`](https://www.php.net/str_ireplace): as of PHP 8.2, its case folding is ASCII-only, so non-ASCII bytes are compared by byte value.

## Conclusion

For exact string replacement in PHP, `str_replace()` is still the right default. The real value is knowing when to step up to `str_ireplace()` or `preg_replace()`, and when array replacement behavior will give you results that look random until you remember PHP processes them left to right.

If you are still cleaning up string handling in everyday PHP code, these are the next reads I would keep nearby:

- [Split strings into arrays cleanly with explode](/php-explode)
- [Parse URLs safely when you need more than a string replace](/php-parse-url)
- [Extract part of a string cleanly with substr](/php-substr)
