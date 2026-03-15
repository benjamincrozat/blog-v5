---
id: "01KKEW27JNW86X1D1J2A25AB7D"
title: "PHP 8.6 features and release date"
slug: "php-86"
author: "benjamincrozat"
description: "PHP 8.6 is scheduled for November 19, 2026. Here are the confirmed features already landed, the accepted RFCs still pending, and the proposals still worth watching."
categories:
  - "php"
published_at: 2025-12-07T18:33:00+01:00
modified_at: 2026-03-15T19:53:56Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBX0YY1ERYXHAV4T6YPFE21R.jpeg"
sponsored_at: null
---
As of March 15, 2026, PHP 8.6 is still the next yearly release after [PHP 8.5](https://benjamincrozat.com/php-85).

If you are checking PHP 8.6 features right now, the cleanest way to separate confirmed changes from wishlists is to combine the official [PHP 8.6 preparation page](https://wiki.php.net/todo/php86) with the live RFC pages and the current [PHP.Watch 8.6 tracker](https://php.watch/versions/8.6/rfcs).

This post keeps that split simple: what is already in the 8.6 branch, what is accepted but not landed yet, and what still only looks like a candidate.

## PHP 8.6 release date and status

The official preparation page currently targets **November 19, 2026** for GA. That date can still move, but it is more precise than the older "late 2026" wording.

| Date | Release |
| --- | --- |
| Jul 02 2026 | Alpha 1 |
| Jul 16 2026 | Alpha 2 |
| Jul 30 2026 | Alpha 3 |
| Aug 13 2026 | Feature freeze |
| Aug 13 2026 | Beta 1 |
| Aug 27 2026 | Beta 2 |
| Sep 10 2026 | Beta 3 |
| Sep 24 2026 | RC1 |
| Oct 08 2026 | RC2 |
| Oct 22 2026 | RC3 |
| Nov 05 2026 | RC4 |
| Nov 19 2026 | GA |

As of March 15, 2026, there are still no alpha, beta, or RC tags. So the best way to judge what is really coming is current branch status, not rumor or wishlists.

## What is confirmed for PHP 8.6 right now?

On the current [PHP.Watch 8.6 RFC tracker](https://php.watch/versions/8.6/rfcs), PHP 8.6 currently has:

* two RFCs already implemented in `php-src`
* six more RFCs accepted for PHP 8.6 but not landed yet

There is also at least one notable non-RFC change already in the branch: better error-location reporting for `json_decode()` and `json_last_error_msg()`.

That distinction matters. "Implemented" means code has already landed. "Accepted" means the vote passed, but the final merge is still being finished.

## Already landed in the PHP 8.6 branch

### `clamp()` gives PHP a native range guard

The [clamp RFC](https://wiki.php.net/rfc/clamp_v2) gives PHP a built-in function to force a value into a given range.

Conceptually:

```php
$result = clamp($value, min: $min, max: $max);
```

* If `$value` is between `$min` and `$max`, you get `$value`.
* If `$value` is lower than `$min`, you get `$min`.
* If `$value` is higher than `$max`, you get `$max`.
* If `$min > $max`, PHP throws a `ValueError` (the bounds are invalid).
* If `$min` or `$max` is `NAN`, PHP also throws a `ValueError`.

In day-to-day code, this mostly saves you from repeating the same small guard clauses everywhere.

> Quick warning: `clamp()` is not just a math helper. It lives in the global namespace, accepts `mixed`, and uses PHP's normal comparison rules. That means strings, arrays, booleans, and comparable objects can all be clamped too, and `declare(strict_types=1)` does not change that behavior.
>
> Another edge case worth knowing: `NAN` as a bound throws `ValueError`, but `clamp(NAN, 4, 6)` itself returns `NAN`.

### ReflectionProperty gets `isReadable()` and `isWritable()`

The [isReadable/Writeable Reflection methods RFC](https://wiki.php.net/rfc/isreadable-iswriteable) adds two new methods to `ReflectionProperty`:

* `ReflectionProperty::isReadable(?string $scope, ?object $object = null): bool`
* `ReflectionProperty::isWritable(?string $scope, ?object $object = null): bool`

This is more useful than it looks. `isPublic()` stopped being a reliable answer to "can I really read or write this property from here?" once PHP added `readonly` properties, asymmetric visibility, hooks, and magic accessors.

That mostly helps framework, ORM, serializer, and tooling authors who need runtime introspection that matches modern property rules.

### JSON decoding errors now report the location

This one is not an RFC, but it is already tracked in the current [PHP 8.6 change log on PHP.Watch](https://php.watch/versions/8.6/json_decode-error-position).

In PHP 8.6, `json_decode()` and `json_last_error_msg()` now include the line and column where parsing failed instead of only telling you that the JSON was invalid.

```php
$json = '[{';

json_decode($json);

echo json_last_error_msg(); // "Syntax error near location 1:3"
```

That is a small quality-of-life improvement, but a very practical one when you are debugging broken API payloads or large JSON fixtures.

## Accepted for PHP 8.6, but still pending implementation

### `trim()`, `ltrim()`, and `rtrim()` will strip form feed too

The [Add Form Feed in Trim Functions RFC](https://wiki.php.net/rfc/trim_form_feed) is accepted for PHP 8.6, but not landed yet.

That means `"\f"` will be added to the default character mask of `trim()`, `ltrim()`, and `rtrim()`. It is small, but it does change behavior: if your code relied on form-feed characters surviving a default `trim()`, that assumption will break once the RFC lands.

### `mysqli_quote_string()` adds a safer manual-escaping helper

The [mysqli_quote_string RFC](https://wiki.php.net/rfc/mysqli_quote_string) is accepted for PHP 8.6.

It adds both `mysqli::quote_string()` and `mysqli_quote_string()`. The point is not to replace prepared statements, but to make the unavoidable manual-escaping cases less error-prone by returning an already quoted string instead of making people remember to pair `real_escape_string()` with matching quotes themselves.

That also addresses a real footgun in the current API: `mysqli::real_escape_string()` is easy to misuse, especially around quoting rules and `NO_BACKSLASH_ESCAPES`.

### `pack()` / `unpack()` get integer endianness modifiers

The [integer endianness modifiers RFC](https://wiki.php.net/rfc/pack-unpack-endianness-signed-integers-support) is also accepted for PHP 8.6.

It adds Perl-style `<` and `>` modifiers to `pack()` and `unpack()` so you can express signed little-endian and big-endian integers directly, instead of doing manual bit-twiddling or bespoke unpack helpers.

### Floating-point modifiers follow the same model

The [floating-point endianness modifiers RFC](https://wiki.php.net/rfc/pack-unpack-float-endianness-modifier) was accepted on **March 14, 2026**.

It extends the same `<` and `>` modifier style to floating-point format codes, so the integer and float stories finally line up.

### Closure optimizations are accepted too

The [Closure optimizations RFC](https://wiki.php.net/rfc/closure-optimizations) was accepted on **March 13, 2026**.

This one is mostly about performance rather than new syntax. It lets PHP infer `static` for closures that do not use `$this`, and cache fully stateless closures between uses. In plain English: fewer needless closure objects, fewer avoidable reference cycles, and less overhead in closure-heavy code.

### Partial Function Application (v2)

[Partial Function Application (v2)](https://wiki.php.net/rfc/partial_function_application_v2) is accepted for PHP 8.6, but as of March 15, 2026 the official RFC page is still marked **In Implementation**, not merged into the branch yet.

The idea is still the same: instead of writing tiny one-off closures everywhere, you write the call you want and leave the missing arguments as placeholders.

```php
function json_response(array $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
}

$jsonOk = json_response(?, 200);

$jsonOk(['ok' => true]);
```

`json_response(?, 200)` returns a closure, so you do not have to write `fn (array $data) => json_response($data, 200)` by hand.

I still expect PFA to be one of the headline PHP 8.6 features, but right now it belongs in the "accepted, not landed yet" bucket, not the "already implemented" bucket.

> Short caveat box: the RFC supports both `?` and `...` placeholders, allows named placeholders that can reorder parameters, and even allows partial applications in constant expressions. It does **not** allow partially applying `new` constructors, and it has a few special rules around variadics, extra arguments, and `func_get_args()`.
>
> If you mainly want the practical syntax, examples, and "should I actually use this?" angle, my separate guide is still the better read.

If you want the syntax and use cases broken down in plain language, I wrote a separate guide: [Partial Function Application in PHP 8.6, made easy](https://benjamincrozat.com/partial-function-application-php-86).

## What else might still land in PHP 8.6?

If you want the most concrete candidates today, I would watch the proposals that are already explicitly targeting PHP 8.6:

* [Add `values()` to `BackedEnum`](https://wiki.php.net/rfc/add_values_method_to_backed_enum)
* [Nullable and non-nullable cast operators](https://wiki.php.net/rfc/nullable-not-nullable-cast-operator)
* [Deprecate fuzzy casts and allow `Stringable` in strict mode](https://wiki.php.net/rfc/deprecate-fuzzy-casts)

There is also a [True Async](https://wiki.php.net/rfc/true_async) RFC page, but as of March 15, 2026 it is still under discussion rather than accepted.

So the rule is simple: until an RFC is accepted and attached to PHP 8.6, treat it as a candidate, not a feature.

## How to follow PHP 8.6 before alpha 1

Because Alpha 1 is not scheduled until **July 2, 2026**, there is not much to install yet. The best signals today are:

1. The [PHP 8.6 preparation page](https://wiki.php.net/todo/php86) for the release schedule.
2. The [PHP.Watch 8.6 RFC tracker](https://php.watch/versions/8.6/rfcs) for a clean accepted vs implemented split.
3. The implementation PRs or commits linked from the RFC pages you care about.

Once the alpha and RC cycle starts, that is the right moment to run your applications and libraries against real 8.6 builds.

## FAQ

### When is PHP 8.6 coming out?

The current target GA date is **November 19, 2026**. Alpha 1 is scheduled for July 2, 2026, and feature freeze plus Beta 1 are scheduled for August 13, 2026.

### What are the confirmed features right now?

Already in the 8.6 branch:

* `clamp()`
* `ReflectionProperty::isReadable()` / `isWritable()`
* Better JSON error locations for `json_decode()` / `json_last_error_msg()`

Accepted for PHP 8.6 but still pending landing:

* Add Form Feed in Trim Functions
* `mysqli_quote_string()`
* `pack()` / `unpack()` integer endianness modifiers
* `pack()` / `unpack()` floating-point endianness modifiers
* Closure optimizations
* Partial Function Application (v2)

Everything else is still a proposal until the RFC status changes.

If you are following the next wave of PHP changes before they land, these are the release reads I would keep open:

- [See what PHP 8.5 changes before you upgrade](/php-85)
- [Catch the PHP 8.4 changes that could affect your code](/php-84)
- [Start fixing the things PHP 9 will stop forgiving](/php-90)
- [See what partial function application looks like in PHP 8.6](/partial-function-application-php-86)
