---
id: "01KKEW27K5Z5DQSFG6MQB97R8Y"
title: "Convert a PHP array to JSON"
slug: "php-array-to-json"
author: "benjamincrozat"
description: "Convert PHP arrays to JSON with `json_encode()`. Ideal for data exchange, storing data, and API communication."
categories:
  - "php"
published_at: 2023-09-16T00:00:00+02:00
modified_at: 2026-03-12T18:37:53Z
serp_title: "PHP array to JSON: how everyone does it in 2025"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/LXXaukrxEH7DkKW.jpg"
sponsored_at: null
---
## The quickest way to convert a PHP array to JSON

**You want to convert a PHP array to JSON?**
Use [`json_encode()`](https://www.php.net/json_encode). Here’s what works:

```php
$array = [
    "foo" => "bar",
    "baz" => "qux",
];

$json = json_encode($array, JSON_THROW_ON_ERROR);

echo $json; // {"foo":"bar","baz":"qux"}
```

That’s it. You’ve turned your PHP array into a JSON string.

## Why convert a PHP array to JSON?

* **APIs:** Practically all modern APIs expect JSON.
* **Data exchange:** Passing data between PHP and JavaScript, or from backend to frontend? JSON is your bridge.
* **Storage:** Need a human-readable, lightweight format for structured data? Use JSON.
* **Legacy:** Forget XML. JSON won. Stop living in the past.

If you're weighing JSON against PHP-specific formats, my [friendly guide to PHP serialization](/a-friendly-guide-to-php-serialization-that-finally-clicked) will help you decide when each one makes sense.

---

## Catching PHP array to JSON conversion errors

Just calling `json_encode($array)` is amateur hour. Handle errors properly:

```php
try {
    $json = json_encode($array, JSON_THROW_ON_ERROR);
} catch (JsonException $e) {
    exit('JSON encoding error: ' . $e->getMessage());
}
```

Why do this?

* With `JSON_THROW_ON_ERROR`, PHP throws an exception if something goes wrong.
* Debugging silent failures is for people who like pain. Use exceptions.

## Pretty-print your JSON

Readable output matters, especially for debugging. Add the `JSON_PRETTY_PRINT` flag:

```php
$json = json_encode($array, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT);
```

## Common issues with `json_encode`

* **Invalid UTF-8:** Non-UTF-8 strings will make `json_encode` choke.
* **Unsupported types:** You can’t encode resources, closures, or objects that don’t implement `JsonSerializable`.
* **Silent failures:** If you don’t use `JSON_THROW_ON_ERROR`, you must check manually:

```php
$json = json_encode($array);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_last_error_msg();
}
```

But you shouldn’t be writing legacy code. Just use the error flag and catch exceptions.

If you want a faster way to inspect data before encoding it, my [`console.log()` in PHP](/console-log-php) guide covers a few simple debugging options.

## Conclusion

* Use `json_encode($array, JSON_THROW_ON_ERROR)`. Don’t skip the error flag.
* Handle `JsonException` to avoid headaches.
* For readable output, use `JSON_PRETTY_PRINT`.
* Don’t pass garbage (resources, closures) to `json_encode`.

If you are still moving PHP data across boundaries after this, these are the next reads I would keep close:

- [Inspect arrays without wrecking your output](/php-laravel-print-array)
- [Check JSON safely before you trust the payload](/validate-json-in-php-with-json-validate)
- [Understand exceptions before your next try/catch block](/php-exceptions)


## References

* [Official `json_encode` docs](https://www.php.net/json_encode)
* [PHP 8.3 release notes](https://www.php.net/releases/8.3/en.php)
* [MDN: JSON in JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/JSON)
