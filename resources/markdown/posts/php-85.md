---
id: "01KKEW27JJQ1HK3NYX9Z4CNNFW"
title: "PHP 8.5 is out now; 15 new features and changes"
slug: "php-85"
author: "benjamincrozat"
description: "PHP 8.5 arrives November 20, 2025: pipe operator, URI extension, clone with v2, array_first/array_last, fatal error backtraces, Intl updates, and more."
categories:
  - "php"
published_at: 2025-07-01T07:50:00+02:00
modified_at: 2025-11-20T10:13:00+01:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KAGD2Z6QRJ2E0G0TGB94CE53.png"
sponsored_at: null
---
## Introduction

PHP 8.5 GA (General Availability) was released on November 20, 2025. In this post, I highlight new features you’ll actually use like the new pipe operator, the URI extension, clone with v2, the #[\NoDiscard] attribute, and array helpers such as array_first and array_last.

## PHP 8.5 release date and schedule

Here’s the [preparation tasks list](https://wiki.php.net/todo/php85). PHP 8.5 was tested through four alphas, three betas, and five release candidates.


| Date         | Release of PHP 8.5 |
|--------------|--------------------|
| Jul 03 2025  | Alpha 1        |
| Jul 17 2025  | Alpha 2        |
| Jul 31 2025  | Alpha 4        |
| Aug 12 2025  | Feature freeze |
| Aug 14 2025  | Beta 1         |
| Aug 28 2025  | Beta 2         |
| Sep 11 2025  | Beta 3         |
| Sep 25 2025  | RC1            |
| Oct 09 2025  | RC2            |
| Oct 23 2025  | RC3            |
| Nov 06 2025  | RC4            |
| Nov 13 2025  | RC5            |
| Nov 20 2025  | GA             |

PHP 8.5 will remain in active support until December 31, 2027 and will get security updates until December 31, 2029.

## How to install PHP 8.5 (Homebrew and Docker)

If you’re on macOS and want to test PHP 8.5, Homebrew makes it easy. Bottles are available. The safest way to avoid conflicts with Homebrew core is to use the dedicated tap and fully qualified formula. The tap’s status is documented in the [tap README](https://github.com/shivammathur/homebrew-php).

1. Install the [Homebrew package manager](https://brew.sh) if it’s not done already.
2. Run `brew update` to make sure Homebrew and the formulae are up to date.
3. Add the PHP tap: `brew tap shivammathur/php`.
4. Install PHP 8.5 from the tap: `brew install shivammathur/php/php@8.5`.
5. Link it so the `php` shim targets 8.5: `brew link --overwrite --force shivammathur/php/php@8.5` (this overwrites the `php` shim).

Tip if you have multiple PHP versions installed: run `brew unlink php` (or another version like `php@8.3`) before linking 8.5; later run `brew link` for the one you want active.

If you prefer not to touch your Homebrew PHP, you can test using the official [PHP Docker images](https://hub.docker.com/_/php). 8.5 tags are available at RC and GA.

If you want to learn more about setup on a Mac, I wrote something for you: [Install PHP on macOS (Laravel Valet guide)](https://benjamincrozat.com/install-php-mac-laravel-valet)

## New in PHP 8.5

### Pipe operator (|>)

The PHP 8.5 pipe operator (|>) is my favorite of the PHP 8.5 features. It passes the result of the left expression as the single argument to the callable on the right, letting you write readable, left-to-right pipelines.

A type-safe example that stays string-only until the end:

```php
$length = "  Hello, World!  "
    |> trim(...)
    |> strtoupper(...)
    |> htmlentities(...)
    |> strlen(...);
```

Rules to remember for the PHP 8.5 pipe operator:
- Right-hand side must be a callable that accepts exactly one parameter; the piped value becomes that parameter.
- You can pipe into closures, arrow functions, first-class callables, array callables, and invokable objects.
- Built-ins that accept no parameters cannot be used directly: wrap them in a callable that accepts one parameter, for example `$x |> (fn($_): string => phpversion());`.
- The operator is left-associative and binds after arithmetic but before comparisons and before the null-coalescing operator (??); use parentheses for mixed expressions.
- Gotchas: wrap arrow-function steps in parentheses when used in a chain; by-reference callables are not allowed on the right-hand side.

A quick example showing an arrow function in a chain:

```php
$result = "  a  "
    |> trim(...)
    |> (fn ($s) => $s . "!")
    |> strlen(...);
```

Learn more: the [pipe operator overview on PHP.Watch](https://php.watch/versions/8.5/pipe-operator) and the [RFC on wiki.php.net](https://wiki.php.net/rfc/pipe-operator-v3).

### URI extension (RFC 3986 and WHATWG URL)

PHP 8.5 includes a new, always-available URI extension with standards-compliant parsers for both RFC 3986 and the WHATWG URL standard. It provides immutable value objects with withers, normalization, and it throws on invalid input. Classes include `Uri\Rfc3986\Uri` and `Uri\WhatWg\Url`. Under the hood it uses the uriparser and Lexbor libraries.

```php
use Uri\Rfc3986\Uri;

$u = new Uri('https://example.com/a/../b?x=1#frag');
$u = $u->withPath('/b');

echo (string) $u; // https://example.com/b?x=1#frag
```

Overview and background: [the PHP 8.5 release page](https://www.php.net/releases/8.5/en.php) and the [URI extension RFC](https://wiki.php.net/rfc/uri).

### array_first and array_last

Two small helpers you’ll actually use: array_first and array_last return the first and last element of an array. On empty arrays they return null, which composes well with `??` to provide defaults.

```php
$numbers = [10, 20, 30];

unset($numbers[0]);

array_first($numbers); // 20
array_last($numbers);  // 30

$empty = [];
array_first($empty) ?? 'n/a'; // 'n/a'
```

`$numbers[0]` doesn’t exist anymore and you can’t know that in advance. And obviously, you usually never know which key is the last one of an array, so array_last()’s usefulness is immediately obvious.

Learn more: [RFC: array_first and array_last](https://wiki.php.net/rfc/array_first_last).

### Fatal error backtraces (fatal_error_backtraces)

Debugging gets easier with fatal error backtraces. With the new `fatal_error_backtraces` INI (defaults to On in both development and production), PHP shows a stack trace for fatal errors and respects #[\SensitiveParameter] and `zend.exception_ignore_args`.

```ini
; php.ini
fatal_error_backtraces = 1
```

Details: [PHP.Watch: fatal error backtraces](https://php.watch/versions/8.5/fatal_error_backtraces) and the [RFC](https://wiki.php.net/rfc/error_backtraces_v2).

### Clone with v2

PHP 8.5 upgrades `clone` so you can pass a second array argument to adjust properties on the cloned object. `clone` now acts like a language construct that can take that second array, calls `__clone` before assignments, and respects visibility and property hooks. This is a nice quality-of-life win for immutable patterns. Learn more: [clone with v2 RFC](https://wiki.php.net/rfc/clone_with_v2).

### Handler introspection (get_error_handler/get_exception_handler)

New functions `get_error_handler()` and `get_exception_handler()` let you introspect the current handlers at runtime.

```php
set_error_handler(fn () => true);
var_dump(get_error_handler());
restore_error_handler();

set_exception_handler(fn (Throwable $e) => null);
var_dump(get_exception_handler());
restore_exception_handler();
```

Learn more: [RFC for handler introspection](https://wiki.php.net/rfc/get-error-exception-handler) and the [PHP.Watch overview](https://php.watch/versions/8.5/get_error_handler-get_exception_handler).

### Intl updates (list formatter, RTL check, NumberFormatter updates)

Internationalization gets a nice bump in PHP 8.5: IntlListFormatter formats human-readable lists; `locale_is_right_to_left()` and `Locale::isRightToLeft()` help with layout for right-to-left scripts; NumberFormatter adds compact decimal formats (`NumberFormatter::DECIMAL_COMPACT_SHORT` and `NumberFormatter::DECIMAL_COMPACT_LONG`); and `Locale::addLikelySubtags()`/`::minimizeSubtags()` help normalize locale tags.

```php
$lf = new IntlListFormatter('en', IntlListFormatter::TYPE_CONJUNCTION);
echo $lf->format(['apples', 'bananas', 'oranges']); // apples, bananas, and oranges

$isRtl = locale_is_right_to_left('ar'); // true
```

Learn more: [IntlListFormatter](https://php.watch/versions/8.5/IntlListFormatter) and RTL checks on [PHP.Watch](https://php.watch/versions/8.5/locale_is_right_to_left-Locale-isRightToLeft). Also see compact decimals and locale helpers in [PHP.Watch’s 8.5 alpha 1 summary](https://php.watch/versions/8.5/releases/8.5.0alpha1).

### grapheme_levenshtein

`grapheme_levenshtein` computes a Unicode-aware [Levenshtein distance](https://en.wikipedia.org/wiki/Levenshtein_distance) that understands grapheme clusters (what users perceive as characters), which is perfect for strings like “café”.

```php
grapheme_levenshtein('café', 'cafe'); // 1
```

Learn more: [PHP.Watch explainer](https://php.watch/rfcs/grapheme_levenshtein).

### Attributes on constants

PHP 8.5 lets you add attributes directly to const declarations. The main constraint: one constant per statement.

```php
#[\MyAttribute]
const EXAMPLE = 1;

#[\MyAttribute]
const A = 1; // OK
// const A = 1, B = 2; // Not allowed with attributes
```

You can reflect these with `ReflectionClassConstant::getAttributes()` or `ReflectionConstant::getAttributes()`. The built-in #[\Deprecated] works on constants too, so you can mark constants as deprecated and surface warnings.

Learn more: [RFC: attributes on constants](https://wiki.php.net/rfc/attributes-on-constants).

### Final property promotion

Final property promotion rounds out the work started in 8.4 with final properties and property hooks. In PHP 8.5 you can mark a promoted property as `final` directly in the constructor, preventing redeclaration or overrides in child classes. Visibility is optional when using `final` (defaults to `public`), and you can combine this with property hooks.

Before PHP 8.5, you had to declare the final property in the class body, then assign it in the constructor:

```php
class User {
    final public readonly string $name;

    public function __construct(string $name) {
        $this->name = $name;
    }
}
```

With PHP 8.5, you can promote and finalize the property in one place:

```php
class User {
    public function __construct(
        final public readonly string $name
    ) {}
}
```

Learn more: [RFC: final property promotion](https://wiki.php.net/rfc/final_promotion).

### Closures and first-class callables in constant expressions

PHP 8.5 allows static closures in constant expressions and also allows first-class callables, which complements the closures-in-constants feature. Both are supported now, with a few restrictions.

```php
class Foo {
    // Static closure as a constant
    public const UPPER = static function (string $v): string { return strtoupper($v); };

    // First-class callable as a constant
    public const LOWER = 'strtolower'(...);
}
```

Notes:
- Closures must be `static`. No variable capture (`use ($x)`) or arrow functions (they auto-capture) are allowed in constant expressions.
- First-class callables like `'strtolower'(...)` or `Bar::baz(...)` are allowed. (Closures are covered by the separate “closures in constant expressions” RFC.)

Learn more: [Closures in constant expressions (RFC)](https://wiki.php.net/rfc/closures_in_const_expr) and [first-class callables in constants on PHP.Watch](https://php.watch/r/128).

### cURL: curl_multi_get_handles

`curl_multi_get_handles` lets you retrieve all easy handles attached to a multi handle, which is useful for debugging and housekeeping.

```php
$mh = curl_multi_init();
$ch = curl_init('https://example.com');
curl_multi_add_handle($mh, $ch);
$handles = curl_multi_get_handles($mh); // array of CurlHandle
```

Learn more: [PHP.Watch note](https://php.watch/versions/8.5/curl_multi_get_handles).

### See only the settings you changed with php --ini=diff

A small but powerful CLI addition: `php --ini=diff` prints only INI settings that differ from PHP’s built-in defaults, which is great for quick diagnostics.

```bash
php --ini=diff
```

Example output:

```text
Non-default INI settings:
html_errors: "1" -> "0"
implicit_flush: "0" -> "1"
max_execution_time: "30" -> "0"
```

Ops note: a new `max_memory_limit` INI (INI_SYSTEM, default `-1`) can cap `memory_limit` at the system level. See [PHP.Watch: max_memory_limit](https://php.watch/versions/8.5/max_memory_limit).

### The new PHP_BUILD_DATE constant

There’s a new `PHP_BUILD_DATE` constant that exposes the timestamp of the binary you’re running, which helps when comparing nightlies or deployment artifacts.

```php
echo PHP_BUILD_DATE; // e.g., Sep 16 2025 10:44:26
```

Note: it prints a C-style timestamp string, not ISO-8601.

Learn more: [PHP.Watch: PHP_BUILD_DATE](https://php.watch/versions/8.5/PHP_BUILD_DATE).

### Marking return values as important (#[\NoDiscard])

#[\NoDiscard] marks functions or methods whose return values must not be ignored, helping developers spot silent bugs.

```php
#[\NoDiscard("processing might fail for individual items")]
function bulk_process(array $items): array { /* ... */ }

bulk_process($items);            // Warning.
$results = bulk_process($items); // OK.
(void) bulk_process($items);     // Explicitly ignore (suppresses the warning).
```

Engine details: ignoring a #[\NoDiscard] return value emits E_WARNING for internal functions and E_USER_WARNING for userland; functions that return `void`/`never`, and magic methods that must be `void`, cannot use it. Tip: the `(void)` cast is an explicit discard.

Learn more: [Marking return value as important (RFC)](https://wiki.php.net/rfc/marking_return_value_as_important).

## Under the hood

- OPcache is no longer optional: it is always compiled in from PHP 8.5 onward, while enablement remains controlled by INI settings such as `opcache.enable` and `opcache.enable_cli`. This affects packaging and “what is included by default” in many environments. See the [OPcache required RFC](https://wiki.php.net/rfc/make_opcache_required).
- A new `max_memory_limit` INI caps `memory_limit` at the system level (INI_SYSTEM, default `-1`), which helps lock down production defaults. See [PHP.Watch: max_memory_limit](https://php.watch/versions/8.5/max_memory_limit).

## What else is coming in 8.5

- DOM in the 8.5 line: `outerHTML`, `insertAdjacentHTML`, and `children`. See [alpha 1 notes](https://php.watch/versions/8.5/releases/8.5.0alpha1).
- Smaller items worth noting: CHIPS/partitioned cookies (a new `partitioned` flag in `setcookie()` and session options; `secure` is required when you use it), #[Deprecated] support for traits, #[Override] allowed on properties, `FILTER_THROW_ON_FAILURE`, locale support for case-insensitive grapheme functions, and the accepted RFC for persistent cURL share handles. See the [CHIPS RFC](https://wiki.php.net/rfc/chips) and the [PHP 8.5 RFC list](https://php.watch/versions/8.5/rfcs).

## FAQ

### What is the PHP 8.5 release date?

November 20, 2025.

### How do I try PHP 8.5 today?

Use the Homebrew tap formula `shivammathur/php/php@8.5` on macOS or the official PHP Docker images.

## Is OPcache required in 8.5?

Yes. OPcache is always compiled in starting with PHP 8.5.

## Conclusion

The pipe operator (|>) steals the spotlight in PHP 8.5, making code cleaner and more readable. The new URI extension gives PHP a modern, standards-based URL/URI API, and `clone` with v2 improves immutable workflows. For more, see the [official PHP 8.5 release page](https://www.php.net/releases/8.5/en.php) and the [What’s new in 8.5 guide](https://www.php.net/manual/en/migration85.new-features.php).