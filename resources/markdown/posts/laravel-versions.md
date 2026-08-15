---
id: "01KKEW27EBJJREQW7SC8BS8P5Y"
title: "Laravel versions: latest release and support status"
slug: "laravel-versions"
author: "benjamincrozat"
description: "See the latest Laravel version, which majors still receive bug or security fixes, and the PHP version each supported release needs."
categories:
  - "laravel"
published_at: 2023-09-19T22:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/4IJU4CXwjE9Me2J.jpg"
sponsored_at: null
---
**Laravel 13 is the latest Laravel major release as of August 15, 2026.**

The important change this week is Laravel 12: its bug-fix window ended on **August 13, 2026**. It still receives security fixes until February 24, 2027.

| Version | PHP versions | Released | Bug fixes until | Security fixes until | Status on August 15, 2026 |
| --- | --- | --- | --- | --- | --- |
| Laravel 11 | 8.2-8.4 | March 12, 2024 | September 3, 2025 | March 12, 2026 | End of life |
| Laravel 12 | 8.2-8.5 | February 24, 2025 | August 13, 2026 | February 24, 2027 | Security fixes only |
| Laravel 13 | 8.3-8.5 | March 17, 2026 | Q3 2027 | March 17, 2028 | Current major |

Those dates come from Laravel's current [release and support table](https://laravel.com/docs/13.x/releases#support-policy).

## Which Laravel version should you use?

- **New application:** start on Laravel 13 unless a package or hosting constraint blocks it.
- **Laravel 12 application:** you are still covered for security fixes, but routine bugs are no longer patched. Plan the Laravel 13 upgrade.
- **Laravel 11 or older:** move to a supported major. Laravel 11 reached end of life on March 12, 2026.

Laravel 13 is a relatively small upgrade for many applications, but package compatibility still decides how easy the move will be. Read the [Laravel 13 upgrade guide](https://laravel.com/docs/13.x/upgrade) and run your real test suite before changing production.

## Laravel 13 is the current major

Laravel 13 was released on March 17, 2026 and requires PHP 8.3 or newer. The framework supports PHP 8.3, 8.4, and 8.5.

This page tracks the support decision. My separate [Laravel 13 guide](/laravel-13) covers the features and upgrade value so this version hub does not have to repeat release notes.

## Laravel 12 now receives security fixes only

Laravel 12's regular bug-fix support ended on August 13, 2026. Security support continues until February 24, 2027.

That does not mean every Laravel 12 app broke on August 14. It means new framework bugs outside the security scope are no longer promised fixes. Treat Laravel 12 as an upgrade runway, not a long-term target for new work.

## Laravel 11 is out of support

Laravel 11 stopped receiving security fixes on March 12, 2026.

If a project is still on Laravel 11, the practical path is usually:

1. update packages and remove abandoned dependencies
2. make the test suite reliable on Laravel 11
3. upgrade to Laravel 12
4. run the suite and fix deprecations
5. upgrade to Laravel 13

Laravel supports yearly major releases, so small regular upgrades are easier than waiting for several majors to pile up.

## Does Laravel still have LTS releases?

No. Laravel no longer designates new majors as long-term support releases.

The current policy gives each major:

- 18 months of bug fixes
- 2 years of security fixes
- a new major roughly once per year

Laravel 6 was the last LTS release. Waiting for another LTS branch is not an upgrade strategy.

## Check the version your app actually runs

Run either command inside the project:

```bash
php artisan --version
composer show laravel/framework
```

The first command is the quickest answer. The Composer command also shows the exact installed package release and its dependency constraints.

If `php artisan` does not start, check the runtime first with [my PHP version diagnostic guide](/check-php-version).

## Laravel and PHP support are separate clocks

A supported Laravel major can still run on a PHP branch that is close to end of life. Check both before planning an upgrade:

- [Latest PHP version and current support windows](/latest-php-version)
- [Ways to check which Laravel version is running](/check-laravel-version)

The safest target is a supported Laravel major on a supported PHP branch with compatible packages—not merely the newest number in one column.

If you are planning an upgrade, keep these pages nearby:

- [See what changed in Laravel 13](/laravel-13)
- [Review Laravel 12 before an intermediate upgrade](/laravel-12)
- [Plan a safer upgrade path off Laravel 11](/laravel-11-upgrade-guide)
- [Check which Laravel version your project actually uses](/check-laravel-version)
