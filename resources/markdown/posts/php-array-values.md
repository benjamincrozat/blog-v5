---
id: "01KKEW27K8BSG582BAQ3YAJT9K"
title: "Bring order back to your PHP arrays using array_values()"
slug: "php-array-values"
author: "benjamincrozat"
description: "Discover how array_values() in PHP can help you re-do what has been undone."
categories:
  - "php"
published_at: 2023-11-11T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/a0TQCHHF0o4xrnB.jpg"
sponsored_at: null
---
Talking about PHP, it's hard not to dive into its array handling capabilities. 😅 One particularly handy function is [`array_values()`](https://www.php.net/array_values). Ever found yourself needing to re-index an array? That's where `array_values()` shines.

## What is array_values() in PHP?

In PHP, `array_values()` is a built-in function that returns all the values from an array and indexes them numerically. It's perfect when you're not concerned about the keys but just want the values in a simple, 0-indexed array.

## How does array_values() work?

Imagine you have an associative array where the keys are all over the place. Maybe they're strings, or perhaps they're just not in the order you need. If you only need the values, `array_values()` simplifies this by stripping away the keys and providing a neatly indexed array.

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

Another great use case is when you sort your array alphabetically using the [`sort()`](https://www.php.net/sort) function, which can mess up the order of your key. Let me show you:

```php
$fruits = [
    'Orange',
    'Banana',
    'Apple',
];

sort($fruits);

// [
//     3 => 'Apple',
//     2 => 'Banana',
//     1 => 'Orange',
// ]
var_dump($fruits);
```

As you can see, the numerical keys are not in ascending order anymore. But if you apply `array_values()` to this array, you'll get a nice, clean array back:

```php
array_values($fruits);

// [
//     0 => 'Apple',
//     1 => 'Banana',
//     2 => 'Orange',
// ]
var_dump($fruits);
```

If you want a few more PHP rabbit holes after this:

- [Learn how to sort any kind of array in PHP](/php-array-sort)
- [Understanding array_filter() in PHP](/php-array-filter)
- [Making sense of PHP's array_map() function](/php-array-map)

