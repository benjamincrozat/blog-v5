---
id: "01KKEW27MFE4P3CS7MMWKGPEHX"
title: "How to show all PHP errors safely"
slug: "php-show-all-errors"
author: "benjamincrozat"
description: "Show every PHP error with E_ALL, CLI flags, php.ini, Apache, or PHP-FPM, then keep production errors out of the response and in the log."
categories:
  - "php"
published_at: 2023-10-06T22:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/php-show-all-errors.png"
sponsored_at: null
---
To show every PHP error while developing, put this at the top of the script:

```php
ini_set('display_errors', '1');
error_reporting(E_ALL);
```

That does not catch a parse error in the same file because PHP must parse the file before it can run the snippet. Use CLI flags or configuration for startup and parse errors.

## Pick the setting that matches how PHP runs

| Situation | Best setup |
| --- | --- |
| One web script you can still execute | `ini_set('display_errors', '1')` and `error_reporting(E_ALL)` |
| One CLI command | `php -d display_errors=stderr -d display_startup_errors=1 -d error_reporting=-1 script.php` |
| Local PHP installation | Development values in the active `php.ini` |
| Apache with mod_php | `.htaccess` directives |
| Nginx or Apache with PHP-FPM | `.user.ini` or the FPM pool configuration |
| Production | Hide errors from responses and log `E_ALL` |

## Show all errors for one CLI command

This catches parse errors without changing a shared configuration file:

```bash
php -d display_errors=stderr -d display_startup_errors=1 -d error_reporting=-1 script.php
```

`stderr` keeps errors on the error stream, which is useful in shell scripts and CI. `-1` means every error level, including levels PHP may add later.

You can also check syntax without running the file:

```bash
php -l script.php
```

## Enable errors in php.ini

First find the configuration used by the PHP binary you are testing:

```bash
php --ini
```

Then use these development values:

```ini
display_errors = On
display_startup_errors = On
error_reporting = E_ALL
```

Restart PHP-FPM or Apache after changing a server-level `php.ini`. The CLI and web server can load different files, so compare both environments with my [PHP version and active binary guide](/check-php-version).

## Use .htaccess only with Apache mod_php

These directives work when PHP runs as an Apache module:

```apache
php_flag display_errors On
php_flag display_startup_errors On
php_value error_reporting 2147483647
```

They do not work with PHP-FPM and can turn the request into a 500 response. Apache configuration does not understand the `E_ALL` PHP constant, so the PHP manual recommends a large numeric mask that covers current and future error bits.

## Configure PHP-FPM with .user.ini or its pool

For a per-directory FastCGI configuration, create or update `.user.ini`:

```ini
display_errors = 1
display_startup_errors = 1
error_reporting = E_ALL
```

PHP checks `.user.ini` files on an interval. The default `user_ini.cache_ttl` is 300 seconds, so the change may not appear immediately.

For a pool-wide policy, update the relevant PHP-FPM pool:

```ini
php_admin_flag[display_errors] = Off
php_admin_flag[log_errors] = On
php_admin_value[error_log] = /var/log/php/app-errors.log
php_admin_value[error_reporting] = E_ALL
```

`php_admin_*` values cannot be changed by `ini_set()`. That is useful when the production pool must enforce safe values for every application.

The PHP manual documents both [where settings can be changed](https://www.php.net/manual/en/configuration.changes.php) and the [FPM pool syntax](https://www.php.net/manual/en/install.fpm.configuration.php).

## Use E_ALL instead of a copied number

In PHP code, `php.ini`, `.user.ini`, and PHP-FPM pool files, write `E_ALL`. `error_reporting(-1)` and the CLI value `-1` also cover every possible level. Apache configuration is different: PHP constants do not exist there, so use the documented numeric mask `2147483647`.

PHP 8.4 changed the numeric value of `E_ALL` to `30719` when the separate `E_STRICT` level was removed. A hardcoded number copied from an old post can silently miss future error levels. The [PHP 8.4 migration guide](https://www.php.net/manual/en/migration84.incompatible.php) has the exact change.

## Hide errors and log them in production

Production responses should not contain stack traces, paths, SQL, or configuration details. Use these server-level values:

```ini
display_errors = Off
display_startup_errors = Off
log_errors = On
error_reporting = E_ALL
error_log = /var/log/php/app-errors.log
```

Make sure the PHP process can write to the log path. I keep deprecations in the log because they give me upgrade warning before a dependency becomes incompatible.

Framework error pages are a separate switch. In Laravel, for example, `APP_DEBUG=false` controls the detailed application response; PHP's logging settings still decide where engine errors go.

## Why errors still do not appear

| Symptom | Likely cause | What to check |
| --- | --- | --- |
| A broken file shows a blank page | The parse error happens before `ini_set()` runs | Use `php -l`, CLI `-d` flags, or server configuration |
| CLI shows errors but the browser does not | CLI and PHP-FPM use different config | Compare `php --ini` with `php_ini_loaded_file()` in the browser |
| Adding `php_flag` causes HTTP 500 | The server uses PHP-FPM, not mod_php | Remove the directives and use `.user.ini` or the FPM pool |
| `ini_set()` returns false or changes nothing | A `php_admin_*` pool value locked the setting | Inspect the active FPM pool configuration |
| A framework shows its own error page | Its error handler intercepted the PHP error | Check the framework debug setting and its log |
| A single call stays silent | The `@` operator suppressed that call | Remove the operator while debugging |
| The log stays empty | Logging is off or the path is not writable | Check `log_errors`, `error_log`, ownership, and permissions |

To inspect the values PHP currently uses on the command line:

```bash
php -i | grep -E 'display_errors|display_startup_errors|error_reporting|error_log'
```

For a web request, use a temporary `phpinfo()` page or print the individual `ini_get()` values, then remove the page when you finish.

The short rule is simple: display errors in a controlled development environment, log `E_ALL` in production, and always edit the configuration used by the runtime that actually failed.

Related guides:

- [Find the php.ini file PHP actually loads](/php-ini-location)
- [Check the active PHP binary and version](/check-php-version)
- [Check the latest supported PHP branches](/latest-php-version)
