---
id: "01KKEW27K31T1FCNHMP674ERSA"
title: "PHP array sort: which function to use and when"
slug: "php-array-sort"
author: "benjamincrozat"
description: "Choose the right PHP array sort function, preserve or reset keys deliberately, use sort flags, and write stable custom and multi-column comparisons."
categories:
  - "php"
published_at: 2023-11-08T23:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/fatzUFHaSxNN3gJ.jpg"
sponsored_at: null
---
Use `sort()` for a simple list, `asort()` when associative keys must stay attached to their values, `ksort()` to order by key, and `usort()` for custom rules.

The function name matters because PHP's array sort functions modify the original array, and several of them reset its keys.

## PHP array sorting functions compared

| Function | Sorts by | Order | Keeps keys? | Modifies the input? |
| --- | --- | --- | --- | --- |
| `sort()` | Value | Ascending | No | Yes |
| `rsort()` | Value | Descending | No | Yes |
| `asort()` | Value | Ascending | Yes | Yes |
| `arsort()` | Value | Descending | Yes | Yes |
| `ksort()` | Key | Ascending | Yes | Yes |
| `krsort()` | Key | Descending | Yes | Yes |
| `usort()` | Value with your callback | Your callback | No | Yes |
| `uasort()` | Value with your callback | Your callback | Yes | Yes |
| `uksort()` | Key with your callback | Your callback | Yes | Yes |

On current PHP versions, these functions return `true`. They do not return the sorted array.

## Sort a list in ascending or descending order

```php
$fruits = ['Banana', 'Apple', 'Orange'];

sort($fruits);

// ['Apple', 'Banana', 'Orange']
```

Use `rsort()` for descending order:

```php
$scores = [12, 5, 28];

rsort($scores);

// [28, 12, 5]
```

Both functions assign new zero-based numeric keys. That makes them a good fit for lists, not arrays where the original keys carry meaning.

## Preserve associative keys with asort() and arsort()

`asort()` orders by value while keeping each key attached:

```php
$prices = [
    'Apple' => 1.20,
    'Banana' => 0.50,
    'Orange' => 0.90,
];

asort($prices);

// [
//     'Banana' => 0.50,
//     'Orange' => 0.90,
//     'Apple' => 1.20,
// ]
```

Use `arsort()` for the same operation in descending order.

## Sort an associative array by key

Use `ksort()` or `krsort()` when the keys determine the order:

```php
$products = [
    'product3' => 'Chair',
    'product1' => 'Desk',
    'product2' => 'Lamp',
];

ksort($products);

// [
//     'product1' => 'Desk',
//     'product2' => 'Lamp',
//     'product3' => 'Chair',
// ]
```

The values do not change. Only the order of their keys does.

## Do not assign the return value

This is wrong:

```php
$sorted = sort($fruits);

// $sorted is true, not an array.
```

If you need to keep the original array, copy it first:

```php
$sorted = $fruits;
sort($sorted);
```

## Choose the comparison mode with sort flags

Most sorting functions accept a flag as their second argument.

| Flag | Comparison |
| --- | --- |
| `SORT_REGULAR` | PHP's normal comparison rules |
| `SORT_NUMERIC` | Compare values numerically |
| `SORT_STRING` | Compare values as strings |
| `SORT_NATURAL` | Natural order, so `file2` comes before `file10` |
| `SORT_LOCALE_STRING` | Compare using the current locale |
| `SORT_FLAG_CASE` | Combine with `SORT_STRING` or `SORT_NATURAL` for case-insensitive sorting |

Numeric strings are a common reason to set the flag explicitly:

```php
$values = ['20', '3', '100'];

sort($values, SORT_NUMERIC);

// ['3', '20', '100']
```

For file-like names, `natsort()` is easier to read:

```php
$files = ['file10.txt', 'file2.txt', 'file1.txt'];

natsort($files);
$files = array_values($files);

// ['file1.txt', 'file2.txt', 'file10.txt']
```

`natsort()` preserves keys, which is why I used `array_values()` when I wanted a clean list afterward. Use `natcasesort()` when letter case should not affect the natural order.

## Write a custom comparison with usort()

A comparison callback must return:

- a negative integer when the first value should come first
- zero when the values are equal for sorting
- a positive integer when the second value should come first

The spaceship operator makes that compact:

```php
$numbers = [3, 2, 5, 6, 1];

usort($numbers, fn (int $a, int $b): int => $a <=> $b);

// [1, 2, 3, 5, 6]
```

Do not return only `$a > $b`. A boolean cannot describe all three outcomes reliably.

Use `uasort()` instead when the original keys must survive. Use `uksort()` when your callback compares keys rather than values.

## Sort rows by more than one column

Compare the main field first, then use a second comparison when the first one ties:

```php
$users = [
    ['name' => 'Zoe', 'score' => 90],
    ['name' => 'Ana', 'score' => 95],
    ['name' => 'Ben', 'score' => 95],
];

usort($users, function (array $a, array $b): int {
    return ($b['score'] <=> $a['score'])
        ?: ($a['name'] <=> $b['name']);
});

// Ana 95, Ben 95, Zoe 90
```

The score comparison is reversed for descending order. Names use the normal ascending comparison.

## Equal values keep their original order on PHP 8+

PHP's array sorting functions are stable as of PHP 8.0. When the comparison says two values are equal, their previous relative order is retained.

That means the callback above needs a name comparison only if name order is a real requirement. If insertion order is the intended tie-breaker, return `0` and let the stable sort preserve it.

The practical rule is to choose key behavior first, then direction, then a comparison flag or callback. That prevents most sorting surprises before the code runs.

Related guides:

- [Filter arrays and understand preserved keys](/php-array-filter)
- [Reset array keys with array_values()](/php-array-values)
- [Map arrays without changing their shape by accident](/php-array-map)
