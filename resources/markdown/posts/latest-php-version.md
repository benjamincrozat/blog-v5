---
id: "01KM5MRH8AV63PMH55FRWJ0V73"
title: "Latest PHP version: current release and support status"
slug: "latest-php-version"
author: "benjamincrozat"
description: "See the latest PHP version, which branches are still supported, when each support window ends, and how to tell whether your project is behind."
categories:
  - "php"
published_at: 2026-03-20T12:50:44Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/latest-php-version.png"
sponsored_at: null
---
## Introduction

**PHP 8.5 is the latest stable PHP version as of March 20, 2026.**

If you only need the short version:

- **PHP 8.5** is the current stable branch.
- **PHP 8.5.4** is the latest release visible on php.net, published on **March 12, 2026**.
- **PHP 8.4** is still in active support.
- **PHP 8.3** and **PHP 8.2** still receive security fixes, but they are no longer the best default for new work.

If you are starting a new project today, **PHP 8.5** is the right default when your hosting and dependencies support it.

## The latest PHP version right now

According to the official [PHP releases index](https://www.php.net/releases/index.php) and the [PHP 8.5 release announcement](https://www.php.net/releases/8.5/en.php):

- **PHP 8.5** is the latest stable major branch.
- **PHP 8.5.0** was released on **November 20, 2025**.
- The latest patch release published on php.net as of **March 20, 2026** is **PHP 8.5.4**, released on **March 12, 2026**.

That distinction matters because people often ask for the "latest PHP version" when they really mean one of two different things:

- the latest stable **major** branch: **PHP 8.5**
- the latest stable **point** release in that branch: **PHP 8.5.4**

## Supported PHP versions right now

Here is the current support picture from the official [PHP supported versions page](https://www.php.net/supported-versions.php), interpreted on **March 20, 2026**:

| Version | Release date | Active support until | Security support until | Status on March 20, 2026 |
| --- | --- | --- | --- | --- |
| PHP 8.2 | December 8, 2022 | December 31, 2024 | December 31, 2026 | Security fixes only |
| PHP 8.3 | November 23, 2023 | December 31, 2025 | December 31, 2027 | Security fixes only |
| PHP 8.4 | November 21, 2024 | December 31, 2026 | December 31, 2028 | Active support |
| PHP 8.5 | November 20, 2025 | December 31, 2027 | December 31, 2029 | Current stable release |

Anything older than **PHP 8.2** is already out of support.

## What this means in practice

### If you are on PHP 8.5

You are on the latest stable branch and in the healthiest place for new development.

### If you are on PHP 8.4

You are still on a strong production target. There is no panic here, but **PHP 8.5** is your natural next upgrade.

### If you are on PHP 8.3 or PHP 8.2

You are still covered for critical security fixes, but you are already past active support. That usually means fewer routine fixes, less breathing room, and more upgrade pressure than teams expect.

I would not start a new project on either branch unless you are boxed in by platform or dependency constraints.

### If you are on PHP 8.1 or older

You are on an unsupported branch. Treat that as upgrade work, not background maintenance.

## Which PHP version should you use for a new project?

My rule of thumb is simple:

- choose **PHP 8.5** when your framework, packages, and host already support it
- choose **PHP 8.4** if you need the more conservative target today
- avoid starting fresh on **PHP 8.3** or **PHP 8.2**

For Laravel specifically, this matters even more because framework support moves with PHP support. If your app is framework-based, this companion page helps line the two up:

[Laravel versions: latest release and support status](/laravel-versions)

## Where to verify the latest PHP release yourself

If you want the official answer instead of a third-party summary, these are the two pages worth bookmarking:

- the [PHP downloads page](https://www.php.net/downloads.php) for the current stable branch
- the [PHP supported versions page](https://www.php.net/supported-versions.php) for support windows and branch status

If you want the exact patch release data in the most direct form, use the [PHP releases index](https://www.php.net/releases/index.php) and the branch-specific release pages. That is where you can confirm whether the latest branch is still **8.5** and whether the latest point release has moved past **8.5.4**.

## How to tell whether your project is behind

The fastest first step is to check what runtime you are actually using:

```bash
php -v
```

That tells you the CLI version, which is often enough to spot whether you are still on **8.2**, **8.3**, or **8.4**.

If you are working in Laravel, this is a nice companion check:

```bash
php artisan about
```

If you are not sure whether the browser and CLI are using the same PHP binary, [this guide to checking your PHP version](/check-php-version) walks through the common methods.

One more practical check is your Composer constraint. Look at the `php` requirement in `composer.json`. If it still pins your project to an older branch, that is often why upgrades feel stuck.

For example:

```json
{
  "require": {
    "php": "^8.2"
  }
}
```

That does not mean your app is broken, but it does tell you your dependency policy is still built around PHP 8.2.

## Should you upgrade now?

My short recommendation:

- move to **PHP 8.5** for new projects and routine forward upgrades
- keep **PHP 8.4** if you need the safer compatibility target today
- plan upgrades off **PHP 8.3** and **PHP 8.2** instead of treating them as long-term resting points

The point is not to chase every release on day one. It is to avoid waking up on a branch that quietly slid from active support into security-only support, then into end of life.

## Conclusion

As of **March 20, 2026**, **PHP 8.5** is the latest stable PHP version, and **PHP 8.5.4** is the latest visible patch release on php.net. **PHP 8.4** is still a healthy supported branch, while **PHP 8.3** and **PHP 8.2** are now more of an upgrade runway than a long-term destination.

If you are deciding what to do next after checking the version map, these are the follow-up reads I would keep open:

- [Check which PHP version is actually running](/check-php-version)
- [See what changed in PHP 8.5](/php-85)
- [Review PHP 8.4 if you need a more conservative target](/php-84)
- [Match your Laravel version against current PHP support](/laravel-versions)
