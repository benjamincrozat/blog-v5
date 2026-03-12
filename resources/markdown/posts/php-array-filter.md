---
id: "01KKEW27JY66N60P9DHKPF8AM5"
title: "Understanding array_filter() in PHP"
slug: "php-array-filter"
author: "benjamincrozat"
description: "See how PHP allows you to filter unwanted values in arrays in a simple and concise way."
categories:
  - "php"
published_at: 2023-11-11T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/wMRMazWfWw2CTos.jpg"
sponsored_at: null
---
## Introduction to array_filter()

[`array_filter()`](https://www.php.net/array_filter) is a powerful function in PHP. It allows you to filter elements of an array using a callable (a closure for instance). Let me guide you in using this super handy function.

## Basic usage of array_filter()

`array_filter()` works by passing each element of an array through a callback function. If this function returns `true`, the element is included in the resulting array. This is particularly useful when you need to sift through data and only keep elements that meet certain conditions.

Here’s a simple example:

```php
$array = [1, 2, 3, 4, 5];

$even = array_filter($array, fn ($value) => $value % 2 == 0);

print_r($even);
```

In this snippet, `array_filter()` retains only the even numbers from the original array.

## Advanced usage of array_filter()

Beyond simple filters, `array_filter()` can be used in more complex scenarios. For instance, you can filter an array of objects based on the properties of those objects. It's also possible to use it with associative arrays, filtering by key as well as value.

## Common pitfalls when using array_filter()

When using `array_filter()`, remember that the callback function must return `true` or `false`.

```php
$array = [1, 2, 3, 4, 5];

$even = array_filter($array, function ($value) {
    $value % 2 == 0;
});

// []
print_r($even);
```

If you don't, the resulting array will be empty.

To finish this up, another common mistake is forgetting that array keys are preserved. This might lead to unexpected gaps in the numeric array indexes:

```php
$array = [1, 2, 3, 4, 5];

$even = array_filter($array, fn ($value) => $value % 2 == 0);

// Array
// (
//     [1] => 2
//     [3] => 4
// )
print_r($even);
```

If you want a few more PHP rabbit holes after this:

- [Bring order back to your PHP arrays using array_values()](/php-array-values)
- [Making sense of PHP's array_map() function](/php-array-map)
- [Check if your PHP array is empty](/php-array-empty)

