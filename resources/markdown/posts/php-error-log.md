---
id: "01KKEW278247QVW51TXXYX9XAE"
title: "PHP error_log(): how to use it and find your logs"
slug: "php-error-log"
author: "benjamincrozat"
description: "Use PHP error_log() to write useful debug messages, check the right log on Apache, Nginx, CLI, or Docker, and fix the setups where nothing seems to appear."
categories:
  - "php"
published_at: 2023-06-23T22:00:00Z
modified_at: 2026-03-18T20:45:00Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-error-log.png"
sponsored_at: null
---
## Introduction

**Use [`error_log()`](https://www.php.net/manual/en/function.error-log.php) when you want PHP to write a message to its configured error log.**

For most debugging jobs, the shortest useful example is this:

```php
error_log('Checkout failed: missing Stripe customer ID');
```

That sends a message to PHP's default logger. If the `error_log` directive is not set, PHP sends errors to the SAPI logger instead, which means an Apache error log in mod_php or `stderr` in CLI. The tricky part is usually not the function call. It is knowing which log you are actually writing to.

This guide focuses on the practical questions people usually have:

- how to use `error_log()` without guessing
- where PHP logs go on Apache, Nginx, CLI, and Docker
- why `error_log()` sometimes looks like it is not working

## The simplest `error_log()` examples

### Log to the default PHP error log

```php
error_log('Database connection failed');
```

This uses message type `0`, which is the default. PHP sends the message to its configured logger.

### Append to a specific file

```php
error_log(
    '[import] Missing CSV header',
    3,
    __DIR__ . '/php-errors.log'
);
```

Message type `3` appends directly to the file you pass as the third argument.

One small gotcha matters here: PHP does **not** add a newline automatically for message type `3`, so this is often better:

```php
error_log(
    '[import] Missing CSV header' . PHP_EOL,
    3,
    __DIR__ . '/php-errors.log'
);
```

If you are writing to a file on purpose, prefer an absolute path so you do not have to guess where the file landed.

## How `error_log()` works in PHP

The current signature is:

```php
error_log(
    string $message,
    int $message_type = 0,
    ?string $destination = null,
    ?string $additional_headers = null
): bool
```

The practical message types are:

| Type | What it does | When to use it |
| --- | --- | --- |
| `0` | sends the message to PHP's default logger | your normal default |
| `3` | appends the message to the file in `$destination` | you want a specific file |
| `4` | sends the message directly to the SAPI logging handler | you know you want the SAPI logger |

Type `1` sends the message by email, but that is rarely the best logging strategy in modern apps. Type `2` is no longer used.

## Where does PHP `error_log` go?

This depends on how PHP is running and whether you set the `error_log` directive yourself.

If `error_log` is configured, that file wins.

If it is not configured, PHP sends errors to the SAPI logger. The PHP manual gives two concrete examples: Apache's error log for Apache setups, and `stderr` for CLI.

Here is the short version:

| Environment | Usually check here first | Why |
| --- | --- | --- |
| Apache with mod_php | Apache error log or the file set in `error_log` | Apache is the active SAPI logger |
| Nginx with PHP-FPM | your configured `error_log` file or the PHP-FPM log | FPM handles PHP's logging path |
| CLI | `stderr` or wherever you redirected it | CLI uses the SAPI logger |
| Docker | container logs if PHP is writing to `stderr`, otherwise the configured file inside the container | containerized apps often surface stderr through container logs |

The important point is that CLI, FPM, and Apache do **not** necessarily read the same php.ini or write to the same place.

If you are not sure which config is active:

- run `php --ini` for CLI
- run `php -i | grep error_log` for CLI values
- load `phpinfo()` in the browser for the web SAPI

That is usually faster than guessing log paths from memory.

## Configure PHP error logging in `php.ini`

If you want PHP to write to one clear file, configure it explicitly:

```ini
log_errors = On
error_log = /var/log/php/php_errors.log
```

After that, restart the thing running PHP:

- Apache
- PHP-FPM
- the PHP built-in server
- your container

If you skip the restart, you can end up checking the right file with the wrong running config.

## Nginx and PHP-FPM gotcha

On PHP-FPM, pool settings can override what your script tries to do.

For example:

```ini
php_admin_value[error_log] = /var/log/fpm-php.www.log
php_admin_flag[log_errors] = on
```

The PHP-FPM manual notes that settings passed with `php_admin_value` and `php_admin_flag` cannot be overridden with `ini_set()`.

So if you change logging in code and nothing happens, check your FPM pool config before you assume `error_log()` is broken.

## Practical `error_log()` patterns that help

### Add context to every message

```php
error_log(sprintf(
    '[checkout] user=%d order=%d status=%s',
    $userId,
    $orderId,
    $status,
));
```

Flat strings with useful context are easier to grep later than vague messages like `"it failed"`.

### Log arrays or payloads as JSON

```php
error_log(
    '[webhook] ' . json_encode(
        $payload,
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    )
);
```

This is usually cleaner than dumping raw arrays into a log file.

### Log exceptions with the part you actually need

```php
try {
    // ...
} catch (Throwable $e) {
    error_log(sprintf(
        '[billing] %s in %s:%d',
        $e->getMessage(),
        $e->getFile(),
        $e->getLine(),
    ));
}
```

That gives you a traceable message without exposing the error to users.

## Why `error_log()` seems not to work

These are the most common reasons:

- `log_errors` is off, so PHP is not writing anything
- you are checking the CLI config while the bug happens in FPM or Apache
- your FPM pool sets `php_admin_value[error_log]`, so your script-level changes are ignored
- the target file is not writable by the PHP user
- you used message type `3` but forgot the destination file
- you used message type `3` and forgot to add `PHP_EOL`, so the output looks broken
- you are looking for a file while PHP is actually writing to `stderr`

That last point is especially common in local Docker setups. If PHP is writing to `stderr`, the useful place to look is often `docker logs`, not a file path inside your app directory.

## `error_log()` vs `var_dump()` and `dump()`

Use `error_log()` when you want a message that survives the request and is easy to tail later.

Use `var_dump()`, `dump()`, or `dd()` when you want to inspect data immediately during development and you do not mind sending output to the screen.

If your real need is to inspect a big array or object cleanly, [this PHP print array guide](/php-laravel-print-array) is the better next step.

If you are still tracking down noisy PHP failures, these are the next reads I would keep open:

- [Show every PHP error safely](/php-show-all-errors)
- [Find the active php.ini file without guessing](/php-ini-location)
- [Check the PHP version before you chase the wrong bug](/check-php-version)
- [Handle exceptions without losing the useful context](/php-exceptions)
