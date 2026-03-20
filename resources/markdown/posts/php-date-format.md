---
id: "01KM632E71SP63WZ3QFVB8WJ40"
title: "PHP date format guide with practical examples"
slug: "php-date-format"
author: "benjamincrozat"
description: "Learn PHP date format patterns with date() and DateTimeImmutable::format(), including escaping, timestamps, time zones, and copy-ready examples."
categories:
  - "php"
published_at: 2026-03-20T17:00:42Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-date-format.png"
sponsored_at: null
---
## Introduction

**To format a date in PHP, use `date()` for quick timestamp formatting or `DateTimeImmutable::format()` when the timezone belongs to the date itself.**

Here is the fast answer:

```php
echo date('Y-m-d');

$date = new DateTimeImmutable('2026-03-20 14:30:45', new DateTimeZone('Europe/Paris'));
echo $date->format('Y-m-d H:i:s T');
```

Most articles stop at the token list. The real question is usually one of these instead:

- which format string should I actually use?
- when is `date()` enough?
- when should I switch to `DateTimeImmutable`?
- why did my timezone or escaping behave strangely?

That is what this guide covers.

## `date()` vs `DateTimeImmutable::format()`

This is the decision that makes the rest easier.

### Use `date()` for quick formatting

[`date()`](https://www.php.net/manual/en/function.date.php) formats a Unix timestamp, or the current time if you omit the second argument:

```php
echo date('Y-m-d H:i:s');
echo date('Y-m-d', 1710938096);
```

It is fine for small scripts, logs, and simple output.

### Use `DateTimeImmutable::format()` when the date has context

[`DateTimeInterface::format()`](https://www.php.net/manual/en/datetime.format.php) works on a real date object that can carry a timezone and microseconds:

```php
$date = new DateTimeImmutable('2026-03-20 14:30:45', new DateTimeZone('Europe/Paris'));

echo $date->format('Y-m-d H:i:s T');
```

That usually makes it the better default in application code.

Why? The PHP manual warns that Unix timestamps do not handle timezones. `date()` also always outputs `000000` for microseconds because it takes an `int` timestamp, while `DateTimeImmutable::format()` can preserve them.

## What about `date_format()`?

This is an easy point of confusion.

[`date_format()`](https://www.php.net/manual/en/function.date-format.php) is the procedural alias of `DateTime::format()`.

That means these two lines do the same job:

```php
$date = date_create('2026-03-20 14:30:45', timezone_open('Europe/Paris'));

echo date_format($date, 'Y-m-d H:i:s T');
echo $date->format('Y-m-d H:i:s T');
```

If you are writing fresh code, I would usually prefer `DateTimeImmutable` with `$date->format(...)` because it reads more clearly and avoids accidental mutation.

## PHP date format cheat sheet

These are the patterns most people actually need:

| Format | Output example | Best use |
| --- | --- | --- |
| `Y-m-d` | `2026-03-20` | database-style date |
| `Y-m-d H:i:s` | `2026-03-20 14:30:45` | database-style datetime |
| `d/m/Y` | `20/03/2026` | day-first UI |
| `m/d/Y` | `03/20/2026` | US-style UI |
| `F j, Y` | `March 20, 2026` | readable long date |
| `D, d M Y H:i:s O` | `Fri, 20 Mar 2026 14:30:45 +0100` | email and headers |
| `H:i` | `14:30` | 24-hour time |
| `h:i A` | `02:30 PM` | 12-hour time |
| `DATE_ATOM` | `2026-03-20T14:30:45+01:00` | machine-friendly ISO output |
| `DATE_RFC3339` | `2026-03-20T14:30:45+01:00` | APIs and feeds |

If you only remember one pattern, make it `Y-m-d H:i:s`.

## The most useful PHP date format characters

You do not need to memorize the full reference table.

These are the tokens that matter most in daily PHP work:

| Token | Meaning | Example |
| --- | --- | --- |
| `Y` | 4-digit year | `2026` |
| `y` | 2-digit year | `26` |
| `m` | month with leading zero | `03` |
| `n` | month without leading zero | `3` |
| `d` | day with leading zero | `20` |
| `j` | day without leading zero | `20` |
| `H` | hour, 24-hour format | `14` |
| `h` | hour, 12-hour format | `02` |
| `i` | minutes | `30` |
| `s` | seconds | `45` |
| `F` | full month name | `March` |
| `M` | short month name | `Mar` |
| `D` | short weekday | `Fri` |
| `l` | full weekday | `Friday` |
| `T` | timezone abbreviation | `CET` |
| `P` | timezone offset | `+01:00` |
| `u` | microseconds | `123456` |
| `v` | milliseconds | `123` |

That is enough to build most format strings without opening the manual every time.

## Common PHP date format examples

### ISO-like date

```php
echo date('Y-m-d');

// 2026-03-20
```

### Date and time for storage or logs

```php
echo date('Y-m-d H:i:s');

// 2026-03-20 14:30:45
```

### Human-readable date

```php
echo date('F j, Y');

// March 20, 2026
```

### 24-hour vs 12-hour time

```php
echo date('H:i');
echo PHP_EOL;
echo date('h:i A');

// 14:30
// 02:30 PM
```

### API-friendly output

```php
$date = new DateTimeImmutable('2026-03-20 14:30:45', new DateTimeZone('Europe/Paris'));

echo $date->format(DATE_ATOM);

// 2026-03-20T14:30:45+01:00
```

If the real task is checking which PHP version supports these constants and date APIs, [this PHP version guide](/latest-php-version) is the place to verify your upgrade path.

## How to format a Unix timestamp in PHP

This is the classic `date()` use case:

```php
$timestamp = 1710938096;

echo date('Y-m-d H:i:s', $timestamp);

// 2024-03-20 12:34:56
```

That works well when you already have a Unix timestamp.

If you are starting from a string like `2026-03-20 14:30:45`, using a `DateTimeImmutable` object is usually clearer than converting back and forth.

## How to format dates with `DateTimeImmutable`

This is the safer everyday pattern:

```php
$date = new DateTimeImmutable('2026-03-20 14:30:45', new DateTimeZone('Europe/Paris'));

echo $date->format('Y-m-d H:i:s T');

// 2026-03-20 14:30:45 CET
```

The nice part is that the date object keeps its own timezone context instead of depending on the global default timezone.

That makes debugging easier in apps, queues, and tests.

## Time zones: where `date()` starts to feel too small

This is the practical warning most date-format articles skip.

With `DateTimeImmutable`, changing the timezone is explicit:

```php
$date = new DateTimeImmutable('2026-03-20 14:30:45', new DateTimeZone('Europe/Paris'));

echo $date->format('Y-m-d H:i:s T');
echo PHP_EOL;
echo $date->setTimezone(new DateTimeZone('America/New_York'))->format('Y-m-d H:i:s T');

// 2026-03-20 14:30:45 CET
// 2026-03-20 09:30:45 EDT
```

With `date()`, the timezone comes from the current default timezone instead.

That is why `date()` is fine for quick formatting, but `DateTimeImmutable` is usually the better choice once user locale, queues, APIs, or multi-region behavior enters the picture.

If you are checking environment mismatches and suspect the wrong runtime or config is involved, [this PHP version check guide](/check-php-version) helps verify what your CLI and web server are actually running.

## How to escape text in a PHP date format string

Format characters are expanded unless you escape them with a backslash.

Example:

```php
echo date('Y-m-d \a\t H:i', 1710938096);

// 2024-03-20 at 12:34
```

Without escaping, letters like `a`, `t`, or `m` may be interpreted as format tokens and give you weird output.

This is one of the easiest PHP date bugs to miss because the result looks almost right.

## Microseconds and milliseconds

If you need fractional seconds, use `DateTimeImmutable::format()`, not `date()`.

```php
$date = DateTimeImmutable::createFromFormat(
    'Y-m-d H:i:s.u',
    '2026-03-20 14:30:45.123456',
    new DateTimeZone('UTC'),
);

echo $date->format('Y-m-d H:i:s.u');
echo PHP_EOL;
echo $date->format('Y-m-d H:i:s.v');

// 2026-03-20 14:30:45.123456
// 2026-03-20 14:30:45.123
```

That matters for logs, event processing, tracing, and APIs where second-level precision is not enough.

## When `date()` is not enough

Use `DateTimeImmutable` instead of `date()` when:

- the date has a specific timezone
- you need microseconds or milliseconds
- the value comes from parsing or transforming real date strings
- you want safer immutable behavior in app code

If you need localized month or weekday names, the PHP manual points to `IntlDateFormatter::format()`. `date()` and `DateTimeImmutable::format()` do not solve localization on their own.

## Conclusion

For quick formatting, `date()` is still fine. For real application code, `DateTimeImmutable::format()` is usually the better default because it keeps timezone and precision attached to the value you are formatting.

If you are still building out your PHP utility toolkit, these are the next reads I would keep open:

- [Check whether a string contains text in modern and older PHP](/php-string-contains)
- [Extract part of a string safely with `substr()` and `mb_substr()`](/php-substr)
- [Decode JSON in PHP without the usual edge-case mistakes](/php-json-decode)
