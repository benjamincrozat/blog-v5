---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/245/9dEdV3JeGO4NWMY9bKIHIwyq8bLocm-metacGhwLWV4cGxvZGUuanBn-.jpg
Title: "The essentials of explode() in PHP"
Description: "Learn the ins and outs of PHP's explode() function for splitting strings into arrays."
Published at: 2023-11-08
Categories: php
---

PHP's [`explode()`](https://www.php.net/explode) function is a go-to solution for splitting strings into arrays using a specified delimiter.

Let's say that you have a string of comma-separated words. You can use `explode()` to split the string into an array of individual values. 

In this article, I will show you in-depth how to use it.

## How does explode() work?

```php
array explode(string $delimiter, string $string[, int $limit ])
```

- **$delimiter**: The string boundary for splitting.
- **$string**: The input string.
- **$limit**: This parameter is optional and represents the maximum number of elements to return. A positive value sets the size; a negative value excludes the last segments; zero entails no limit. Honestly, I never used this one. 🤷‍♂️

## A practical example for explode()

I honestly don't know what to write here, because using `explode()` is straightforward!

Given a string of devices:

```php
$devices = explode(
    ", ", 
    "apple tv, apple watch, imac, iphone, macbook pro"
);

// array(5) {
//   [0]=>
//   string(8) "apple tv"
//   [1]=>
//   string(11) "apple watch"
//   [2]=>
//   string(4) "imac"
//   [3]=>
//   string(6) "iphone"
//   [4]=>
//   string(11) "macbook pro"
// }
var_dump($devices);
```

The output is an array of individual devices. Now, you can store them in a database for example.

## A few notes about explode()

Be cautious when choosing your delimiter for the `explode()` function. If you use a delimiter that doesn’t exist in the input string, `explode()` will simply return an array containing the entire original string as a single element. Always double-check your input to ensure you're getting the expected results.

For tasks that require the reverse operation (converting an array back into a string) turn to PHP's [`implode()`](https://www.php.net/implode) function. And if you need to break down a string into individual characters, [`str_split()`](https://www.php.net/str_split) is the function that offers that fine-grained control.
