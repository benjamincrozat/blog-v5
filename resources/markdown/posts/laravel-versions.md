---
id: "01KKEW27EBJJREQW7SC8BS8P5Y"
title: "Laravel versions: latest release and support status"
slug: "laravel-versions"
author: "benjamincrozat"
description: "See the latest Laravel version, which majors are still supported, and how long bug-fix and security support lasts."
categories:
  - "laravel"
published_at: 2023-09-20T00:00:00+02:00
modified_at: 2026-03-19T22:39:10Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/4IJU4CXwjE9Me2J.jpg"
sponsored_at: null
---
## Introduction

**Laravel 13 is the latest Laravel major release as of March 19, 2026.**

If you only need the fast answer:

- **Laravel 13** is the current major release.
- **Laravel 12** is still fully supported.
- **Laravel 11** reached the end of security support on **March 12, 2026**.
- There is **no current LTS release**.

This page used to be a freshness pass for Laravel 12, but the official framework release changed quickly: `v13.0.0` was published on **March 17, 2026**, and `v13.1.1` followed on **March 18, 2026**.

## The latest Laravel version

According to Laravel's official release notes and framework releases:

- **Laravel 13** is the latest documented major release.
- **Laravel 13.0.0** was published on **March 17, 2026**.
- The latest framework release visible on GitHub as of **March 19, 2026** is **Laravel 13.1.1**, published on **March 18, 2026**.

If you are choosing a version for a new app today, **Laravel 13** is the default target.

## Supported Laravel versions right now

Here is the current support picture from Laravel's official support-policy table, interpreted on **March 19, 2026**:

| Version | PHP | Release | Bug fixes until | Security fixes until | Status on March 19, 2026 |
| --- | --- | --- | --- | --- | --- |
| Laravel 11 | 8.2 - 8.4 | March 12, 2024 | September 3, 2025 | March 12, 2026 | End of support |
| Laravel 12 | 8.2 - 8.5 | February 24, 2025 | August 13, 2026 | February 24, 2027 | Supported |
| Laravel 13 | 8.3 - 8.5 | Q1 2026 in the docs table | Q3 2027 | Q1 2028 | Current major release |

One subtle detail: the docs support table still labels Laravel 13's release date broadly as **Q1 2026**, even though the framework release itself was published on **March 17, 2026**.

## What this means in practice

### If you are on Laravel 13

You are on the latest major and the right default track for new work.

### If you are on Laravel 12

You are still in a healthy place. There is no emergency, but you should treat Laravel 13 as your next routine upgrade target.

### If you are on Laravel 11

You are now past the last day of official security support. That deadline was **March 12, 2026**.

If that is your current branch, I would stop thinking in terms of "later this year" and start planning the upgrade now.

## Is there a current Laravel LTS release?

No. Laravel no longer ships special LTS majors.

The last LTS release was **Laravel 6**, released on **September 3, 2019**.

The modern policy is simpler:

- bug fixes for **18 months**
- security fixes for **2 years**
- major releases roughly once per year

That is why keeping up with Laravel now is mostly a maintenance habit, not a once-every-few-years migration event.

## Which PHP version does each current Laravel major need?

This is the short version from the release notes:

- **Laravel 11**: PHP **8.2 - 8.4**
- **Laravel 12**: PHP **8.2 - 8.5**
- **Laravel 13**: PHP **8.3 - 8.5**

So if you are preparing for Laravel 13, make sure your environment is already on **PHP 8.3 or newer**.

If you need to confirm the runtime first, check out [how to check your PHP version](/check-php-version).

## How to check which Laravel version your app is running

If you are not sure which version is actually installed, the quickest next step is here:

[Ways to check which Laravel version you are running](/check-laravel-version)

That is the practical companion to this page. This article tells you what is current; that one tells you what your project is actually using.

## Should you upgrade now?

My short rule of thumb:

- Upgrade to **Laravel 13** for new applications.
- Keep **Laravel 12** on your near-term upgrade roadmap, not your panic list.
- Move off **Laravel 11** as soon as reasonably possible because security support ended on **March 12, 2026**.

## Conclusion

Laravel 13 is the latest Laravel release on **March 19, 2026**, Laravel 12 is still comfortably supported, and Laravel 11 is now out of support. There is no active LTS branch to wait for, so the safest long-term habit is to keep upgrades routine.

If you are planning the next move after checking the version map, these are the follow-up reads I would keep open:

- [Double-check which Laravel version is actually running](/check-laravel-version)
- [See what changed in Laravel 13](/laravel-13)
- [Review Laravel 12 before an intermediate upgrade](/laravel-12)
- [Plan a safer upgrade path off Laravel 11](/laravel-11-upgrade-guide)
