---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/12/confused_xxboi4.jpg
Title: "The fastest way to check if your PHP array is empty"
Description: "There are multiple ways to check if an array is empty. Let me tell you about each of them and why and when you should use them."
Published at: 2022-10-09
Modified at: 2023-11-02
Categories: php
---

## The fastest way to check if an array is empty

**To check whether a PHP array is empty or not, use the [`empty()`](https://www.php.net/empty) function:**

```php
$foo = [];

// true
var_dump(empty($foo));

$bar = ['Foo', 'Bar', 'Baz'];

// false
var_dump(empty($bar));
```

This is my favorite way of doing it. But there are other methods to check if an array is empty such as:

1. Using the [`count()`](https://www.php.net/count) (or [`sizeof()`](https://www.php.net/sizeof)) function to count the number of elements in the array and check if it's equal to zero. `count()` can even count the numbers of entries inside a multidimensional array.
2. Using the not operator (`!`). If the array does hold any value, the not operator will return `true`.

You can stop there, or you can dive deeper and see in detail to use these functions to check for empty arrays.

## Other ways to check if an array is empty

### The count() function

Another way to check if your array is empty is to use the [`count()`](https://www.php.net/count) function. The function returns an integer depending on the number of items inside, or zero if it's empty.

You can even use it with [Countable](https://www.php.net/manual/en/class.countable.php) objects.

```php
echo count(['Foo', 'Bar', 'Baz']);
```

For multidimensional arrays, there's a second parameter for which you can use the `COUNT_RECURSIVE` constant to recursively count the numbers of items.

```php
$array = [
    'Foo' => [
        'Bar' => ['Baz'],
    ],
];

// 3
$count = count($array, COUNT_RECURSIVE);

// If $count is greater than zero.
if ($count > 0) {
    // The array is not empty.
} else {
    // The array is empty.
}
```

Learn more about the [`count()`](https://www.php.net/count) function.

### The sizeof() function

[`sizeof()`](https://www.php.net/sizeof) is an alias of count() and can be used on arrays in the same way. [PHP actually has a lot of aliases for various functions](https://www.php.net/manual/en/aliases.php).

There's nothing to add, [you already know how to use it](#the-count-function):

```php
echo sizeof(['Foo', 'Bar', 'Baz']);
```

Learn more about the [`sizeof()`](https://www.php.net/sizeof) function.

### The not (!) operator

One not super intuitive way to check if your array is not empty is to use the not operator (`!`).

I had no clue it could check for empty arrays. But here I am, after more than 15 years of PHP, learning yet another basic thing. 😅

```php
$foo = [];

if (! $foo) {
    echo '$foo is empty.';
}
```
