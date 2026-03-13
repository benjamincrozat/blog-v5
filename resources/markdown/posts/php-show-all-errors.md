---
id: "01KKEW27MFE4P3CS7MMWKGPEHX"
title: "How to show all errors in PHP safely"
slug: "php-show-all-errors"
author: "benjamincrozat"
description: "Show all PHP errors with E_ALL, php.ini, .user.ini, .htaccess, or CLI flags, then switch back to production-safe logging."
categories:
  - "php"
published_at: 2023-10-07T00:00:00+02:00
modified_at: 2026-03-13T11:30:00Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/Tf6C7ooxyLR7PCE.jpg"
sponsored_at: null
---
## Introduction

Need to show all errors in PHP while you debug? This guide shows the fastest safe setup with `E_ALL`, plus the right php.ini, `.user.ini`, `.htaccess`, and CLI flags for PHP 8.1-8.4. When I debug locally, I enable display errors; before I deploy, I switch back to logging only.

By the way: PHP 8.4 was released on November 21, 2024. See the official news archive on [php.net](https://www.php.net/archive/2024.php). In PHP 8.4, the `E_STRICT` level was removed and the `E_STRICT` constant was deprecated. Referencing it can emit a deprecation notice. See the 8.4 notes in [migration 8.4 incompatible changes](https://www.php.net/manual/en/migration84.incompatible.php). Also, the numeric value of `E_ALL` changed in PHP 8.4 from `32767` to `30719`. If you use numeric masks (for example in Apache config), update them. See the summary at [php.watch on E_STRICT/8.4](https://php.watch/versions/8.4/E_STRICT-deprecated).

## TL;DR

- Development snippet (top of your script):

```php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Alternatively:
error_reporting(-1); // -1 equals E_ALL
```

- php.ini: set `display_errors=On`, `display_startup_errors=On`, `error_reporting=E_ALL` (or `-1`).
- .htaccess (mod_php only): use numerics: `php_flag display_errors On`, `php_flag display_startup_errors On`, `php_value error_reporting -1`.
- PHP-FPM: use `.user.ini` or your FPM pool config; `ini_set()` cannot override `php_admin_*` flags.
- Production defaults: `display_errors=Off`, `log_errors=On`, `error_reporting=E_ALL`.

## Quick snippet (E_ALL)

Place this at the very top of your script to show all errors during development:

```php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Alternatively:
error_reporting(-1); // -1 equals E_ALL
```

Warning: if a fatal or parse error happens before this runs, it will not display. Set these directives in php.ini, a per-directory `.user.ini`, your PHP-FPM pool, or use an [`auto_prepend_file`](https://www.php.net/manual/en/ini.core.php#ini.auto-prepend-file) so they run earlier. See the manual’s [runtime configuration](https://www.php.net/manual/en/errorfunc.configuration.php).

## What each setting does

- display_errors: Prints errors to output. Use `1` to show, `0` to hide. In CLI you can set the special value `stderr` so errors go to the error stream. See [runtime configuration](https://www.php.net/manual/en/errorfunc.configuration.php).
- display_startup_errors: Shows errors during PHP startup. Useful for setup issues. Turn off in production.
- error_reporting(E_ALL): Reports all errors, warnings, notices, and deprecations. The `@` suppression operator still silences specific calls.

## Configure by environment

### php.ini

Use php.ini for global defaults or to ensure even parse errors are visible while debugging.

Steps:
1. Find the active php.ini with `php --ini` (CLI) or by calling `phpinfo()` in a browser.
2. Set development values:

```ini
display_errors = On
display_startup_errors = On
error_reporting = E_ALL

; or, equivalently:
error_reporting = -1
```

3. Restart your SAPI (Apache, Nginx + PHP-FPM, or the PHP built-in server). If errors still do not appear, see Troubleshooting below.

Helpful references: [runtime configuration](https://www.php.net/manual/en/errorfunc.configuration.php).

### Apache .htaccess (mod_php only)

If and only if you run PHP as an Apache module (mod_php), you can enable error display per directory in `.htaccess`:

```apache
# mod_php only, has no effect with PHP-FPM
php_flag display_errors On
php_flag display_startup_errors On
php_value error_reporting -1
```

Use numeric values in Apache config/.htaccess; constants like `E_ALL` are not recognized here. See [changing PHP configuration](https://www.php.net/manual/en/configuration.changes.php).

Important: these directives do nothing on PHP-FPM and may cause a 500 error if used there. See WordPress guidance on [display_errors across environments](https://developer.wordpress.org/advanced-administration/security/hardening/display-errors/).

### .user.ini (PHP-FPM) and FPM pool directives

On PHP-FPM and other FastCGI setups, use a `.user.ini` (per directory) or pool configuration (per site) instead of `.htaccess`.

.user.ini example (place in your web root or a specific app directory):

```ini
display_errors = 1
display_startup_errors = 1
error_reporting = E_ALL
; or: error_reporting = -1
```

Notes:
- `.user.ini` is read only by CGI/FastCGI SAPIs. Apache mod_php uses `.htaccess` instead. See [.user.ini files](https://www.php.net/manual/en/configuration.file.per-user.php).
- Changes may take up to `user_ini.cache_ttl` seconds (default 300) to apply.

PHP-FPM pool example (e.g., `/etc/php/8.3/fpm/pool.d/www.conf`):

```ini
; Per-pool hard overrides.
php_admin_flag[display_errors] = Off
php_admin_flag[log_errors] = On
php_admin_value[error_log] = /var/log/php_errors.log

; Soft overrides (can be replaced by ini_set).
php_flag[display_errors] = On
php_value[error_reporting] = E_ALL
```

Settings defined with `php_admin_value` and `php_admin_flag` cannot be overridden by `ini_set()`. The pool config wins. Verify in `phpinfo()`. See the FPM manual: [install.fpm.configuration](https://www.php.net/manual/en/install.fpm.configuration.php).

### CLI one-off flags

For quick checks on the command line, set directives per run:

```bash
php -d display_errors=stderr -d display_startup_errors=1 -d error_reporting=E_ALL script.php
```

Or with the equivalent numeric setting:

```bash
php -d display_errors=stderr -d display_startup_errors=1 -d error_reporting=-1 script.php
```

Using `display_errors=stderr` sends errors to the error stream, which is useful for CI and shell pipelines. See [runtime configuration](https://www.php.net/manual/en/errorfunc.configuration.php).

## Verify and diagnose

- Check current values (CLI): `php -i | grep error_reporting` and `php -i | grep display_errors`.
- Syntax check for parse errors: `php -l file.php`.
- Compare SAPIs: run `phpinfo()` in the web SAPI and `php -i` in CLI. They often differ (for example, `cli` vs `fpm-fcgi`).
- `.user.ini` delay: remember `user_ini.cache_ttl` can delay changes (default 300 seconds). See [runtime configuration](https://www.php.net/manual/en/errorfunc.configuration.php).
- Numeric masks: on PHP 8.4, `E_ALL` is `30719` (previously `32767`). If you must use numbers (Apache config), use `-1` or the correct value for your version.

## Troubleshooting: errors still not showing

- A fatal or parse error happens before your `ini_set` line executes. Configure php.ini, a `.user.ini`, or your FPM pool instead, or use an [`auto_prepend_file`](https://www.php.net/manual/en/ini.core.php#ini.auto-prepend-file) so settings are applied earlier.
- You edited the wrong php.ini or the wrong SAPI. Confirm with `php --ini` (CLI) or `phpinfo()` in the web SAPI. Sentry’s guide also shows common locations: see [How do I get PHP errors to display](https://sentry.io/answers/how-do-i-get-php-errors-to-display/).
- You used Apache `.htaccess` directives on a PHP-FPM host. Switch to `.user.ini` or FPM pool configuration. See WordPress’s [display_errors](https://developer.wordpress.org/advanced-administration/security/hardening/display-errors/).
- A framework or custom error handler is intercepting errors. Check your framework toggles (for example, WordPress `WP_DEBUG`, Laravel `APP_DEBUG`, Symfony `APP_ENV=dev`).
- The `@` operator is suppressing output for specific calls. Search your codebase for `@` to find silenced calls.
- FPM pool uses `php_admin_flag`/`php_admin_value` to lock settings. `ini_set` cannot override these. See [install.fpm.configuration](https://www.php.net/manual/en/install.fpm.configuration.php).
- The error log path is wrong or not writable. Verify `log_errors=On` and a valid `error_log` path, then tail the file.

## Production: hide display, log everything

In production, disable display, enable logging, and report all errors. I prefer to log `E_ALL`, including deprecations, so upgrades do not hide future issues.

```php
// Production-safe defaults.
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// Recommended: log everything, including deprecations.
error_reporting(E_ALL);

// If you must temporarily suppress deprecations in logs:
// error_reporting(E_ALL & ~E_DEPRECATED);
```

Do not use `E_STRICT` in masks on PHP 8.4+. The level was removed and the constant is deprecated; referencing it can trigger a deprecation notice. See [migration 8.4 incompatible changes](https://www.php.net/manual/en/migration84.incompatible.php).

Server-level logging example (php.ini):

```ini
log_errors = On
error_log = /var/log/php_errors.log
```

On FPM, prefer pool-level `php_admin_flag[log_errors] = On` and set `php_admin_value[error_log]` to enforce logging.

## Consider advanced debugging tools

For deep debugging in development, use Xdebug, or add error monitoring so production errors are captured without exposing them to users. See Sentry’s step-by-step guide on [getting PHP errors to display](https://sentry.io/answers/how-do-i-get-php-errors-to-display/) and its [PHP SDK docs](https://docs.sentry.io/platforms/php/). Stackify’s overview also covers the php.ini, in-code, and `.htaccess` paths with the key parse-error caveat: [How to display all PHP errors](https://stackify.com/display-php-errors/).

## FAQs

### How do I show PHP errors with .htaccess?

Use this only with Apache mod_php, and use numerics:

```apache
php_flag display_errors On
php_flag display_startup_errors On
php_value error_reporting -1
```

Constants like `E_ALL` are not recognized in Apache config. See [changing PHP configuration](https://www.php.net/manual/en/configuration.changes.php).

### Why aren’t my PHP errors showing even with display_errors=On?

Common reasons: wrong SAPI (CLI vs web), a parse error before your code runs, `.htaccess` directives on an FPM host, `php_admin_*` flags in your FPM pool locking settings, or `.user.ini` cache delay. Check with `phpinfo()`, run `php -l file.php`, and review FPM pool config. See [runtime configuration](https://www.php.net/manual/en/errorfunc.configuration.php) and [install.fpm.configuration](https://www.php.net/manual/en/install.fpm.configuration.php).

### What’s the difference between E_ALL and -1?

For `error_reporting`, `-1` equals `E_ALL`. Use whichever is clearer. See [error handling constants](https://www.php.net/manual/en/errorfunc.constants.php).

### How do I send errors to stderr in CLI?

Run scripts with:

```bash
php -d display_errors=stderr -d error_reporting=-1 script.php
```

See [runtime configuration](https://www.php.net/manual/en/errorfunc.configuration.php).

### How do I enable errors on PHP-FPM?

Use a `.user.ini` or your FPM pool config. Remember that `.user.ini` changes can be delayed by `user_ini.cache_ttl`. `ini_set()` cannot override `php_admin_*` pool flags. See [.user.ini files](https://www.php.net/manual/en/configuration.file.per-user.php) and [install.fpm.configuration](https://www.php.net/manual/en/install.fpm.configuration.php).

### Is E_STRICT still needed on PHP 8.4?

No. `E_STRICT` was removed in PHP 8.4 and the constant is deprecated. Referencing it can trigger a deprecation notice. See [migration 8.4 incompatible changes](https://www.php.net/manual/en/migration84.incompatible.php). Also note: `E_ALL`’s numeric value is `30719` on PHP 8.4 (previously `32767`).

## Conclusion

Use `E_ALL` and enable display locally to fix issues fast, then deploy with display disabled and comprehensive logging. Choose the right place to configure it: php.ini for global or early errors, `.htaccess` for Apache mod_php, `.user.ini` or FPM pool settings for PHP-FPM, and `-d` flags for one-off CLI runs. With PHP 8.4 now mainstream, drop `E_STRICT` and prefer logging everything so deprecations are visible during upgrades. For reference materials, see [error_reporting()](https://www.php.net/error_reporting), [ini_set()](https://www.php.net/ini_set), [runtime configuration](https://www.php.net/manual/en/errorfunc.configuration.php), and WordPress’s [display_errors](https://developer.wordpress.org/advanced-administration/security/hardening/display-errors/).

If you are in the middle of troubleshooting and want the rest of the basics close at hand, these are the next reads I would open:

- [Find the php.ini file that's actually affecting your setup](/php-ini-location)
- [Check whether your PHP version is part of the problem](/check-php-version)
- [See what PHP 8.3 changed before you rely on it](/php-83)
