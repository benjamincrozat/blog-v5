---
id: "01KKEW27K0GTJXCM6NM1PQKQGA"
title: "PHP array_map(): practical examples and key rules"
slug: "php-array-map"
author: "benjamincrozat"
description: "Use PHP array_map() to transform arrays, map multiple arrays at once, preserve keys when possible, and zip arrays with a null callback."
categories:
  - "php"
published_at: 2023-11-04T00:00:00+01:00
modified_at: 2026-03-19T09:35:00+00:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-array-map.png"
sponsored_at: null
---
## Introduction

**Use [`array_map()`](https://www.php.net/array_map) when you want to transform every item in an array and return a new array.**

It is a clean alternative to building a second array manually in a `foreach` loop, and it becomes even more useful when you need to map multiple arrays at once. The two rules worth remembering are:

- keys are preserved only when you pass one array
- `array_map()` passes values, not keys, unless you deliberately pass keys as another array

## How to use array_map() in PHP

`array_map()` takes a callback first, then one or more arrays:

```php
array_map(?callable $callback, array $array, array ...$arrays): array
```

The callback receives the current value from each array position and returns the transformed value.

Here is a simple example:

```php
$frameworks = ['laravel', 'symfony', 'livewire'];
$upper = array_map('strtoupper', $frameworks);
```

`$upper` contains:

```php
[
    'LARAVEL',
    'SYMFONY',
    'LIVEWIRE',
]
```

Arrow functions work just as well:

```php
$prices = [10, 20, 30];

$discountedPrices = array_map(
    fn (float $price): float => $price * 0.8,
    $prices
);
```

That “transform every item into another item” use case is where `array_map()` shines.

## Mapping multiple arrays at once

`array_map()` can map more than one array in parallel:

```php
$products = ['Apple Watch', 'iMac', 'iPhone'];
$prices = [10, 20, 30];

$discountedProducts = array_map(
    fn (string $product, int $price) => [
        'product' => $product,
        'discounted_price' => $price * 0.8,
    ],
    $products,
    $prices,
);
```

This builds a new array from the matching positions of `$products` and `$prices`.

If the arrays are not the same length, PHP keeps going and fills missing values with `null`:

```php
$letters = ['a', 'b'];
$numbers = [1, 2, 3];

array_map(null, $letters, $numbers);

// [['a', 1], ['b', 2], [null, 3]]
```

That matters when you assume the arrays are perfectly aligned.

## array_map() and array keys

One subtle rule from the PHP manual is worth remembering:

- If you pass exactly one array, the original keys are preserved.
- If you pass more than one array, the result gets sequential integer keys.

That is why this keeps associative keys:

```php
$users = [
    'first' => 'Ben',
    'second' => 'Taylor',
];

$upper = array_map('strtoupper', $users);
// ['first' => 'BEN', 'second' => 'TAYLOR']
```

But the earlier multi-array example returns `0`, `1`, `2`, and so on.

## How to map keys too

`array_map()` does not pass array keys to the callback on its own. If you need both the key and the value, pass them explicitly:

```php
$users = [
    'first' => 'Ben',
    'second' => 'Taylor',
];

$labels = array_map(
    fn (string $value, string $key): string => strtoupper($key) . ':' . $value,
    array_values($users),
    array_keys($users),
);

// ['FIRST:Ben', 'SECOND:Taylor']
```

That is a good pattern when you want key-aware transformations without falling back to `foreach`.

## Passing null as the callback

`array_map()` also accepts `null` as the callback. In that case, PHP zips the arrays together instead of transforming them:

```php
$letters = ['a', 'b', 'c'];
$numbers = [1, 2, 3];

$pairs = array_map(null, $letters, $numbers);

print_r($pairs);
// [['a', 1], ['b', 2], ['c', 3]]
```

That is handy when you need to combine arrays position by position.

## When to use array_map() vs foreach

Use `array_map()` when the job is “turn every item into another item”.

Use `foreach` when the loop has side effects, needs early exits, or becomes too complex for a tidy callback.

If transforming data is the part of PHP you keep coming back to, these are the next reads I would open next:

- [See what partial function application looks like in PHP 8.6](/partial-function-application-php-86)
- [Filter PHP arrays cleanly without awkward loops](/php-array-filter)
- [Reset array keys cleanly when the indexes get weird](/php-array-values)
