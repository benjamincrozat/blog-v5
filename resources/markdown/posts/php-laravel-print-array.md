---
id: "01KKEW27MAVPMVQX01AK08JW0X"
title: "Print an array with PHP (+ Laravel)"
slug: "php-laravel-print-array"
author: "benjamincrozat"
description: "Debugging requires dissecting everything. Here's a list of all the one-line built-in ways to print arrays in PHP (and even Laravel-specific helpers)."
categories:
  - "laravel"
  - "php"
published_at: 2022-10-07T00:00:00+02:00
modified_at: 2023-06-24T00:00:00+02:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/yiHA755lsi4DPWO.jpg"
sponsored_at: null
---
## Introduction to printing arrays in PHP

**There are multiple ways to print the content of an array in PHP like [`var_dump()`](https://www.php.net/var_dump), [`print_r()`](https://www.php.net/print_r), [`var_export()`](https://www.php.net/var_export), and even [`json_encode()`](https://www.php.net/json_encode).**

Let me review each of them in this article.

## Print a PHP array using print_r()

`print_r()` displays arrays in a human-readable format.

Example:

```php
print_r(['Foo', 'Bar', 'Baz']);
```

Output:

```
Array
(
    [0] => Foo
    [1] => Bar
    [2] => Baz
)
```

If you need to capture the output instead of echoing it, you can pass a second parameter to `print_r()`:

```php
$output = print_r(['Foo', 'Bar', 'Baz'], true);
```

### Print a PHP array using var_dump()

`var_dump()` prints information about any type of value. It works great for arrays too!

Example:

```php
var_dump(['Foo', 'Bar', 'Baz']);
```

Output:

```
array(3) {
  [0]=>
  string(3) "Foo"
  [1]=>
  string(3) "Bar"
  [2]=>
  string(3) "Baz"
}
```

You can also print an infinite number of variables at once:

```php
var_dump($foo, $bar, $baz, …);
```

### Print a PHP array using var_export()

`var_export()` prints a parsable string representation of a variable that you could just copy and paste into your source code.

Example:

```php
$array = ['Foo', 'Bar', 'Baz'];

var_export($array);
```

Output:

```
array (
  0 => 'Foo',
  1 => 'Bar',
  2 => 'Baz',
)
```

### Print a PHP array using json_encode()

`json_encode()` can print arrays as JSON.

Example:

```php
$array = ['Foo', 'Bar', 'Baz'];

echo json_encode($array);
```

Output:

```
["Foo","Bar","Baz"]
```

### Print a PHP array using Laravel's dump() function

![Screenshot of dump() in action.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/php-laravel-print-array-00a6a84cb1df0664dd56.jpg/public){: width="400"}

The `dump()` function prints in detail arrays containing any value.

```php
$array = ['Foo', 'Bar', 'Baz'];

dump($array);
```

And just like `var_dump()`, it accepts an infinite number of arguments:

```php
dump($a, $b, $c, $d, $e, …);
```

### Print a PHP array using Laravel's dd() function

The `dd()` function does the same thing as `dump()`, but stops code execution.

```php
$array = ['Foo', 'Bar', 'Baz'];

dd($array);
```

It also accepts an infinite number of arguments:

```php
dd($a, $b, $c, $d, $e, …);
```

If you are still in debugging mode after dumping that array, these are the next reads I would keep nearby:

- [Write PHP messages to your error log when screen output gets in the way](/php-error-log)
- [Show every PHP error when debugging gets vague](/php-show-all-errors)
- [Stop foreach from blowing up on the wrong input](/invalid-argument-supplied-for-foreach)
