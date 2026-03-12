---
id: "01KKEW27CG49GRV02BM10BPV0S"
title: "Laravel Herd: the simplest way to install PHP on macOS and Windows"
slug: "laravel-herd"
author: "benjamincrozat"
description: "Install PHP on macOS or Windows in minutes with Laravel Herd. Free core includes PHP, nginx, and Node.js; supports PHP 7.4–8.4. Herd Pro adds services and debugging features."
categories:
  - "laravel"
  - "tools"
published_at: 2023-07-19T00:00:00+02:00
modified_at: 2025-09-30T16:39:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6DQVFEZX3FNMC12QNBH9A0N.png"
sponsored_at: null
---
## Introduction

[Laravel Herd](https://herd.laravel.com) is a free, native app for local PHP development. It runs on macOS and Windows. It bundles PHP, nginx, dnsmasq, and nvm for managing Node.js. Herd was introduced at [Laracon US](https://laracon.us) on July 19, 2023 and developed by [Beyond Code GmbH](https://beyondco.de) for the Laravel team. If you prefer a Homebrew workflow, I also wrote about [Laravel Valet](https://benjamincrozat.com/install-php-mac-laravel-valet).

## Install Laravel Herd on macOS

### Requirements

- macOS 12 or later

### Steps

1. [Download Herd](https://herd.laravel.com/download) from the official site.
2. Open the disk image and drag the app to your Applications folder.
3. Launch Herd and follow the setup. You can start fresh or import your [Laravel Valet](https://benjamincrozat.com/install-php-mac-laravel-valet) sites.

Herd includes nvm, so it can install and switch Node.js versions for you.

### Verify your setup

Run these commands in Terminal:

```bash
herd --version
php --version
laravel --version
composer --version
node --version
```

## Install Laravel Herd on Windows

### Requirements

- Windows 10 or later

### Steps

1. [Download Herd for Windows](https://herd.laravel.com/windows/).
2. Run the installer, then open Herd and follow the prompts.
3. For details, see the [Herd for Windows install docs](https://herd.laravel.com/docs/windows).

### Verify your setup

Run these commands in PowerShell:

```powershell
herd --version
php --version
laravel --version
composer --version
node --version
```

## Supported PHP versions

Herd supports PHP 7.4, 8.0, 8.1, 8.2, 8.3, and 8.4. New installs default to PHP 8.4 since January 10, 2025. See the [supported PHP versions](https://herd.laravel.com/docs/1/technology/php-versions) and the macOS [updates page](https://herd.mintlify.dev/docs/macos/getting-started/updates) for details.

## Included PHP extensions

Herd includes many extensions out of the box, including:

- bcmath
- bz2
- calendar
- ctype
- curl
- dba
- dom
- exif
- ffi
- fileinfo
- filter
- ftp
- gd
- gmp
- iconv
- igbinary
- imagick
- imap
- intl
- ldap
- lz4
- mbstring
- mongodb
- mysqli
- opcache
- openssl
- pcntl
- pdo
- pdo_mysql
- pdo_pgsql
- pdo_sqlite
- pdo_sqlsrv
- pgsql
- phar
- posix
- readline
- redis
- session
- shmop
- simplexml
- soap
- sockets
- sodium
- sqlite3
- sqlsrv
- sysvmsg
- sysvsem
- sysvshm
- tokenizer
- xml
- xmlreader
- xmlwriter
- xsl
- zip
- zlib

Availability can vary by OS. For the full, always‑current list, see the official page on [included PHP extensions](https://herd.laravel.com/docs/1/technology/php-extensions).

### How to add extensions

Although Herd includes many extensions, you can add more.

- On macOS: use Homebrew or PECL, then enable the extension and restart Herd. For example:

```bash
pecl install xdebug
# then enable it in your php.ini if needed
herd restart
```

- On Windows: download the matching DLL for your PHP version, place it in the ext folder, enable it in php.ini (for example, `extension=php_intl.dll`), then restart Herd.

See the step‑by‑step guide in the [PHP extensions docs](https://herd.laravel.com/docs/1/technology/php-extensions).

## The strengths of Laravel Herd compared to Valet

- Everything you need is bundled. I thought Valet was simple, but Herd is even simpler for a quick start.
- No Homebrew or Docker required for the core setup.
- The team says your tests and web requests run faster with Herd. See the claim on the [Herd site](https://herd.laravel.com).

## The limitations of Laravel Herd compared to Valet

Using Herd, you may hit these limits:

- You cannot install PHP versions before 7.4.
- Herd does not include every extension by default, but you can add more. See How to add extensions above.

## Herd Pro: features and pricing

Herd Basic is free. Herd Pro costs $99 for a one‑year license, and you can activate it on two devices. A teams option is available. Learn more on the [Herd Pro page](https://herd.laravel.com).

Herd Pro adds:

- Mail viewer
- Dumps inspector
- Log viewer
- Services: MySQL, MariaDB, PostgreSQL, Redis, Typesense, Meilisearch, MinIO, Laravel Reverb
- Xdebug detection and helpers

## Switch PHP versions per project

Use Herd to switch globally or per project:

```bash
# set the global default
herd use 8.4

# in a project folder, pin a version
cd ~/Sites/my-app
herd isolate 8.1

# remove the pin
herd unisolate
```

See the full guide on [managing PHP versions](https://herd.laravel.com/docs/1/technology/php-versions).

## Herd vs Valet vs Sail

- Choose Herd if you want zero dependencies, a simple GUI, and optional Pro services.
- Choose Valet if you like the Homebrew workflow and a lightweight nginx setup. See the [Valet docs](https://laravel.com/docs/10.x/valet) and my [Valet guide](https://benjamincrozat.com/install-php-mac-laravel-valet).
- Choose Sail if you want Docker containers that mirror production and need many services.

## Troubleshooting basics

- Restart services from the Herd menu, or run:

```bash
herd restart
```

- On macOS, use the menu option Force stop all if something is stuck, then start again.

## Platform availability

Herd is available on macOS and Windows. A Linux version is not planned currently. Get the Windows build on the [Herd for Windows page](https://herd.laravel.com/windows/).

## Conclusion

If you need to install PHP on macOS or Windows fast, Herd is the easiest way. I suggest starting with the free Basic edition, then adding Pro if you want built‑in services and debugging tools. Next steps: [download Herd](https://herd.laravel.com/download), read the [Windows install docs](https://herd.laravel.com/docs/windows), and check the [PHP versions](https://herd.laravel.com/docs/1/technology/php-versions) and [extensions](https://herd.laravel.com/docs/1/technology/php-extensions) pages.

If you are piecing together your local PHP and Laravel setup, these next reads help with the rest of that environment:

- [PHP for Mac: get started fast using Laravel Valet](/laravel-valet)
- [How to install Laravel on macOS](/laravel-installation-macos)
- [6 ways to check your version of PHP](/check-php-version)
- [Laravel Forge: price, review and alternatives (2025)](/laravel-forge)
- [The 4 best Laravel cloud hosting providers for 2025 (+ my setup)](/best-laravel-hosting-providers)
