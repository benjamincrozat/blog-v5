---
id: "01KKEW27AEB2674XZP8YTGQJNK"
title: "Fix \"Invalid argument supplied for foreach\" in PHP & Laravel"
slug: "invalid-argument-supplied-for-foreach"
author: "benjamincrozat"
description: "Learn why the \"Invalid argument supplied for foreach()\" warning happens, and let me show you multiple ways to fix it."
categories:
  - "laravel"
  - "php"
published_at: 2022-10-07T00:00:00+02:00
modified_at: 2022-11-23T00:00:00+01:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/KYhG89qw2ZuwHXM.jpg"
sponsored_at: null
---
## Why does the "Invalid argument supplied for foreach()" warning occurs?

**The *"Invalid argument supplied for foreach()"* warning occurs when you try to iterate over something other than an array or an object.**

On PHP 8+, the warning has been rewritten to "foreach() argument must be of type array|object".

Whatever version of PHP you're on, you need to ensure that an array or an object is always passed to `foreach`.

## Use the null coalescing operator

When you cannot be certain that you'll get an array or null, you can use the null coalescing operator to ensure an array will always be passed to `foreach`.

```php
foreach ($value ?? [] as $item) {
    …
}
```

## Check if your value is iterable

One of the safest way to go is to use the [`is_iterable()`](https://www.php.net/is_iterable) function. It checks for either:
- An array;
- A [Traversable](https://www.php.net/manual/en/class.traversable.php) object.

```php
if (is_iterable($value)) {
    foreach ($value as $item) {
	    …
    }
}
```

## Use Laravel's collections

If you're using Laravel, you can use [collections](https://laravel.com/docs/collections) to wrap your arrays and work with safer code.

Let's say you're refactoring a poor-quality codebase and have to deal with uncertain return values. Wrapping the return value in the `collect()` helper will ensure that you always get an iterable to loop over.

```php
// The safe collect() helper.
$items = collect(
    // The unsafe method.
    $foo->getItems()
);

// Looping over $items will always work.
foreach ($items as $item) {
    //
}
```

Of course, since you're using Laravel's collections, you could refactor to their built-in methods:

```php
$items = collect(
    $foo->getItems()
);

$items->each(function ($item) {
    //
});
```

If you are still cleaning up the kinds of PHP inputs that turn simple loops into bugs, these are the next reads I would keep nearby:

- [Inspect arrays without wrecking your output](/php-laravel-print-array)
- [Use null coalescing when nested checks start piling up](/php-double-question-mark-null-coalescing-operator)
- [Fix the "$this" error when PHP says you're outside an object](/using-this-when-not-in-object-context)
- [Reset array keys cleanly when the indexes get weird](/php-array-values)
