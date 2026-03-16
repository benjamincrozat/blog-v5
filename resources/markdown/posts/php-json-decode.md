---
id: "01KKV90580W2NP6VWBSYGZQDSE"
title: "PHP json_decode(): practical examples and safer defaults"
slug: "php-json-decode"
author: "benjamincrozat"
description: "Decode JSON in PHP with json_decode(), choose arrays vs objects, catch errors with JSON_THROW_ON_ERROR, and avoid null, depth, and bigint surprises."
categories:
  - "php"
published_at: 2026-03-16T12:28:00Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-json-decode.png"
sponsored_at: null
---
## Introduction

**Use [`json_decode()`](https://www.php.net/json_decode) when you need to turn a JSON string into a PHP value.**

For most API payloads, the safest practical default is:

```php
$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
```

That gives you associative arrays instead of objects, and it throws a `JsonException` when the JSON is invalid instead of quietly returning `null`.

Here is a real example:

```php
$json = '{"name":"Ben","roles":["admin","editor"]}';

$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

echo $data['name'];
echo $data['roles'][0];

// Ben
// admin
```

The confusing part is that `json_decode()` is flexible enough to return arrays, objects, strings, numbers, booleans, or `null`. This guide focuses on the choices and edge cases that actually trip people up.

## How to use `json_decode()` in PHP

The signature looks like this:

```php
json_decode(
    string $json,
    ?bool $associative = null,
    int $depth = 512,
    int $flags = 0
): mixed
```

The four arguments that matter are:

- `$json`: the JSON string you want to decode
- `$associative`: `true` for associative arrays, `false` for objects
- `$depth`: the maximum nesting depth
- `$flags`: options such as `JSON_THROW_ON_ERROR` and `JSON_BIGINT_AS_STRING`

The PHP manual also notes that [`json_decode()`](https://www.php.net/json_decode) only works with UTF-8 encoded strings.

## The best default for most apps

If you are decoding JSON from an API, a queue, a webhook, or a config-like payload, this is a strong default:

```php
try {
    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    report($e);
}
```

Why this version?

- `true` gives you associative arrays, which are often simpler to inspect and transform in PHP
- `JSON_THROW_ON_ERROR` avoids silent failures
- `512` is the normal default depth, and being explicit makes the call easier to read later

If you only need to check whether JSON is valid and do not need the decoded result yet, [`json_validate()`](/validate-json-in-php-with-json-validate) is a better fit.

## Associative arrays vs objects

This is the first thing most developers stumble over.

### Decode JSON objects as PHP objects

If you omit the second argument, JSON objects are returned as `stdClass` objects by default:

```php
$json = '{"name":"Ben","roles":["admin","editor"]}';

$data = json_decode($json);

echo $data->name;
echo $data->roles[0];

// Ben
// admin
```

This is fine if you prefer property access or you are passing the decoded value into code that expects objects.

### Decode JSON objects as associative arrays

If you pass `true` as the second argument, JSON objects become associative arrays:

```php
$json = '{"name":"Ben","roles":["admin","editor"]}';

$data = json_decode($json, true);

echo $data['name'];
echo $data['roles'][0];

// Ben
// admin
```

This is usually the easier option when you need to merge, filter, map, or reshape the data with normal PHP array functions.

If that is how you usually work, [array_map()](/php-array-map) and [array_filter()](/php-array-filter) pair nicely with `json_decode()`.

### Which one should you pick?

Here is the short version:

| Output style | Good default when | Access style |
| --- | --- | --- |
| object | you want property access or object-like semantics | `$data->name` |
| associative array | you want to transform data with PHP array functions | `$data['name']` |

What matters most is being explicit. Do not make future-you guess whether the result is an object or an array.

## Common `json_decode()` examples

### Decode a JSON list

JSON arrays already decode to PHP arrays:

```php
$json = '["php","laravel","mysql"]';

$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

print_r($data);

// ['php', 'laravel', 'mysql']
```

Even if you pass `false`, the outer JSON array still becomes a PHP array. The `associative` argument mainly changes how JSON **objects** are handled.

### Decode JSON into an object graph

Sometimes object access reads a little better:

```php
$json = '{"user":{"name":"Ben","role":"admin"}}';

$data = json_decode($json, false, 512, JSON_THROW_ON_ERROR);

echo $data->user->role;

// admin
```

### Preserve large integers as strings

This one matters more than people realize when the JSON contains IDs, invoice numbers, or anything that must not lose precision.

```php
$json = '{"number":12345678901234567890}';

$default = json_decode($json, true);
$safe = json_decode($json, true, 512, JSON_BIGINT_AS_STRING);

var_dump($default['number']);
var_dump($safe['number']);
```

Output:

```php
float(1.2345678901234567E+19)
string(20) "12345678901234567890"
```

If exact digits matter, add `JSON_BIGINT_AS_STRING`.

## Why did `json_decode()` return `null`?

This is the most common debugging question around the function.

There are two very different reasons:

1. The JSON really is the literal `null`
2. Decoding failed

These two cases look the same if you are not using `JSON_THROW_ON_ERROR`.

### Valid JSON literal `null`

```php
var_dump(json_decode('null'));

// NULL
```

That is valid JSON, so the result is genuinely `null`.

### Invalid JSON also returns `null` without the error flag

```php
$json = '{name: "Ben"}';

var_dump(json_decode($json));
var_dump(json_last_error_msg());

// NULL
// "Syntax error"
```

That is why `JSON_THROW_ON_ERROR` is such a good default: it removes the ambiguity.

## Safer error handling with `JSON_THROW_ON_ERROR`

Instead of checking `json_last_error()` after every call, let PHP throw a `JsonException` for you.

```php
try {
    $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    echo $e->getMessage();
}
```

With invalid JSON, you get a real exception instead of a vague `null`.

If you need a refresher on exception handling itself, [this PHP exceptions guide](/php-exceptions) is a good companion piece.

## Common decoding failures and fixes

### Single quotes and trailing commas are not valid JSON

This is a classic source of confusion when JSON is hand-written:

```php
// Invalid JSON
$json = "{'name':'Ben',}";
```

JSON requires double quotes around keys and string values, and it does not allow trailing commas.

Valid version:

```php
$json = '{"name":"Ben"}';
```

### Depth errors

The default depth is `512`, which is enough for normal payloads. If the nesting is deeper than the limit, decoding fails.

```php
$json = '{"items":[{"a":{"b":{"c":1}}}]}';

var_dump(json_decode($json, true, 3));
var_dump(json_last_error_msg());

// NULL
// "Maximum stack depth exceeded"
```

If you pass an invalid depth such as `0`, PHP throws a `ValueError` in PHP 8.0+.

### Invalid UTF-8 input

The PHP manual states that [`json_decode()`](https://www.php.net/json_decode) only works with UTF-8 strings. If the incoming payload is badly encoded, decoding can fail.

The relevant flags from the JSON constants page are:

- `JSON_INVALID_UTF8_IGNORE`
- `JSON_INVALID_UTF8_SUBSTITUTE`

Use them carefully. They can help when you are dealing with messy external data, but they also change the original input.

## `json_decode()` vs `json_validate()`

Use `json_decode()` when you need the parsed result.

Use [`json_validate()`](/validate-json-in-php-with-json-validate) when you only need to know whether the string is valid JSON and want to avoid parsing it twice.

That rule keeps the code both clearer and cheaper.

## Conclusion

`json_decode()` is straightforward once you make two decisions early: should JSON objects become arrays or objects, and do you want silent failures or exceptions? For most day-to-day PHP work, `json_decode($json, true, 512, JSON_THROW_ON_ERROR)` is a solid default.

If you are still working through JSON handling after this, these are the next reads I would keep nearby:

- [Encode PHP arrays as JSON without silent failures](/php-array-to-json)
- [Validate JSON first when decoding would be wasted work](/validate-json-in-php-with-json-validate)
- [Handle JsonException and other failures more cleanly](/php-exceptions)
