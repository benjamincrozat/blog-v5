---
id: "01KKEW27M7JJ73EHZJ55ZV1MXT"
title: "Is PHP dead? Usage statistics and market share (2025)"
slug: "php-is-dead-2025"
author: "benjamincrozat"
description: "Is PHP dead? Let’s look at the December 2025 PHP usage statistics and see what the data really says about its future."
categories:
  - "php"
published_at: 2025-10-06T16:50:00+02:00
modified_at: 2026-03-12T17:49:51+01:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6X5NWXMGQTBXD9BHJ82QYGP.png"
sponsored_at: null
---
## Introduction

**Numbers for 2026 are here. Check out the newest edition here: [Is PHP dead? Usage statistics and market share (2026)](/php-is-dead-2026)**

Every year someone declares PHP dead. Yet the December 2025 snapshot still showed PHP running most of the web.

This refresh uses December 2025 W3Techs snapshots where available, plus the "same period 2025" rows that IT Jobs Watch still exposes in its current rolling reports.

So let’s look at the latest 2025 numbers and see whether the "PHP is dead" argument still holds up.

## PHP usage statistics in 2025

According to W3Techs, PHP still powered **72.9%** of websites with a known server-side language in the December 2025 snapshot.
Here’s the breakdown:

| Language     | Usage (Dec 2025) |
| ------------ | ---------------- |
| PHP          | 72.9%            |
| Ruby         | 6.4%             |
| Java         | 5.4%             |
| JavaScript   | 5.3%             |
| Scala        | 4.8%             |
| ASP.NET      | 4.7%             |
| Static files | 1.8%             |
| Python       | 1.2%             |
| ColdFusion   | 0.2%             |
| Perl         | 0.1%             |

Source: [W3Techs](https://w3techs.com/technologies/history_overview/programming_language)

So no, PHP isn’t dead. In fact, it is still the foundation for most of the web you use every day.

Before you keep reading, here’s **how W3Techs counts usage:**

- The percentages are among **sites where a server-side language is detectable**, so not all sites on the internet.
- A site can use **more than one** server-side language, and W3Techs updates these surveys **daily**.

Learn more about [the methodology of W3Techs](https://w3techs.com/technologies).

## Which PHP versions are most used

By December 2025, **PHP 8 had clearly overtaken PHP 7** on the public web.

| PHP Version | Usage (Dec 2025) |
| ----------- | ---------------- |
| 8           | 53.9%            |
| 7           | 36.6%            |
| 5           | 9.5%             |
| 4           | 0.1%             |

Source: [W3Techs](https://w3techs.com/technologies/history_details/pl-php)

PHP 8 was no longer "the future" by the end of 2025. It was the default.
That matters because WordPress recommends [PHP 8.3 or greater](https://wordpress.org/about/requirements/), even if its minimum version is still much older.

## The PHP job market in 2025

Job data is always messy, so I prefer a rolling benchmark over pretending there’s a clean global count. Here’s what the **same-period 2025** rows on IT Jobs Watch looked like:

| Role                  | Median salary | Rank |
| --------------------- | ------------- | ---- |
| PHP Developer         | £55,000       | 429  |
| Senior PHP Developer  | £60,000       | 677  |
| PHP Laravel Developer | £47,500       | 426  |

Sources: [PHP Developer](https://www.itjobswatch.co.uk/jobs/uk/php%20developer.do), [Senior PHP Developer](https://www.itjobswatch.co.uk/jobs/uk/senior%20php%20developer.do), [PHP Laravel Developer](https://www.itjobswatch.co.uk/jobs/uk/php%20laravel%20developer.do)

That is not a dead-language salary profile. If anything, it shows a mature market: broad demand, decent pay, and room for specialization.

## CMS market share: WordPress still rules

WordPress keeps PHP alive and well. It remains the most popular CMS on the planet.

| CMS         | Share of all sites | Share among known CMS |
| ----------- | ------------------ | --------------------- |
| WordPress   | 43.2%              | 60.4%                 |
| Shopify     | 4.9%               | 6.8%                  |
| Wix         | 4.1%               | 5.8%                  |
| Squarespace | 2.4%               | 3.4%                  |
| Joomla      | 1.4%               | 1.9%                  |
| Webflow     | 0.9%               | 1.2%                  |
| Drupal      | 0.8%               | 1.1%                  |

("No CMS" or custom builds account for 28.6% of all sites.)

Sources: [W3Techs usage history](https://w3techs.com/technologies/history_overview/content_management/all), [W3Techs market share history](https://w3techs.com/technologies/history_overview/content_management)

## WordPress versions and plugins

By December 2025, most WordPress sites had moved to version 6.

| Version | Usage (Dec 2025) |
| ------- | ---------------- |
| 6       | 90.7%            |
| 5       | 6.6%             |
| 4       | 2.5%             |
| 3       | 0.2%             |

And these were the most used plugins:

| Plugin      | Share |
| ----------- | ----- |
| Elementor   | 30.3% |
| WooCommerce | 20.4% |
| WPBakery    | 8.4%  |

Sources: [W3Techs WordPress version history](https://w3techs.com/technologies/history_details/cm-wordpress/ver), [W3Techs WordPress subtechnologies history](https://w3techs.com/technologies/history_details/cm-wordpress)

I’m not surprised to see Elementor and WooCommerce leading the pack. They are basically ecosystems of their own at this point.

## PHP frameworks in late 2025

The framework picture did not suddenly flip in late 2025 either.

Laravel was still the framework most newcomers talked about first, while **Symfony remained harder to measure with a single Packagist package** because its usage is spread across many components.
That’s why `laravel/framework` versus `symfony/symfony` is not a fair one-line comparison.

If you want a better read on Symfony’s footprint, use [Symfony’s aggregate download stats](https://symfony.com/stats/downloads) instead of just the monorepo package page.

## PHP releases in 2025

Here’s where 2025 ended:

* PHP 8.3 active support ended on **December 31, 2025**
* PHP 8.4 stays in active support until **December 31, 2026**
* PHP 8.5 shipped on **November 20, 2025**

If you’re still on PHP 7, it’s time to upgrade. Performance gains and type safety alone make it worth it.

Sources: [php.net](https://www.php.net/supported-versions.php), [PHP.Watch](https://php.watch/versions/8.5)

## So, is PHP dead?

Not even close.
PHP still powered almost three quarters of the web at the end of 2025, WordPress continued to dominate CMS usage, and the language itself kept moving forward.

It’s not flashy, it’s not trendy, and that’s exactly why it works.
The web runs on things that are predictable, maintainable, and well supported. PHP happens to be all three.

If anything, the December 2025 numbers prove that PHP remained one of the most entrenched technologies on the public web.

If you want the next data point in the same argument, these are the follow-up snapshots I would keep open:

- [Is PHP dead? Usage statistics and market share (2026)](/php-is-dead-2026)
- [Is PHP dead? Usage statistics and market share for 2023.](/php-is-dead-2023)
- [Is PHP dead? Usage and market share for September 2024](/php-is-dead-2024)
