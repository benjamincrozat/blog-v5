---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/248/01HF08NCKD44TV4K8JJXM4WNB1.jpg
Title: "Understanding array_filter() in PHP"
Description: "See how PHP allows you to filter unwanted values in arrays in a simple and concise way."
Published at: 2023-11-11
Categories: php
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
