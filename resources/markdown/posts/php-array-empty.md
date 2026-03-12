---
id: "01KKEW27JVHSV5KW1NNBBHD4K6"
title: "Check if your PHP array is empty"
slug: "php-array-empty"
author: "benjamincrozat"
description: "There are multiple ways to check if an array is empty. Let me tell you about each of them and why and when you should use them."
categories:
  - "php"
published_at: 2022-10-09T00:00:00+02:00
modified_at: 2025-07-10T08:50:00+02:00
serp_title: "Check if your PHP array is empty (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/mDlvLYOuI0LRK29.jpg"
sponsored_at: null
---
## Introduction

**To quickly check whether a PHP array is empty, use the [`empty()`](https://www.php.net/empty) function:**

```php
declare(strict_types=1);

$myArray = [];

// Outputs: bool(true)
var_dump(empty($myArray));

$fruits = ['Apple', 'Banana', 'Orange'];

// Outputs: bool(false)
var_dump(empty($fruits));
```

Using `empty()` is usually the cleanest and fastest method. That being said, it's helpful to understand alternative methods and nuances, especially since PHP 8 introduced stricter type handling.

Here's everything you need to know about reliably checking if your PHP arrays are empty.

## Other reliable methods

### Using the count() function

Another common way to determine if an array is empty is using [`count()`](https://www.php.net/count), which returns the number of elements:

```php
declare(strict_types=1);

$vegetables = ['Carrot', 'Tomato', 'Cucumber'];

// Outputs: int(3)
echo count($vegetables);
```

You can also use `count()` to reliably check if the array is empty by comparing its result to zero:

```php
declare(strict_types=1);

$numbers = [];

if (count($numbers) === 0) {
    // The array is empty.
    echo 'The array is empty.';
}
```

#### Counting multidimensional arrays

`count()` supports counting elements recursively with the `COUNT_RECURSIVE` constant:

```php
declare(strict_types=1);

$nestedArray = [
    'Fruits' => ['Apple', 'Banana'],
    'Vegetables' => ['Carrot'],
];

// Outputs: int(5)
echo count($nestedArray, COUNT_RECURSIVE);
```

### The sizeof() function

[`sizeof()`](https://www.php.net/sizeof) is simply an alias for `count()`. They are functionally identical:

```php
declare(strict_types=1);

$animals = ['Dog', 'Cat', 'Mouse'];

// Outputs: int(3)
echo sizeof($animals);
```

While both work equally well, prefer using `count()` as it's clearer to most developers.

### Using the logical NOT operator (!)

Another concise, though less intuitive, method to check for an empty array is using the logical NOT operator (`!`):

```php
declare(strict_types=1);

$users = [];

if (! $users) {
    // The array is empty.
    echo 'There are no users.';
}
```

This method works well because an empty array evaluates to `false` in a boolean context.

## Important PHP 8 nuance: count() and TypeError

As of PHP 8.0, calling `count()` on a variable that's not an array or Countable object (such as `null`) throws a `TypeError`. Previously, this returned `0` and did not trigger any error.

Here's an example:

```php
declare(strict_types=1);

$value = null;

// PHP 7.4 and below: Returns 0
// PHP 8.0 and above: Throws TypeError
$count = count($value);
```

To avoid this issue, explicitly check if your variable is an array before calling `count()`:

```php
declare(strict_types=1);

$value = null;

if (is_array($value) && count($value) === 0) {
    // Safe way to check if it's an empty array.
    echo 'The array is empty.';
}
```

Read more about this change in the official [RFC: Throw TypeError for internal functions](https://wiki.php.net/rfc/consistent_type_errors).

## Benchmark performance

Here's a quick benchmark summary based on PHP 8.3:

* `empty()` is about 10% faster than `count()`. This doesn't matter for most applications, but there you have it anyway.
* The logical NOT operator (`!`) performs almost identically to `empty()`.

Thus, `empty()` is your fastest, most straightforward option for checking empty arrays.

## Conclusion

In most scenarios, use the `empty()` function to determine if your PHP array is empty. It's also good to know how the alternative methods (`count()`, `sizeof()`, `!`) behave, especially given PHP's evolving type handling.

With these guidelines, you should be able to write solid code.

If you want a few more PHP rabbit holes after this:

- [Understanding array_filter() in PHP](/php-array-filter)
- [Making sense of PHP's array_map() function](/php-array-map)
- [Bring order back to your PHP arrays using array_values()](/php-array-values)

