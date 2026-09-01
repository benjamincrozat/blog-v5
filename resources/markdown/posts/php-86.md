---
id: "01KKEW27JNW86X1D1J2A25AB7D"
title: "PHP 8.6 release date and features"
slug: "php-86"
author: "benjamincrozat"
description: "PHP 8.6 is in beta and scheduled for November 19, 2026. See the release timeline, six tested changes, and the upgrade risks to check before GA."
categories:
  - "php"
published_at: 2025-12-07T18:33:00+01:00
modified_at: 2026-09-01T16:57:19Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBX0YY1ERYXHAV4T6YPFE21R.jpeg"
sponsored_at: null
---
PHP 8.6 is scheduled for **November 19, 2026**. As of September 1, **PHP 8.6.0 Beta 2** is the current test release, with Beta 3 planned for September 10.

Do not use the beta in production. The [official Beta 2 announcement](https://www.php.net/archive/2026.php#2026-08-27-1) calls it an early test version. The useful move now is to run your application and library test suites against it, then fix compatibility problems before the final release.

I built the official Beta 2 source archive and ran the examples below. Partial function application, readonly property defaults, `clamp()`, parameter doc comments, better JSON errors, and the new session defaults all behaved as documented.

## PHP 8.6 release date and current status

The official [PHP 8.6 schedule](https://wiki.php.net/todo/php86) targets November 19 for general availability. Release dates can still move, but the project has reached its beta cycle and the feature set is much firmer than it was during the RFC voting stage.

| Date | Release |
| --- | --- |
| July 2, 2026 | Alpha 1 |
| July 16, 2026 | Alpha 2 |
| July 30, 2026 | Alpha 3 |
| August 13, 2026 | Beta 1 and soft feature freeze |
| August 27, 2026 | Beta 2 |
| September 10, 2026 | Beta 3 |
| September 22, 2026 | Hard feature freeze |
| September 24, 2026 | RC1 |
| October 8, 2026 | RC2 |
| October 22, 2026 | RC3 |
| November 5, 2026 | RC4 |
| November 19, 2026 | General availability |

The short decision is simple:

- **Production apps:** stay on a supported stable PHP release.
- **Application and package CI:** add PHP 8.6 as a non-blocking job now.
- **Extension and tooling authors:** test now, because lower-level changes can need more lead time.

## What I tested in PHP 8.6 Beta 2

I used the official `php-8.6.0beta2.tar.xz` archive from the [PHP pre-release builds page](https://www.php.net/pre-release-builds.php). Its SHA-256 checksum matched the published value, and the CLI reported `PHP 8.6.0beta2`.

The test ran on macOS with an Apple M5. I built the CLI and session extension with this configuration:

```bash
./configure \
  --disable-all \
  --enable-cli \
  --enable-session \
  --disable-cgi \
  --disable-phpdbg \
  --without-pear
```

This is a focused language smoke test, not a claim that a full Laravel or Symfony application is ready for PHP 8.6.

### Partial function application removes tiny wrappers

[Partial function application](https://wiki.php.net/rfc/partial_function_application_v2) lets you leave one or more arguments open with `?`. PHP returns a closure that accepts the missing values later.

```php
function label(string $prefix, string $value): string
{
    return "$prefix$value";
}

$withVersion = label('PHP ', ?);

echo $withVersion('8.6'), PHP_EOL;
```

Beta 2 printed:

```text
PHP 8.6
```

The same file failed to parse on PHP 8.5.7 because `?` was not valid in that argument position. For more syntax and edge cases, see my [partial function application guide](/partial-function-application-php-86).

### Readonly properties can declare defaults

PHP 8.6 allows a readonly property to have a default value:

```php
final class RetryPolicy
{
    public readonly int $maxAttempts = 3;
}

echo (new RetryPolicy())->maxAttempts, PHP_EOL;
```

Beta 2 printed `3`. PHP 8.5.7 stopped with `Readonly property RetryPolicy::$maxAttempts cannot have default value`.

This is useful for immutable value objects whose default is part of the type itself. The [readonly property defaults RFC](https://wiki.php.net/rfc/readonly_property_defaults) keeps one important limit: a default object still has its own mutable state unless that object is designed to be immutable.

### `clamp()` keeps values inside a range

The new [`clamp()` function](https://wiki.php.net/rfc/clamp_v2) returns the value when it is within the bounds, or the nearest bound when it is outside them.

```php
echo clamp(120, 1, 100); // 100
```

I got `100` on Beta 2. Invalid bounds, where the minimum is greater than the maximum, throw a `ValueError`.

`clamp()` accepts `mixed`, so it follows PHP's normal comparison rules. Use clearly comparable values instead of treating it as an integer-only helper.

### Parameter doc comments are available through reflection

PHP 8.6 preserves a doc comment attached directly to a function parameter and exposes it through `ReflectionParameter::getDocComment()`.

```php
function acceptsPort(/** @var int<1, 65535> */ int $port): int
{
    return $port;
}

$parameter = (new ReflectionFunction('acceptsPort'))->getParameters()[0];

echo trim($parameter->getDocComment());
```

Beta 2 returned:

```text
/** @var int<1, 65535> */
```

The [parameter doc comments RFC](https://wiki.php.net/rfc/parameter-doccomments) is mainly useful to static analysis, dependency injection, serialization, and other tools that inspect signatures.

### JSON errors now point to the broken location

The official [PHP 8.6 upgrading notes](https://github.com/php/php-src/blob/php-8.6.0beta2/UPGRADING) document better locations in JSON errors. This test uses invalid JSON with a trailing comma:

```php
try {
    json_decode("{\n  \"name\": \"PHP 8.6\",\n}", flags: JSON_THROW_ON_ERROR);
} catch (JsonException $exception) {
    echo $exception->getMessage();
}
```

Beta 2 reported:

```text
Syntax error near location 3:1
```

That is much more useful than a plain `Syntax error` when the payload spans many lines.

### Session defaults are safer and may change behavior

PHP 8.6 changes three built-in session defaults. I compared clean `php -n` runs so local `php.ini` files could not affect the result:

| Setting | PHP 8.5.7 | PHP 8.6.0 Beta 2 |
| --- | --- | --- |
| `session.use_strict_mode` | `0` | `1` |
| `session.cookie_httponly` | `0` | `1` |
| `session.cookie_samesite` | empty | `Lax` |

These defaults reject unknown session IDs, hide the session cookie from JavaScript, and restrict most cross-site cookie sending. The [secure session defaults RFC](https://wiki.php.net/rfc/session_security_defaults) explains the compatibility boundary: apps that need a session cookie on a cross-site `POST` must configure `SameSite=None` and use a secure cookie.

Framework configuration can override PHP's base values, so check the effective settings in the environment you deploy.

## More PHP 8.6 features worth testing

The exact Beta 2 [`UPGRADING` file](https://github.com/php/php-src/blob/php-8.6.0beta2/UPGRADING) lists the full surface. These are the other changes I would check first:

- [`Time\Duration`](https://wiki.php.net/rfc/duration_class) adds a dedicated value type for a length of time.
- The new [`Io\Poll` API](https://wiki.php.net/rfc/poll_api) gives lower-level libraries a portable way to wait for I/O readiness.
- `ReflectionProperty::isReadable()` and `isWritable()` understand modern property rules better than a simple public/private check.
- Enums may define `__debugInfo()`, and `#[\Override]` may be used on class constants and enum cases.
- `SortDirection` adds a shared direction enum for sorting-related code.
- `mysqli::quote_string()` returns an already quoted value for the rare cases where a prepared statement is not possible. Prepared statements remain the safer default.

I did not include every extension change here. The official upgrading notes are the better checklist for code that depends on Curl, Intl, OpenSSL, streams, URI handling, or native extensions.

## What to check before upgrading

Start with a clean test run on PHP 8.6 and turn on all deprecation reporting. Then pay special attention to these areas:

1. **Authentication and cross-site flows:** verify login callbacks, embedded forms, and any cross-site `POST` that depends on a PHP session cookie.
2. **New deprecations:** PHP 8.6 deprecates patterns such as returning from a `finally` block, passing a third argument to `define()`, and using `spl_object_hash()` instead of `spl_object_id()`.
3. **Exact error-message assertions:** JSON errors now contain line and column details, so brittle tests may need a less exact assertion.
4. **Dependencies and extensions:** a working language smoke test does not prove that every Composer package or native extension supports the beta.

If your test suite is clean, keep PHP 8.6 in CI and wait for the stable release before production. The beta is for finding upgrade work early, not for getting ahead by a few weeks.

If you are mapping PHP's next releases, keep these nearby:

- [See what PHP 8.5 changed before you upgrade](/php-85)
- [Start fixing the things PHP 9 will stop forgiving](/php-90)
- [Explore partial function application in PHP 8.6](/partial-function-application-php-86)
- [Catch the PHP 8.4 changes that could affect your code](/php-84)
