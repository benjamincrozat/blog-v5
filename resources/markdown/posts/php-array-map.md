---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/223/D9jrbMlts0BBtZ0U3ViydhJtM5CuQz-metaNDI4MzUuanBn-.jpg
Title: "Making sense of PHP's array_map() function"
Description: "PHP's array_map() is an extremely useful function that will help you write better code. Let me demystify it for you."
Published at: 2023-11-04
Categories: php
---

## About array_map()

The [`array_map()`](https://www.php.net/array_map) function in PHP is extremely useful to write leaner code when transforming arrays and avoid lengthier loops with temporary variables and an additional indentation level.

When I was inexperienced with PHP, I had a hard time understanding the `array_map()` function. Now, things have changed, so let me desmystify this super handy function for you!

## How to use array_map() in PHP

The `array_map()` takes a callable as its first parameter, and the array we want to transform as its second parameter. It then returns the a fresh array that has freshly been transformed.

Now, imagine we have an array of prices that we want to apply a discount to.

Before `array_map()`, we would use a code that looks like this:

```php
function discount($price)
{
	return $price * (1 - 0.2);
}

$prices = [
	10, 20, 30, 40, 50,
];

$discounted_prices = [];

foreach ($prices as $price)
{
	$discounted_prices[] = discount($price);
}
```

This is good, but we can do better thanks to `array_map()`:

```php
function discount($price)
{
	return $price * (1 - 0.2);
}

$prices = [
	10, 20, 30, 40, 50,
];

$discounted_prices = array_map(discount(...), $prices);
```

`$discounted_prices` now contains:

```php
[
	8,
	16,
	24,
	32,
	40,
];
```

Also, did you know that `array_map()` can also accept a closure (or anonymous function)?

```php
$prices = [
	10, 20, 30, 40, 50,
];

$discounted_prices = array_map(function ($price) {
	return $price * (1 - 0.2);
}, $prices);
```

But wait, did you really think this is it? Why not use the arrow syntax on our closure?

```php
$prices = [
	10, 20, 30, 40, 50,
];

$discounted_prices = array_map(
	fn ($price) => $price * (1 - 0.2),
	$prices
);
```

There you have it! Using `array_map()` can drastically help improving your code. Now, you can take a look at other similar functions such as [`array_filter()`](https://www.php.net/array_filter) or [`array_reduce()`](https://www.php.net/array_reduce).

## One more thing about array_map()

Ha! You thought that was really it, right? No! `array_map()` is such a useful function to use in PHP!

So, what you probably didn't know is that it can accept an infinite amount of arrays to loop through. Here's an example:

```php
$products = [
	'Apple Watch',
	'iMac',
	'iPhone',
	'Mac Studio',
	'MacBook Pro',
];

$prices = [
	10, 20, 30, 40, 50,
];

$discounted_products = array_map(function ($product, $price) {
	return [
		'product' => $product,
		'discounted_price' => $price * (1 - 0.2)
	];
}, $products, $prices);
```

In this block of code, we build a unique multidimensional array out of two dinstinct ones and apply the discount.

```php
[
	[
		'product' => 'Apple Watch',
		'discounted_price' => 8,
	],
	…
]
```
