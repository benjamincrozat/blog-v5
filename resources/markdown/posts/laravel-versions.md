---
id: "01KKEW27EBJJREQW7SC8BS8P5Y"
title: "Laravel versions, support policy, and LTS status"
slug: "laravel-versions"
author: "benjamincrozat"
description: "See the current Laravel version, which releases are still supported, and how Laravel's support policy works."
categories:
  - "laravel"
published_at: 2023-09-20T00:00:00+02:00
modified_at: 2026-03-13T15:40:00Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/4IJU4CXwjE9Me2J.jpg"
sponsored_at: null
---
## Introduction

**If you want the short answer first: Laravel 12 is the latest fully documented major release on Laravel's official [release-notes page](https://laravel.com/docs/releases) as of March 13, 2026.**

That same support-policy table already lists Laravel 13 for `Q1 2026`, so this is one of those moments where the support matrix is slightly ahead of the narrative release notes.

## The latest stable version of Laravel

According to Laravel's official [release notes](https://laravel.com/docs/releases) and [GitHub releases](https://github.com/laravel/framework/releases):

- **Laravel 12** is the latest documented major release.
- **Laravel 12** was released on **February 24, 2025**.
- **Laravel 12** receives bug fixes until **August 13, 2026** and security fixes until **February 24, 2027**.

If you just need to know what to target in production today, Laravel 12 is the safe default answer.

## The latest LTS (Long Term Support) release of Laravel

There is **no current LTS release** of Laravel.

The last LTS release was **Laravel 6**, released on **September 3, 2019**.

Earlier LTS releases were:

- Laravel 5.1
- Laravel 5.5
- Laravel 6

Modern Laravel does not use special LTS majors anymore. The framework now follows a support policy with **18 months of bug fixes** and **2 years of security fixes** for each major release.

## The currently supported Laravel versions

Here is the status from Laravel's official support table as of **March 13, 2026**:

| Version | Release | Bug fixes until | Security fixes until | Status on March 13, 2026 |
| --- | --- | --- | --- | --- |
| Laravel 11 | March 12, 2024 | September 3, 2025 | March 12, 2026 | Security support just ended |
| Laravel 12 | February 24, 2025 | August 13, 2026 | February 24, 2027 | Supported |
| Laravel 13 | Q1 2026 | Q3 2027 | Q1 2028 | Listed in the support policy table |

So, if you are still on Laravel 11, **March 12, 2026** was your last day of official security support.

## See which version of Laravel you are running

Knowing which version of Laravel you are running matters when you are planning an upgrade, checking package compatibility, or reading framework docs.

Check out: [Ways to check which Laravel version you are running](https://benjamincrozat.com/check-laravel-version)

## How Laravel's support policy works

Laravel's current policy is simple:

- bug fixes for **18 months**
- security fixes for **2 years**
- weekly minor and patch releases
- major releases around **Q1**

That means Laravel upgrades are meant to be routine maintenance, not something you postpone for years.

## Quick Laravel version timeline

If you only want the broad picture, this is the useful timeline:

- **Laravel 5.1** introduced the first LTS release.
- **Laravel 5.5** was another LTS release.
- **Laravel 6** was the last LTS release.
- **Laravel 8 and later** shifted into the modern annual major-release cadence and current support policy.
- **Laravel 12** is the latest fully documented release on the official release-notes page.

If you need the deeper archive for older majors, Laravel keeps release notes for old versions in its documentation archive.

## Conclusion

Laravel 12 is the current documented answer, Laravel 11 fell out of security support on **March 12, 2026**, and there is no active LTS branch to hide on. If you are planning upgrades, target Laravel 12 now and keep an eye on Laravel 13's official release notes once that page lands.

If you are using the version map to plan what comes next, these are the follow-up reads I would keep open:

- [Double-check which Laravel version is actually running](/check-laravel-version)
- [See what Laravel 13 is shaping up to change](/laravel-13)
- [See what Laravel 12 changed before you adopt it](/laravel-12)
- [Plan a safer upgrade from Laravel 11](/laravel-11-upgrade-guide)
