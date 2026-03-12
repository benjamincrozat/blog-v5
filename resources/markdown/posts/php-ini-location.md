---
id: "01KKEW27KVZP920YG58MHGQPEA"
title: "This is the location of your php.ini"
slug: "php-ini-location"
author: "benjamincrozat"
description: "Discover the location of your php.ini file using two simple methods: the phpinfo() function or the command line."
categories:
  - "php"
published_at: 2023-11-02T00:00:00+01:00
modified_at: 2025-07-27T07:20:00+02:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K29KD5WZQ4SNNJG02F14A8QR.png"
sponsored_at: null
---
## What is php.ini and why does it matter?

The _php.ini_ file is a PHP configuration file used to control your PHP environment’s behavior. You might tweak it to increase memory limits, adjust error reporting, or handle file uploads. [Official PHP docs](https://www.php.net/manual/en/ini.core.php) cover all directives in depth.

## Locate php.ini using phpinfo()

The fastest method PHP developers usually discover first is through `phpinfo()`:

*   Create a new PHP file, _index.php_, and add:

<?php
phpinfo();
?>

*   Open the file in your web browser.

Look under “Loaded Configuration File” for the exact location.

![phpinfo showing php.ini location](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/216/conversions/JAyCkwTofAYGl4PX3byMXdOJ8DUTcQ-metaQ2xlYW5TaG90IDIwMjMtMTEtMDIgYXQgMTcuMDQuNTBAMngucG5n--medium.jpg)

## Locate php.ini using the command line

For command-line enthusiasts, PHP provides simple commands:

*   Open your terminal and run:

php --ini

The terminal output clearly shows the active _php.ini_ path.

![Terminal command to find php.ini](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/218/conversions/IdaI8uqocUu45gmvwzENy2KOGSiec2-metaQ2xlYW5TaG90IDIwMjMtMTEtMDIgYXQgMTcuMTIuNTRAMngucG5n--medium.jpg)

Alternatively, use:

php -i | grep "Loaded Configuration File"

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

sudo systemctl restart apache2

**PHP-FPM:**

sudo systemctl restart php<version>-fpm

## Troubleshooting FAQ

### Changes to php.ini not taking effect?

*   Ensure you’re editing the correct file (verify via `php --ini`).
*   Restart your PHP/Apache server.

### Multiple php.ini files found?

*   PHP CLI and Web Server (Apache/Nginx) typically use separate php.ini files.
*   Check via `phpinfo()` in your browser vs. `php --ini` in your terminal.

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

If you want a few more PHP rabbit holes after this:

- [PHP: Show all errors (E_ALL) safely](/php-show-all-errors)
- [6 ways to check your version of PHP](/check-php-version)
- [Is PHP dead? Usage statistics and market share for 2023.](/php-is-dead-2023)

