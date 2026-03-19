---
id: "01KKEW27MAVPMVQX01AK08JW0X"
title: "PHP print array: print_r(), var_dump(), and Laravel helpers"
slug: "php-laravel-print-array"
author: "benjamincrozat"
description: "Print an array in PHP with print_r(), var_dump(), var_export(), or json_encode(), then see when Laravel dump() and dd() are the better fit."
categories:
  - "laravel"
  - "php"
published_at: 2022-10-06T22:00:00Z
modified_at: 2026-03-19T22:58:16Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/yiHA755lsi4DPWO.jpg"
sponsored_at: null
---
## Introduction

If you want to print an array in PHP, the quickest answer is usually [`print_r()`](https://www.php.net/manual/en/function.print-r.php) for readability or [`var_dump()`](https://www.php.net/manual/en/function.var-dump.php) for full debugging detail.

```php
$array = ['foo', 'bar', 'baz'];

print_r($array);
```

But there is no single best function for every situation. Sometimes you want:

- a readable dump in the browser or terminal
- exact types and lengths for debugging
- copy-pasteable PHP output
- JSON output for APIs
- Laravel's friendlier `dump()` or `dd()` helpers

## Quick answer: which PHP function should you use?

| Goal | Best choice | Why |
| --- | --- | --- |
| Read an array quickly | [`print_r()`](https://www.php.net/manual/en/function.print-r.php) | Simple, readable output |
| Inspect types and values deeply | [`var_dump()`](https://www.php.net/manual/en/function.var-dump.php) | Shows types, lengths, and nested structure |
| Export valid PHP code | [`var_export()`](https://www.php.net/manual/en/function.var-export.php) | Output can be pasted back into PHP |
| Emit JSON | [`json_encode()`](https://www.php.net/manual/en/function.json-encode.php) | Useful for APIs and frontend debugging |
| Debug nicely in Laravel | `dump()` | Clean Symfony VarDumper output |
| Dump and stop execution in Laravel | `dd()` | Same as `dump()`, but ends the request |

## Use print_r() when you just want readable output

`print_r()` is the simplest answer for most "how do I print an array in PHP?" questions.

```php
$array = ['foo', 'bar', 'baz'];

print_r($array);
```

Output:

```text
Array
(
    [0] => foo
    [1] => bar
    [2] => baz
)
```

If you are in a browser, wrap it in `<pre>` to keep the formatting readable:

```php
echo '<pre>';
print_r($array);
echo '</pre>';
```

If you need the output as a string instead of printing it directly:

```php
$output = print_r($array, true);
```

## Use var_dump() when the exact values matter

`var_dump()` is better when you need to see data types, string lengths, booleans, or `null` values clearly.

```php
$array = ['count' => 3, 'active' => false, 'name' => null];

var_dump($array);
```

Output:

```text
array(3) {
  ["count"]=>
  int(3)
  ["active"]=>
  bool(false)
  ["name"]=>
  NULL
}
```

This is often the best choice when `print_r()` is too vague.

## Use var_export() when you want valid PHP code

`var_export()` prints a representation you can paste back into a PHP file.

```php
$array = ['foo', 'bar', 'baz'];

var_export($array);
```

Output:

```php
array (
  0 => 'foo',
  1 => 'bar',
  2 => 'baz',
)
```

That makes it useful when you are building fixtures, quick config arrays, or debugging code generation.

## Use json_encode() when JSON is what you actually need

If you are debugging API responses, JavaScript payloads, or structured output, JSON is often easier to work with than `print_r()`.

```php
$array = ['foo', 'bar', 'baz'];

echo json_encode($array);
```

Output:

```json
["foo","bar","baz"]
```

For prettier output:

```php
echo json_encode($array, JSON_PRETTY_PRINT);
```

## Laravel helpers: dump() and dd()

Laravel gives you two especially useful debugging helpers.

### dump()

`dump()` prints the value and keeps the request running.

```php
$array = ['foo', 'bar', 'baz'];

dump($array);
```

This is great when you want to inspect data without stopping the page entirely.

### dd()

`dd()` means "dump and die." It prints the value and stops execution.

```php
$array = ['foo', 'bar', 'baz'];

dd($array);
```

This is often the fastest way to inspect state inside a controller, job, listener, or Blade-driven flow.

## Which one should you choose?

Here is the practical rule of thumb I use:

- use `print_r()` for a quick readable array dump
- use `var_dump()` when type information matters
- use `var_export()` when you want reusable PHP output
- use `json_encode()` when the real target is JSON
- use `dump()` or `dd()` in Laravel because the output is nicer and faster to scan

## FAQ

### What is the simplest way to print an array in PHP?

`print_r($array);`

### How do I print an array without echoing it directly?

Use `print_r($array, true)` to get the output as a string.

### Why is var_dump() so verbose?

Because it shows types and lengths, not just values. That is exactly why it is useful for debugging.

### Should I use dd() in production code?

No. `dd()` is a debugging helper. Remove it before shipping.

## Conclusion

If you only need one quick answer, start with `print_r()` for readability and switch to `var_dump()` when you need more detail. In Laravel, `dump()` and `dd()` are usually even better because the output is easier to scan and they fit the framework's debugging workflow nicely.

If you are still in debugging mode after dumping that array, these are the next reads I would keep nearby:

- [Write PHP messages to your error log when screen output gets in the way](/php-error-log)
- [Show every PHP error when debugging gets vague](/php-show-all-errors)
- [Stop foreach from blowing up on the wrong input](/invalid-argument-supplied-for-foreach)
