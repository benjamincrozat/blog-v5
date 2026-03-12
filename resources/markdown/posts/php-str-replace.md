---
id: "01KKEW27MJ9HYRQFW9G3BG0KXY"
title: "PHP's str_replace() made simple"
slug: "php-str-replace"
author: "benjamincrozat"
description: "Manipulate strings in PHP using the extremely useful str_replace() function."
categories:
  - "php"
published_at: 2023-07-10T00:00:00+02:00
modified_at: 2025-07-05T07:24:00+02:00
serp_title: "PHP's str_replace() made simple (2025 edition)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/ZlcgxPjBYUPoQGA.png"
sponsored_at: null
---
## Introduction

PHP provides a plethora of built-in functions, with [`str_replace()`](https://www.php.net/str_replace) being one of my favorites.

This function is fundamental to handling and manipulating strings, playing an essential role in many PHP applications.

## The basics of the str_replace() function in PHP

The [`str_replace()`](https://www.php.net/str_replace) function in PHP replaces strings with other strings. It's that simple, yet powerful.

Its signature looks like this:

```php
str_replace(
    string|array $search,
    string|array $replace, 
    string|array $subject, 
    int &$count = null
) : string|array
```

- `search`: It specifies the value to find. It can either be a `string` or an `array`.
- `replace`: It defines the value to replace the found value with. It can either be a `string` or an `array`.
- `subject`: It's the string or array to be searched and replaced on. It can either be a `string` or an `array`.
- `count`: It's an *optional* `integer` and determines the number of replacements performed.

To me, examples speak more than theory. Let's explore how to use [`str_replace()`](https://www.php.net/str_replace).

## Practical use cases of str_replace() in PHP

### Classic search and replace

Let's say we have a greeting "Hello, unknown person!" and we want to change "unknown person" to "Benjamin".

Using [`str_replace()`](https://www.php.net/str_replace), we can achieve this like so:

```php
$sentence = 'Hello, unknown person!';

echo str_replace('unknown person', 'Benjamin', $sentence);

// Outputs: Hello, Benjamin!
```

### Search and replace multiple values

`str_replace()` can also be used with arrays, sequentially searching and replacing the values.

This can avoid you having to call [`str_replace()`](https://www.php.net/str_replace) multiple times.

For example:

```php
$sentence = '1st, 2nd, and 3rd.';

echo str_replace(
    ['1st', '2nd', '3rd'], 
    ['first', 'second', 'third'], 
    $sentence
);

// Outputs: first, second, and third.
```

### Search and replace in an array of strings

`str_replace()` accepts a value of type `array` as the subject.

Which means you can still avoid calling `str_replace` multiple times if you are able to build an array of subjects. I didn't know that before writing this article!

Let me show you:

```php
$sentences = [
    'You cannot mention Mastodon on Twitter!',
    "Let's build a new society on Mastodon!",
    'Is Mastodon a Twitter-killer?',
];

var_dump(
    str_replace(
        'Mastodon', 
        '@&$!?%', 
        $sentences
    )
);

// Outputs: [
//     'You cannot mention @&$!?% on Twitter!',
//     "Let's build a new society on @&$!?%!",
//     'Is @&$!?% a Twitter-killer?',
// ]
```

### PHP's str_ireplace() isn't case sensitive

The str_replace() function is case-sensitive.

If you need case-insensitive replacement, PHP offers the [`str_ireplace()`](https://php.net/str_ireplace) function.

```php
echo str_ireplace('foo', 'bar', 'FOo foo fOO');

// Outputs: bar bar bar
```

## The limitations of the str_replace() function in PHP

While versatile, [`str_replace()`](https://www.php.net/str_replace) has its limitations:
- **No direct support for regular expressions:** As mentioned, [`str_replace()`](https://www.php.net/str_replace) does not support regex. For this, you'll need to use [`preg_replace()`](https://www.php.net/preg_replace).
- **It's unable to replace multi-byte unicode characters:** The [`str_replace()`](https://www.php.net/str_replace) function is not safe for multi-byte characters like those found in UTF-8 strings.
- **There are problems with case sensitivity:** As noted before, [`str_replace()`](https://www.php.net/str_replace) is case-sensitive. If you need to ignore case, use [`str_ireplace()`](https://www.php.net/str_ireplace).

If you are still cleaning up string handling in everyday PHP code, these are the next reads I would keep nearby:

- [Split strings into arrays cleanly with explode](/php-explode)
- [Read the current URL path without framework helpers](/php-current-url-path)
- [Catch the PHP 8.4 changes that could affect your code](/php-84)
