---
id: "01KKEW27K8BSG582BAQ3YAJT9K"
title: "PHP array_values(): reindex arrays the right way"
slug: "php-array-values"
author: "benjamincrozat"
description: "Use PHP array_values() to reset numeric keys after filtering or unsetting items and get a clean zero-based array back."
categories:
  - "php"
published_at: 2023-11-11T00:00:00+01:00
modified_at: 2026-03-14T10:12:17Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/a0TQCHHF0o4xrnB.jpg"
sponsored_at: null
---
## Introduction

Use [`array_values()`](https://www.php.net/array_values) when you need to reindex an array and keep only its values. It is especially useful after `array_filter()`, `unset()`, or any operation that leaves gaps in numeric keys.

## What is array_values() in PHP?

In PHP, `array_values()` is a built-in function that returns all the values from an array and indexes them numerically from `0`. Use it when you do not care about the original keys and want a clean, zero-based array.

## How does array_values() work?

Imagine you have an array with string keys or numeric gaps. If you only need the values, `array_values()` strips the keys away and returns a neatly reindexed array.

## Simple examples for array_values()

### Example #1

Let's say you have an array like this:

```php
$fruits = [
    'apple' => 'Apple',
    'orange' => 'Orange',
    'banana' => 'Banana'
];
```

If you apply `array_values()` to this array:

```php
array_values($fruits);
```

You'll get a simple array back:

```php
[
    0 => 'Apple', 
    1 => 'Orange', 
    2 => 'Banana',
]
```

Notice how the associative keys are gone, and the values are indexed from 0 onwards.

### Example #2

Another common use case is after filtering an array, because filtering preserves the original numeric keys:

```php
$numbers = [10, 15, 20, 25, 30];

$even = array_filter($numbers, fn (int $number) => $number % 20 === 0 || $number % 10 === 0);

var_dump($even);
```

Output:

```php
[
    0 => 10,
    2 => 20,
    4 => 30,
]
```

If you apply `array_values()` to that filtered array, you get a clean result back:

```php
$clean = array_values($even);

var_dump($clean);
```

Output:

```php
[
    0 => 10,
    1 => 20,
    2 => 30,
]
```

If you keep reindexing arrays after filtering or sorting them, these are the next reads I would open:

- [Sort PHP arrays without losing track of what changes](/php-array-sort)
- [Filter PHP arrays cleanly without awkward loops](/php-array-filter)
- [Map arrays in PHP without overcomplicating the callback](/php-array-map)
