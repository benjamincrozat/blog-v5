---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/246/BWD3BRHnM9PK1xU7BlmRpUaaDzESn1-metac29ydGluZy5qcGc%3D-.jpg
Title: "Learn how to sort any kind of array in PHP"
Description: "Let me walk you through some of the most useful functions in PHP that will enable you to sort any kind of array."
Published at: 2023-11-09
Categories: php
---

Sorting arrays is a common task in PHP, and the language provides a variety of functions to order elements just the way you need.

Whether you're dealing with numerical indices or associative arrays, PHP has you covered. 😎

Let me show you some of PHP's array sorting capabilities.

## Utilizing the sort() and rsort() Functions

When you have a simple indexed array that needs reordering, `sort()` and `rsort()` are the straightforward choices for ascending and descending order, respectively.

For `sort()`:

```php
$fruits = ["Banana", "Apple", "Orange"];

sort($fruits);

var_dump($fruits);
```

Once sorted, the `$fruits` array will be:

```php
["Apple", "Banana", "Orange"]
```

Now, what to do when you have an associative array? The `sort()` function won't cut it.

## Maintaining key association with asort() and arsort()

Associative arrays require maintaining the relationship between keys and values. `asort()` sorts in ascending order by value, maintaining key association, and `arsort()` does the same in descending order.

Example of `asort()`:

```php
$prices = [
    "Apple" => 1.2,
    "Banana" => 0.5,
    "Orange" => 0.9,
];

asort($prices);

var_dump($prices);
```

The sorted `$prices` array now looks like this:

```php
[
    "Banana" => 0.5,
    "Orange" => 0.9,
    "Apple" => 1.2,
]
```

## Key-centric sorting with ksort() and krsort()

To sort an associative array by its keys, `ksort()` for ascending order and `krsort()` for descending order are your go-to functions.

Using `ksort()`:

```php
$products = [
    "product3" => "Chair",
    "product1" => "Desk",
    "product2" => "Lamp",
];

ksort($products);

var_dump($products);
```

## Custom sorting with usort(), uasort(), and uksort()

What we saw until now will probably cover 99% of your needs.

But sometimes, you need a sorting logic that's not built-in. For these instances, `usort()` for value-based, `uasort()` for value-based with preserved keys, and `uksort()` for key-based custom sorting. Each requires a user-defined comparison function.

An example with `usort()`:

```php
$numbers = [3, 2, 5, 6, 1];

usort($numbers, function ($a, $b) {
    return $a <=> $b; // The spaceship operator
});

var_dump($numbers);
```

With the custom function, `$numbers` will be sorted as:

```php
[1, 2, 3, 5, 6];
```

Of course, in that case, using `sort()` would be better. I just wanted to let you know that custom sorting logic is possible! 🙂
