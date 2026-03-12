---
id: "01KKEW27JNW86X1D1J2A25AB7D"
title: "PHP 8.6: release date and confirmed features"
slug: "php-86"
author: "benjamincrozat"
description: "PHP 8.6 is scheduled for November 19, 2026. Here are the features already implemented, what is accepted but still pending, and what is still only a proposal."
categories:
  - "php"
published_at: 2025-12-07T18:33:00+01:00
modified_at: 2026-03-12T11:50:20+01:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBX0YY1ERYXHAV4T6YPFE21R.jpeg"
sponsored_at: null
---
As of March 12, 2026, PHP 8.6 is still the upcoming yearly release after [PHP 8.5](https://benjamincrozat.com/php-85).

But it is no longer just "two RFCs and wait." The official [PHP 8.6 preparation page](https://wiki.php.net/todo/php86) already includes a release calendar, and the official [RFC index](https://wiki.php.net/rfc) now splits PHP 8.6 work into features already implemented, features accepted but still pending landing, and ideas that are still only proposals.

This post tracks that status in plain English.

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

As of March 12, 2026, there are still no alpha, beta, or RC tags. So the best way to judge what is really coming is the RFC status, not rumor or wishlists.

## What is confirmed for PHP 8.6 right now?

On the official RFC index, PHP 8.6 currently has:

* four RFCs already implemented in `php-src`
* one more RFC accepted for 8.6 but still marked "In Implementation"

That distinction matters. "Implemented" means code has already landed. "In Implementation" means the vote passed, but the final merge is still being finished.

## Features already implemented in PHP 8.6

### `trim()`, `ltrim()`, and `rtrim()` now strip form feed too

The [Add Form Feed in Trim Functions RFC](https://wiki.php.net/rfc/trim_form_feed) adds `"\f"` to the default character mask of `trim()`, `ltrim()`, and `rtrim()`.

That is a tiny change, but it matters because PHP used to treat form feed as the odd whitespace character out.

```php
$input = "\fHello World\f";

trim($input); // "Hello World" in PHP 8.6
```

It is also the only confirmed PHP 8.6 change here that is explicitly backward incompatible: if your code relied on `trim()` preserving leading or trailing form-feed characters, behavior will change.

### `grapheme_strrev()` reverses user-visible characters correctly

The new [grapheme_strrev RFC](https://wiki.php.net/rfc/grapheme_strrev) adds `grapheme_strrev()` to the `intl` extension.

This matters because `strrev()` works on bytes, and many userland "multibyte reverse" helpers still break combined emoji, accented characters, or right-to-left text. `grapheme_strrev()` works on grapheme clusters, which is much closer to what users see as one character.

```php
echo grapheme_strrev("a👨‍👨‍👧‍👦b"); // "b👨‍👨‍👧‍👦a"
```

If you deal with localized strings, emoji-heavy content, or proper Unicode handling in general, this is a much better primitive than rolling your own.

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

### ReflectionProperty gets `isReadable()` and `isWritable()`

The [isReadable/Writeable Reflection methods RFC](https://wiki.php.net/rfc/isreadable-iswriteable) adds two new methods to `ReflectionProperty`:

* `ReflectionProperty::isReadable(?string $scope, ?object $object = null): bool`
* `ReflectionProperty::isWritable(?string $scope, ?object $object = null): bool`

This is more useful than it looks. `isPublic()` stopped being a reliable answer to "can I really read or write this property from here?" once PHP added `readonly` properties, asymmetric visibility, hooks, and magic accessors.

That mostly helps framework, ORM, serializer, and tooling authors who need runtime introspection that matches modern property rules.

## Accepted for PHP 8.6, but still pending implementation

### Partial Function Application (v2)

[Partial Function Application (v2)](https://wiki.php.net/rfc/partial_function_application_v2) is accepted for PHP 8.6, but as of March 12, 2026 the RFC page is still marked **In Implementation**, not merged into the branch yet.

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

If you want the syntax and use cases broken down in plain language, I wrote a separate guide: [Partial Function Application in PHP 8.6, made easy](https://benjamincrozat.com/partial-function-application-php-86).

## What else might still land in PHP 8.6?

The official RFC index still has a long list of ideas under discussion. A few visible examples are:

* [Readonly Variables](https://wiki.php.net/rfc/readonly-variables)
* [Query Parameter Manipulation Support](https://wiki.php.net/rfc/query_params)
* [Type Aliases](https://wiki.php.net/rfc/typed-aliases)
* [Stringable Enums](https://wiki.php.net/rfc/stringable-enums)

There is also a [True Async](https://wiki.php.net/rfc/true_async) RFC page, but as of March 12, 2026 it is still only in the **Draft** section of the RFC index, not accepted for PHP 8.6.

So the rule is simple: until an RFC is accepted and attached to PHP 8.6, treat it as a candidate, not a feature.

## How to follow PHP 8.6 before alpha 1

Because Alpha 1 is not scheduled until **July 2, 2026**, there is not much to install yet. The best signals today are:

1. The [PHP 8.6 preparation page](https://wiki.php.net/todo/php86) for the release schedule.
2. The [official RFC index](https://wiki.php.net/rfc) for status changes.
3. The implementation PRs or commits linked from the RFC pages you care about.

Once the alpha and RC cycle starts, that is the right moment to run your applications and libraries against real 8.6 builds.

## FAQ

### When is PHP 8.6 coming out?

The current target GA date is **November 19, 2026**. Alpha 1 is scheduled for July 2, 2026, and feature freeze plus Beta 1 are scheduled for August 13, 2026.

### What are the confirmed features right now?

Already implemented for PHP 8.6:

* Add Form Feed in Trim Functions
* `grapheme_strrev()`
* `clamp()`
* `ReflectionProperty::isReadable()` / `isWritable()`

Accepted for PHP 8.6 but still pending landing:

* Partial Function Application (v2)

Everything else is still a proposal until the RFC status changes.

If you are following the next wave of PHP changes before they land, these are the release reads I would keep open:

- [PHP 8.5: 15 new features and changes](/php-85)
- [PHP 8.4: new features and release date](/php-84)
- [PHP 9 release date and what to fix now](/php-90)
- [Partial Function Application in PHP 8.6, made easy](/partial-function-application-php-86)
