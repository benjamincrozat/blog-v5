---
id: "01KKEW27JVHSV5KW1NNBBHD4K6"
title: "How to get array length in PHP with count()"
slug: "php-array-length"
author: "benjamincrozat"
description: "Get array length in PHP with count(), know when sizeof() or COUNT_RECURSIVE helps, and avoid the Countable and TypeError edge cases."
categories:
  - "php"
published_at: 2022-10-08T22:00:00+00:00
modified_at: 2026-03-18T21:02:00+00:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-array-length.png"
sponsored_at: null
---
## Introduction

**To get the length of an array in PHP, use [`count()`](https://www.php.net/count).**

```php
$frameworks = ['Laravel', 'Symfony', 'CodeIgniter'];

echo count($frameworks);

// 3
```

That is the fast answer most searchers want.

The useful part starts right after that:

- `sizeof()` is just an alias of `count()`
- multidimensional arrays need care with `COUNT_RECURSIVE`
- `count()` also works on `Countable` objects, not just arrays
- checking whether an array is empty is related, but not the same question

This guide focuses on those real-world edge cases instead of stopping at one line of syntax.

## The normal way to get array length in PHP

[`count()`](https://www.php.net/count) returns the number of elements in an array:

```php
$vegetables = ['Carrot', 'Tomato', 'Cucumber'];

echo count($vegetables);

// 3
```

It counts elements, not the highest numeric key:

```php
$numbers = [];
$numbers[5] = 'a';
$numbers[99] = 'b';

echo count($numbers);

// 2
```

That distinction matters because PHP arrays are ordered maps, not fixed-length lists.

## `count()` vs `sizeof()`

[`sizeof()`](https://www.php.net/sizeof) is simply an alias of `count()`:

```php
$animals = ['Dog', 'Cat', 'Mouse'];

echo count($animals);
echo sizeof($animals);

// 3
// 3
```

They do the same thing. I would still use `count()` in day-to-day code because it is clearer to most PHP developers and matches the language in the manual.

## Multidimensional arrays and `COUNT_RECURSIVE`

This is where people often expect one behavior and get another.

By default, `count()` returns only the number of top-level elements:

```php
$nested = [
    'fruits' => ['apple', 'banana'],
    'vegetables' => ['carrot'],
];

echo count($nested);

// 2
```

If you pass `COUNT_RECURSIVE`, PHP counts the nested items too:

```php
echo count($nested, COUNT_RECURSIVE);

// 5
```

That `5` can surprise people the first time.

Why not `3`? Because `COUNT_RECURSIVE` includes:

- the 2 top-level keys
- the 3 nested values

So it is not “how many leaf values are in this structure?” It is “how many elements are in the whole nested array tree?”

If you need a true leaf count or a depth-limited count, `COUNT_RECURSIVE` is usually too blunt.

## `count()` also works on Countable objects

The PHP manual notes that `count()` works on arrays and on objects that implement [`Countable`](https://www.php.net/manual/en/class.countable.php).

For example:

```php
$iterator = new ArrayIterator([1, 2, 3]);

echo count($iterator);

// 3
```

This matters because sometimes the real problem is not “array length” but “countable length.”

If the value might be either an array or a Countable object, `count()` still does the right thing.

## Invalid values, `TypeError`, and `is_countable()`

As of PHP 8.0, calling `count()` on something that is neither an array nor a Countable object throws a `TypeError`.

Example:

```php
$value = null;

count($value);
```

If that input is uncertain, use [`is_countable()`](https://www.php.net/manual/en/function.is-countable.php) first:

```php
$value = null;

if (is_countable($value)) {
    echo count($value);
} else {
    echo 0;
}
```

That works for arrays and Countable objects.

## What if you only want to know whether the array is empty?

This is the closest sibling question, and it is where the old version of this article started.

If your real goal is just “does this array have anything in it?”, these are the two common checks:

```php
$items = [];

var_dump(count($items) === 0);
var_dump(empty($items));

// true
// true
```

Use `count()` when the number itself matters.

Use `empty()` when you only care whether the array has any values at all.

That is also why `empty()` is not a real replacement for “array length.” It answers a different question.

If that comparison is what you are really fighting with, [this `isset()` guide](/php-isset) is a useful companion because it covers `isset()`, `empty()`, and `??` side by side.

## Common misunderstandings worth avoiding

### `count()` is not string length

This sounds obvious, but the query shows up often enough to be worth saying: use `count()` for arrays and `strlen()` for strings.

```php
$tags = ['php', 'laravel'];
$text = 'php';

echo count($tags); // 2
echo strlen($text); // 3
```

### Sparse keys do not change the count

```php
$values = [2 => 'a', 10 => 'b'];

echo count($values);

// 2
```

If you need clean zero-based keys afterward, [array_values()](/php-array-values) is the next function to reach for.

### Recursive arrays can emit warnings

If an array contains itself, `count($array, COUNT_RECURSIVE)` can emit a recursion warning.

That is rare in everyday app code, but it is a good reason not to treat recursive counts as a magic answer for every nested structure.

## Conclusion

For plain PHP arrays, the answer is simple: use `count()` to get the length. The real value is knowing the edge cases around `sizeof()`, `COUNT_RECURSIVE`, sparse keys, Countable objects, and invalid inputs that throw on PHP 8+.

If you are still straightening out the small array habits that make PHP code easier to trust, these are the next reads I would open:

- [Filter PHP arrays cleanly without awkward loops](/php-array-filter)
- [Map arrays in PHP without overcomplicating the callback](/php-array-map)
- [Reset array keys cleanly when the indexes get weird](/php-array-values)
