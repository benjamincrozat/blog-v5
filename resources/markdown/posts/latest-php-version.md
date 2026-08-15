---
id: "01KM5MRH8AV63PMH55FRWJ0V73"
title: "Latest PHP version: current release and support status"
slug: "latest-php-version"
author: "benjamincrozat"
description: "See the latest stable PHP release, the current PHP 8.6 testing build, and the active and security support dates for PHP 8.2 through 8.5."
categories:
  - "php"
published_at: 2026-03-20T12:50:44Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/latest-php-version.png"
sponsored_at: null
---
**PHP 8.5 is the latest stable PHP branch, and PHP 8.5.9 is the latest stable release as of August 15, 2026.**

PHP 8.6.0 Beta 1 is also available for testing. It is a preview, not the version I would deploy to production.

| What you mean by “latest” | Version | Release date | Use it for production? |
| --- | --- | --- | --- |
| Latest stable PHP | PHP 8.5.9 | July 30, 2026 | Yes, when your stack supports it |
| Latest PHP 8.6 preview | PHP 8.6.0 Beta 1 | August 13, 2026 | No, test only |

The stable release number comes from PHP's [official release index](https://www.php.net/releases/). The preview status comes from the [PHP 8.6 Beta 1 announcement](https://www.php.net/archive/2026.php#2026-08-13-1).

## Supported PHP versions right now

PHP supports a branch actively for two years, then provides two more years of critical security fixes.

| Version | Initial release | Active support until | Security support until | Status on August 15, 2026 |
| --- | --- | --- | --- | --- |
| PHP 8.2 | December 8, 2022 | December 31, 2024 | December 31, 2026 | Security fixes only |
| PHP 8.3 | November 23, 2023 | December 31, 2025 | December 31, 2027 | Security fixes only |
| PHP 8.4 | November 21, 2024 | December 31, 2026 | December 31, 2028 | Active support |
| PHP 8.5 | November 20, 2025 | December 31, 2027 | December 31, 2029 | Current stable branch |

These dates come from PHP's [supported versions table](https://www.php.net/supported-versions.php). PHP 8.1 and older are already out of support.

## Which PHP version should you use?

- **New project:** use PHP 8.5 when your framework, packages, host, and extensions support it.
- **Conservative production target:** PHP 8.4 is still actively supported through December 31, 2026.
- **PHP 8.3 or 8.2:** plan the upgrade. Both branches now receive security fixes only.
- **PHP 8.1 or older:** treat the move as current upgrade work, not future maintenance.
- **PHP 8.6 beta:** use it in a disposable test environment to find compatibility problems early.

For Laravel projects, check the framework at the same time. Laravel 13 supports PHP 8.3 through 8.5; my [Laravel versions page](/laravel-versions) keeps that support map separate.

## Stable release versus preview release

PHP uses preview labels in a deliberate order:

1. Alpha
2. Beta
3. Release candidate (RC)
4. Stable

A beta is useful for package maintainers and teams preparing an upgrade. It can still change and it is not covered like a stable branch.

If your goal is to run a production app, “latest PHP” means **PHP 8.5.9**, not PHP 8.6.0 Beta 1.

## Check the PHP version you are actually using

Run:

```bash
php -v
```

That reports the command-line PHP binary. It may differ from the PHP-FPM or Apache version serving your website.

For the full diagnosis—including multiple binaries, `php.ini`, Laravel, Docker, and web-server differences—use [How to check your PHP version quickly](/check-php-version).

Also check the PHP constraint in `composer.json`:

```json
{
    "require": {
        "php": "^8.4 || ^8.5"
    }
}
```

Then ask Composer whether the current machine satisfies every installed package:

```bash
composer check-platform-reqs
```

## Verify the latest release yourself

Use these official pages instead of trusting a version number copied months ago:

- [PHP releases](https://www.php.net/releases/) for stable point releases
- [PHP supported versions](https://www.php.net/supported-versions.php) for lifecycle dates
- [PHP news archive](https://www.php.net/archive/2026.php) for alpha, beta, and release-candidate announcements

If you are deciding what to do after checking the version, these are the useful next steps:

- [Check which PHP binary your app actually runs](/check-php-version)
- [See what changed in PHP 8.5](/php-85)
- [Review PHP 8.4 if you need a more conservative target](/php-84)
- [Match your Laravel version against current PHP support](/laravel-versions)
