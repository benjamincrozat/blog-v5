---
id: "01KKEW27KVZP920YG58MHGQPEA"
title: "php.ini location: how to find the active file"
slug: "php-ini-location"
author: "benjamincrozat"
description: "Find your active `php.ini` location with `php --ini` or `phpinfo()`, then confirm whether CLI and PHP-FPM are using different configuration files."
categories:
  - "php"
published_at: 2023-11-02T00:00:00+01:00
modified_at: 2026-03-20T12:41:41Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K29KD5WZQ4SNNJG02F14A8QR.png"
sponsored_at: null
---
## Introduction

Need to find your `php.ini` file? Start with `php --ini` in the terminal or `phpinfo()` in the browser, then confirm whether CLI and PHP-FPM are reading the same file.

The exact path depends on how PHP is running. This guide shows the two quickest checks first, then the common locations developers usually expect on Linux, macOS, and Windows.

## What is php.ini and why does it matter?

The _php.ini_ file is PHP's main configuration file. You might tweak it to increase memory limits, adjust error reporting, or handle file uploads. The [PHP manual](https://www.php.net/manual/en/configuration.file.php) explains how PHP looks for the active file and how scan directories fit into that process.

## How to find your php.ini location with phpinfo()

The fastest browser check is usually `phpinfo()`:

*   Create a new PHP file, _index.php_, and add:

```php
<?php
phpinfo();
?>
```

*   Open the file in your web browser.

Look under “Loaded Configuration File” for the exact location. If that field differs from what you saw in the terminal, you are probably comparing CLI and web-server PHP.

![phpinfo showing php.ini location](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/php-ini-location-4f1fce8314bd74f7d44b.jpg/public)

## How to find your php.ini location in the terminal

For command-line checks, PHP provides a simple answer:

*   Open your terminal and run:

```bash
php --ini
```

The terminal output clearly shows the active _php.ini_ path and any additional scan directories.

![Terminal command to find php.ini](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/php-ini-location-0a2e20d55030ea90d1a3.jpg/public)

Alternatively, use:

```bash
php -i | grep "Loaded Configuration File"
```

## Common php.ini locations (by OS)

| Operating System | Typical php.ini Location |
| --- | --- |
| Ubuntu/Debian (CLI) | `/etc/php/<version>/cli/php.ini` |
| Ubuntu/Debian (Apache) | `/etc/php/<version>/apache2/php.ini` |
| CentOS/Fedora | `/etc/php.ini` or `/etc/php/<version>/php.ini` |
| macOS (Homebrew) | `/opt/homebrew/etc/php/<version>/php.ini` |
| Windows (XAMPP/WAMP) | `C:\xampp\php\php.ini` or `C:\wamp64\bin\php\php<version>\php.ini` |

## How to safely edit php.ini

*   Open the file in a text editor with admin privileges.
*   Make your changes (e.g., adjust `memory_limit = 256M`).
*   Save the file and restart your PHP service:

**Apache:**

```bash
sudo systemctl restart apache2
```

**PHP-FPM:**

```bash
sudo systemctl restart php<version>-fpm
```

## Troubleshooting FAQ

### Changes to php.ini not taking effect?

*   Ensure you are editing the correct file (verify via `php --ini` and `phpinfo()`).
*   Restart your PHP or web server after making the change.

### Multiple php.ini files found?

*   PHP CLI and the web server stack often use separate `php.ini` files.
*   Check the browser output from `phpinfo()` against `php --ini` in your terminal.

## Common php.ini directives cheat-sheet

| Directive | Default | Purpose |
| --- | --- | --- |
| memory_limit | 128M | Max memory per script |
| upload_max_filesize | 2M  | Max upload file size |
| post_max_size | 8M  | Max POST data size |
| max_execution_time | 30  | Max execution time (seconds) |
| error_reporting | E_ALL | Error reporting level |

## Conclusion

Now you know exactly how and where to find your _php.ini_ file, tweak it safely, and troubleshoot common issues. Bookmark this guide for quick reference anytime you need it.

If you are tuning PHP locally and want the rest of that troubleshooting picture nearby, these are the next reads I would open:

- [Show every PHP error when debugging gets vague](/php-show-all-errors)
- [Check whether your PHP version is part of the problem](/check-php-version)
- [Run PHP and Laravel more smoothly on macOS](/laravel-valet)
