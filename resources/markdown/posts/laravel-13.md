---
id: "01KKEW27BBGFKC1KETMVEMKHM5"
title: "An early look at Laravel 13's features and changes"
slug: "laravel-13"
author: "benjamincrozat"
description: "Laravel 13 lands in Q1 2026. I cover PHP 8.3+ requirements, support timelines, recent merged changes, and how to try 13.x today."
categories:
  - "laravel"
published_at: 2025-07-06T21:26:00+02:00
modified_at: 2025-09-27T01:08:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "01JZZD9NKMTM9H87RFVTHT0GWX.png"
sponsored_at: null
---
## Introduction

In this post, I summarize what’s known about Laravel 13 as of today—its release date, PHP 8.3 requirement, support timelines, how to try it early, and notable changes already merged. I’ll keep this page updated as new pull requests land.

## Release date and support timeline

According to the official [Support Policy](https://laravel.com/docs/12.x/releases#support-policy), Laravel 13 is scheduled to be released during Q1 of 2026. The release of Laravel 13 doesn’t mean you must update all your projects immediately, though.

The framework last had LTS (Long-Term Support) in version 6, and the current policy is: bug fixes for 18 months; security fixes for 2 years. Laravel 12 will receive bug fixes until August 13, 2026 and security fixes until February 24, 2027. So, take your time.

| Version | PHP | Release | Bug fixes until | Security fixes until |
| ------- | --- | ------- | --------------- | -------------------- |
| 12 | 8.2–8.4 | February 24, 2025 | August 13, 2026 | February 24, 2027 |
| 13 | 8.3–8.4 | Q1 2026 | Q3 2027 | Q1 2028 |

## Requirements

Laravel 13 requires PHP 8.3 as the minimum version, as confirmed by the merged pull request [Requires PHP 8.3 as minimum version](https://github.com/laravel/framework/pull/54763) and reflected in the Support Policy ranges above. The framework is also being kept compatible with newer Symfony versions, including [Symfony 7.4 and 8.0 support](https://github.com/laravel/framework/pull/56029).

If you are on PHP 8.2, stay on Laravel 12 until you can upgrade your runtime. As 13.x stabilizes, subpackages and Composer metadata are synchronized to `^13.0` to avoid mismatched dependencies; see the [prepare branch alias for Laravel 13](https://github.com/laravel/framework/pull/54701) work.

## How to try Laravel 13 today (experimental)

Using the Laravel installer:

```bash
laravel new hello-world --dev
```

This installs the latest development application skeleton.

Using Composer:

1. Ensure your environment runs PHP 8.3+.

2. Create a new app using the stable skeleton:

    ```bash
    composer create-project laravel/laravel hello-world
    cd hello-world
    ```

3. Allow development packages while preferring stable:

    ```bash
    composer config minimum-stability dev
    composer config prefer-stable true
    ```

4. Require the 13.x development branch of the framework and update dependencies:

    ```bash
    composer require laravel/framework:13.x-dev --update-with-all-dependencies
    ```

## What’s new in 13.x so far

Laravel 13 development has focused primarily on foundation work, plus a handful of developer-facing improvements that you can try when testing 13.x-dev.

Foundation work examples:

- Versioning and release prep: [Prepare branch alias for Laravel 13](https://github.com/laravel/framework/pull/54701).
- CI environment reliability: [Fix Tests/CI environments](https://github.com/laravel/framework/pull/54760).
- Minimum runtime: [Requires PHP 8.3 as minimum version](https://github.com/laravel/framework/pull/54763).
- Cleanup and modernization: for example, [cleanup PR #54876](https://github.com/laravel/framework/pull/54876) and [cleanup PR #54900](https://github.com/laravel/framework/pull/54900).
- Hardening edge cases: [remove nested scope pitfalls](https://github.com/laravel/framework/pull/54816) and [clarify Response::throw parameters](https://github.com/laravel/framework/pull/54798).

Developer-facing improvements:

- [Cache::touch() and Store::touch() for TTL extension](https://github.com/laravel/framework/pull/55954) so you can extend cache lifetimes without re-get/put.
- [Use clearer pagination view names](https://github.com/laravel/framework/pull/56307) for better consistency.
- [Supports Symfony 7.4 and 8.0](https://github.com/laravel/framework/pull/56029) to keep dependencies modern.

## Recently merged in 13.x

Check some recent pull requests:

- [[13.x] Add command method to contract (#56978)](https://github.com/laravel/framework/pull/56978) – contract surface tweak. Merged Sep 10, 2025.
- [[13.x] Generate plural morph pivot table name (#56832)](https://github.com/laravel/framework/pull/56832) – naming improvement for morph pivot tables. Merged Sep 4, 2025.
- [[13.x] Resolve Symfony Console add() method deprecation (#56488)](https://github.com/laravel/framework/pull/56488) – future-proofing against Symfony changes. Merged Sep 7, 2025.
- [[13.x] Use clearer pagination view names (#56307)](https://github.com/laravel/framework/pull/56307) – naming consistency and clarity. Merged Aug 27, 2025.
- [[13.x] Cache::touch() & Store::touch() for TTL Extension (#55954)](https://github.com/laravel/framework/pull/55954) – extend TTL without re-get/put. Merged Aug 12, 2025.
- [[13.x] Supports Symfony 7.4 & 8.0 (#56029)](https://github.com/laravel/framework/pull/56029) – broader Symfony compatibility. Merged Jun 16, 2025.
- [[13.x] Use exception object in JobAttempted event (#56148)](https://github.com/laravel/framework/pull/56148) – event payload consistency. Merged Jul 2, 2025.
- [[13.x] Register subdomain routes before routes not linked to a domain (#55921)](https://github.com/laravel/framework/pull/55921) – routing order refinement. Merged Jun 5, 2025.

Browse more merged items in the GitHub query for [merged 13.x pull requests](https://github.com/laravel/framework/pulls?q=is%3Apr+is%3Amerged+%5B13.x%5D+in%3Atitle).

## FAQ

### When is Laravel 13 coming out?

Q1 2026, per the official [Support Policy](https://laravel.com/docs/12.x/releases#support-policy).

### What PHP version does Laravel 13 require?

PHP 8.3+, as set in the merged PR [Requires PHP 8.3 as minimum version](https://github.com/laravel/framework/pull/54763) and reflected in the [Support Policy](https://laravel.com/docs/12.x/releases#support-policy).

### How long is Laravel 12 supported?

Bug fixes until August 13, 2026 and security fixes until February 24, 2027, per the [Support Policy](https://laravel.com/docs/12.x/releases#support-policy).

## Conclusion

Laravel 13 is slated for Q1 2026 with a PHP 8.3+ baseline, an 18-month bug-fix window, and two years of security updates. If you want to try it today, I recommend doing so on a non-production branch using the experimental steps above. I’ll refresh the merged changes list monthly and keep the “last updated” date current. As we get closer to launch, I plan to expand this into a concise Laravel 13 upgrade guide.