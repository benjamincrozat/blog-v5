---
id: "01KKEW27JNW86X1D1J2A25AB7D"
title: "An early look at PHP 8.6's new features"
slug: "php-86"
author: "benjamincrozat"
description: "PHP 8.6 is already taking shape. I track its release date, confirmed features like partial function application and clamp(), plus RFCs that might still land."
categories:
  - "php"
published_at: 2025-12-07T18:33:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBX0YY1ERYXHAV4T6YPFE21R.jpeg"
sponsored_at: null
---
Right now, PHP 8.6 is an “upcoming release” and it is expected to land toward the end of 2026. 

In this post, I keep track of what PHP 8.6 brings on top of 8.5, starting with the first accepted RFCs:

* Partial Function Application (PFA)
* `clamp()` v2

I will update this post as more RFCs are accepted for 8.6.

## PHP 8.6 release date and status

Here is what we know today:

- **Version status:** upcoming release, active development branch.   
- **Timeline:** current plan is “towards the end of 2026,” not a fixed GA date.   
- **Releases:** there are no alpha, beta, RC, or GA tags yet.   

So 8.6 is still in “language design” mode. Accepted RFCs define the feature set; implementation and alphas will follow.

If you care about getting ready early (or you write libraries), it is worth following the RFC list and testing snapshots as soon as they appear.

## New in PHP 8.6 (so far)

At the time of writing, PHP 8.6 has **two accepted RFCs**:

- [Partial Function Application (v2)](https://wiki.php.net/rfc/partial_function_application_v2)   
- [`clamp()` v2](https://wiki.php.net/rfc/clamp_v2)   

There is also a **True Async** RFC that targets PHP 8.6 but is still under discussion, not accepted yet. 

Let’s look at what you can actually use once 8.6 lands.

## Partial Function Application in PHP 8.6

PHP 8.6 adds *partial function application* (PFA). Instead of writing tiny one-off closures everywhere, you can write the call you want and mark the “missing” arguments with placeholders.   

Very quick example:

```php
function json_response(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
}

// Pre-configure a 200 OK responder:
$jsonOk = json_response(?, 200);

$jsonOk(['ok' => true]);
$jsonOk(['user' => $user]);
````

`json_response(?, 200)` returns a closure that behaves like a normal function, but you didn’t have to write `fn (array $data) => json_response($data, 200)` by hand.

That’s the core idea. If you want the full tour (placeholders, named arguments, pipes, real-world use cases, and edge cases), I broke it down here:

👉 [Partial Function Application in PHP 8.6, made easy](https://benjamincrozat.com/partial-function-application-php-86).

## `clamp()` v2: a built-in range guard

The second accepted feature is `clamp()` v2. It gives you a native function to force a value into a given range.

Conceptually:

```php
$result = clamp($value, $min, $max);
```

* If `$value` is between `$min` and `$max`, you get `$value`.
* If `$value` is lower than `$min`, you get `$min`.
* If `$value` is higher than `$max`, you get `$max`.
* If `$min > $max`, PHP throws a `ValueError` (the bounds are invalid).

This is the classic clamp you see in engines and math libraries.

### Why it matters in practice

Most of us already have this in userland somewhere:

```php
function clamp_userland(float $num, float $min, float $max): float
{
    if ($num < $min) {
        return $min;
    }

    if ($num > $max) {
        return $max;
    }

    return $num;
}
```

But the built-in `clamp()` is:

* **Faster** than the common `min(max())` combo, because it is specialized and does not need to deal with variadic inputs.
* **Standardized**, so every project can agree on the same semantics.
* **Type-checked** and error-prone cases like inverted bounds are handled for you.

A few places where this becomes nice:

#### Score or percentage normalization

```php
$score = clamp($score, 0, 100);
```

#### Rate limiting or retry delays

```php
$delaySeconds = clamp($delaySeconds, 1, 60);
sleep($delaySeconds);
```

#### UI inputs (sliders, zoom levels, pagination)

```php
$page = (int) ($_GET['page'] ?? 1);
$page = clamp($page, 1, $maxPage);
```

Nothing life-changing, but it cleans up a pattern that shows up everywhere.

## What else might land in PHP 8.6?

Only **Partial Function Application** and **`clamp()` v2** are accepted for 8.6 today.

Other things are being discussed with 8.6 as the target version but are not guaranteed at all:

* **True Async**: a big RFC to bring native asynchronous programming to PHP core, with `spawn`, `await`, coroutines, and structured concurrency.
* Various smaller proposals (new casts, APIs like a poll() wrapper, enum helpers, license changes) that could end up in 8.6 or get pushed to 9.0.

The important bit: until an RFC is **accepted** and tagged with “Version: PHP 8.6”, it is only a candidate.

For an up-to-date list, PHP.Watch keeps a dedicated PHP 8.6 RFC page: it lists accepted, under discussion, and declined proposals.

## How to try PHP 8.6 as soon as builds exist

Once 8.6 nightlies and alphas appear, you will usually have three options:

1. **Compile from source**
   Clone `php-src` and build the `PHP-8.6` branch following the instructions on the official downloads page.

2. **Docker images**
   The official `php` images on Docker Hub will eventually expose `8.6-rc` and `8.6-dev` tags, similar to `8.5-rc` today.

3. **Homebrew tap on macOS**
   If you use the `shivammathur/php` tap, expect a `php@8.6` formula once the first RCs appear, just like for 8.5. (You already know the dance: `brew install` then `brew link`.)

Until those exist, you can still experiment with PFA semantics by targeting the branch that implements the RFC or by using tools like PHPStan and PHP_CodeSniffer once they add 8.6 stubs.

## FAQ

### When is PHP 8.6 coming out?

The current plan is “towards the end of 2026.” There is no exact GA date yet, but PHP typically ships one feature release per year near November.

### What are the confirmed features?

Right now:

* Partial Function Application (v2)
* `clamp()` v2

Everything else is still a proposal.

For a deep dive into PFA with practical examples, read:
[Partial Function Application in PHP 8.6, made easy](https://benjamincrozat.com/partial-function-application-php-86).