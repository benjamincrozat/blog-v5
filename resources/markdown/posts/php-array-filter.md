---
id: "01KKEW27JY66N60P9DHKPF8AM5"
title: "PHP array_filter(): practical examples and gotchas"
slug: "php-array-filter"
author: "benjamincrozat"
description: "Use PHP array_filter() with callbacks, remove empty values carefully, filter by key, and reindex the result when you need clean numeric keys."
categories:
  - "php"
published_at: 2023-11-11T00:00:00+01:00
modified_at: 2026-03-19T09:50:00+00:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-array-filter.png"
sponsored_at: null
---
## Introduction

**Use [`array_filter()`](https://www.php.net/array_filter) when you want to keep only the array values that match a condition.**

PHP passes each value to your callback and keeps the ones for which the callback returns `true`. The two gotchas worth remembering are that omitting the callback removes values like `'0'` and `0`, and the function preserves the original keys unless you reindex the result.

## How to use array_filter() in PHP

```php
array_filter(array $array, ?callable $callback = null, int $mode = 0): array
```

By default, the callback receives the value only.

## Filter values with a callback

Here is the most common example:

```php
$array = [1, 2, 3, 4, 5];

$even = array_filter($array, fn (int $value) => $value % 2 === 0);

print_r($even);
```

This keeps only the even numbers from the original array.

## Remove empty values without a callback

If you call `array_filter()` without a callback, PHP removes values that `empty()` considers empty.

```php
$values = ['foo', false, -1, null, '', '0', 0];

$filtered = array_filter($values);

print_r($filtered);
// ['foo', -1]
```

That behavior is useful, but it can surprise you because `'0'` and `0` are removed too.

If you want to remove only `null` and empty strings while keeping `0`, `'0'`, or `false`, be explicit:

```php
$values = ['foo', false, -1, null, '', '0', 0];

$filtered = array_filter(
    $values,
    fn ($value) => $value !== null && $value !== '',
);

print_r($filtered);
// ['foo', false, -1, '0', 0]
```

That small callback is usually safer than relying on the default behavior when form or request data is involved.

## Filter by key or by both key and value

Use the third argument when the key matters:

```php
$settings = [
    'app_name' => 'Blog',
    'app_env' => 'production',
    'db_host' => '127.0.0.1',
];

$appOnly = array_filter(
    $settings,
    fn (string $key) => str_starts_with($key, 'app_'),
    ARRAY_FILTER_USE_KEY,
);
```

If you need both the value and the key, use `ARRAY_FILTER_USE_BOTH`:

```php
$numbers = ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4];

$filtered = array_filter(
    $numbers,
    fn (int $value, string $key) => $key === 'b' || $value === 4,
    ARRAY_FILTER_USE_BOTH,
);
```

That is the mode to reach for when the filtering rule depends on field names as much as data.

## Keys are preserved

`array_filter()` keeps the original array keys. That is often what you want, but it can leave gaps in numeric indexes:

```php
$array = [1, 2, 3, 4, 5];

$even = array_filter($array, fn (int $value) => $value % 2 === 0);

print_r($even);
// [1 => 2, 3 => 4]
```

If you want a clean zero-based array again, wrap the result in `array_values()`:

```php
$even = array_values($even);
// [2, 4]
```

## Common pitfalls

Your callback must return something truthy or falsy. Forgetting `return` is a classic mistake:

```php
$array = [1, 2, 3, 4, 5];

$even = array_filter($array, function (int $value) {
    $value % 2 === 0;
});

print_r($even);
// []
```

Also, avoid modifying the array from inside the callback. The PHP manual treats that behavior as undefined.

## Conclusion

`array_filter()` is the right tool when you want to keep only matching values, keys, or key-value pairs. The two behaviors worth remembering are that omitting the callback removes all empty values, and that the function preserves keys unless you reindex the result yourself.

If you spend a lot of time shaping arrays before they hit the rest of the app, these are the next reads I would keep nearby:

- [Reset array keys cleanly when the indexes get weird](/php-array-values)
- [Map arrays in PHP without overcomplicating the callback](/php-array-map)
- [Count arrays correctly before you branch on them](/php-array-length)
