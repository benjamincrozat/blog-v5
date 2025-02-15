---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/250/01HF1NKCB4P7NCJEP2XZJ3WZ53.jpg
Title: "Bring order back to your PHP arrays using array_values()"
Description: "Discover how array_values() in PHP can help you re-do what has been undone."
Published at: 2023-11-11
Categories: php
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
