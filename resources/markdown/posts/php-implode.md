---
id: "01KKV8GGB19F5CZ69PP32W1YRR"
title: "How to join array values into a string in PHP"
slug: "php-implode"
author: "benjamincrozat"
description: "Use PHP implode() to join array values into strings, choose the right separator, handle associative arrays, and avoid the edge cases that trip people up."
categories:
  - "php"
published_at: 2026-03-16T12:27:00Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-implode.png"
sponsored_at: null
---
## Introduction

**Use [`implode()`](https://www.php.net/implode) when you need to turn an array into a string.**

It joins the array values with a separator you choose, which makes it perfect for comma-separated lists, CSS class strings, SQL fragments, and any other "array to text" job.

Here is the fastest example:

```php
$tags = ['php', 'laravel', 'mysql'];

echo implode(', ', $tags);

// php, laravel, mysql
```

The two rules worth remembering are simple:

- `implode()` joins **values**, not keys.
- If your array contains `null`, nested arrays, or values you still need to format, clean those first.

## How to use `implode()` in PHP

The current signature is:

```php
implode(string $separator, array $array): string
implode(array $array): string
```

You can pass the separator and the array, or pass the array alone if you want the values concatenated with no separator at all.

```php
echo implode('-', ['2026', '03', '16']);
// 2026-03-16

echo implode(['P', 'H', 'P']);
// PHP
```

`join()` is just an alias of [`implode()`](https://www.php.net/implode), but `implode()` is the clearer name and the one most PHP developers expect to see.

## When `implode()` is the right tool

Reach for `implode()` when you already have the pieces in an array and your next step is to render them as one string.

Typical examples:

- show tags or categories separated by commas
- build a space-separated CSS class list
- join path segments with `/`
- turn a list of formatted values into SQL or log output

If you need the reverse operation, use [explode()](/php-explode) to split a string into an array.

## Practical examples of `implode()`

### Join a simple list with commas

```php
$frameworks = ['Laravel', 'Symfony', 'CodeIgniter'];

echo implode(', ', $frameworks);

// Laravel, Symfony, CodeIgniter
```

This is the most common use case: take an array of values and render a readable list.

### Build a CSS class string

This pattern shows up everywhere in PHP templates:

```php
$classes = array_filter([
    'btn',
    'btn-primary',
    $isDisabled ? 'opacity-50' : null,
    $hasError ? 'border-red-500' : null,
]);

echo implode(' ', $classes);
```

If `$isDisabled` is `true` and `$hasError` is `false`, the output is:

```php
btn btn-primary opacity-50
```

Filtering first matters here because otherwise `null` values become empty strings. If you do this kind of cleanup often, [array_filter()](/php-array-filter) is worth keeping nearby.

### Add quotes around every value before joining

Sometimes the array is almost ready, but each item needs a small transformation first.

```php
$columns = ['name', 'email', 'created_at'];

$quotedColumns = array_map(
    static fn (string $column): string => "`{$column}`",
    $columns,
);

echo implode(', ', $quotedColumns);

// `name`, `email`, `created_at`
```

This is cleaner than manually concatenating inside a loop, and it makes the transformation step obvious.

### Associative arrays: keys are ignored

This catches a lot of people the first time:

```php
$user = [
    'first_name' => 'Taylor',
    'last_name' => 'Otwell',
];

echo implode(' ', $user);

// Taylor Otwell
```

`implode()` joins only the values. It does not include the keys.

If you need `key=value` pairs, format them first:

```php
$params = [
    'page' => 2,
    'sort' => 'latest',
];

$pairs = array_map(
    static fn (string $key, string|int $value): string => "{$key}={$value}",
    array_keys($params),
    $params,
);

echo implode('&', $pairs);

// page=2&sort=latest
```

For real query strings, [`http_build_query()`](https://www.php.net/http_build_query) is usually the better choice because it handles URL encoding for you.

### An empty array returns an empty string

```php
$values = [];

var_dump(implode(', ', $values));

// string(0) ""
```

That behavior is useful, but it can hide missing data if you expected at least one value. If an empty result would be a bug, check the array first.

## Common pitfalls and gotchas

### The old parameter order is not supported in modern PHP

Older PHP versions accepted the arguments in reverse order. Current PHP expects the separator first and the array second.

```php
$parts = ['a', 'b', 'c'];

echo implode(', ', $parts);
// a, b, c
```

If you reverse them in PHP 8+, you get a `TypeError`.

### Nested arrays produce warnings and useless output

`implode()` is for flat arrays of scalar values. If one item is itself an array, PHP will try to convert it to the string `"Array"` and emit a warning.

```php
$values = [['a'], ['b']];

echo implode(', ', $values);

// Warning: Array to string conversion
// Array, Array
```

If your data is nested, flatten or transform it first.

### `null` becomes an empty string

This is another subtle one:

```php
$values = ['php', null, 'laravel'];

echo implode(', ', $values);

// php, , laravel
```

That extra gap is easy to miss. Filter out `null` values first if you do not want blank segments in the output.

### Non-string scalars are cast to strings

Integers, floats, and booleans are converted to strings automatically:

```php
$values = [1, true, 3.5, false];

echo implode(', ', $values);

// 1, 1, 3.5,
```

That can be convenient, but it is also a good reason to format values explicitly when the final output matters.

### Omitting the separator is valid, but rarely the clearest option

Passing only the array works:

```php
echo implode(['P', 'H', 'P']);
// PHP
```

Still, when readability matters, I prefer passing the separator explicitly because it makes the output format obvious at a glance.

## `implode()` vs `explode()`

These two functions are easy to mix up, so here is the mental shortcut:

| Function | Input | Output | Use it when |
| --- | --- | --- | --- |
| `implode()` | array | string | you want to join array values |
| `explode()` | string | array | you want to split a string by a separator |

Example:

```php
$tags = ['php', 'laravel', 'mysql'];

$line = implode(', ', $tags);
// php, laravel, mysql

$again = explode(', ', $line);
// ['php', 'laravel', 'mysql']
```

If you keep bouncing between arrays and strings, [explode()](/php-explode) and [`implode()`](https://www.php.net/implode) are a pair worth memorizing.

## Conclusion

`implode()` is the right tool when you already have an array and need one string back. The most important things to remember are that it joins values only, it returns an empty string for an empty array, and it works best when you clean or format the data first.

If you are still shaping PHP data after this, these are the next reads I would keep open:

- [Split strings into arrays cleanly with explode](/php-explode)
- [Filter PHP arrays cleanly before you join them](/php-array-filter)
- [Map array values first when each item needs formatting](/php-array-map)
