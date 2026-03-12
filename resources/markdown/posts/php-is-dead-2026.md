---
id: "01KKHF9QW3ZQH90K081XEW4RT2"
title: "Is PHP dead? Usage statistics and market share (2026)"
slug: "php-is-dead-2026"
author: "benjamincrozat"
description: "Is PHP dead? Let’s look at the March 2026 PHP usage statistics and see what the data really says about its future."
categories:
  - "php"
published_at: 2026-03-12T15:49:51+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KKHHHSYWCAB9NZB42H29GGQC.png"
sponsored_at: null
---
## Introduction

Every year someone says PHP is finished. Yet here we are in March 2026, and it is still running most of the web.

The share has slipped a little again, but the overall picture has not changed: PHP remains the default server-side language for a huge number of real websites, products, and publishing platforms.

So let’s look at the current numbers and see whether the "PHP is dead" argument makes any sense in 2026.

## PHP usage statistics in 2026

According to W3Techs, PHP currently powers **71.8%** of websites with a known server-side language.
Here’s the breakdown:

| Language     | Usage (12 Mar 2026) |
| ------------ | ------------------- |
| PHP          | 71.8%               |
| Ruby         | 6.7%                |
| JavaScript   | 5.9%                |
| Java         | 5.4%                |
| Scala        | 5.0%                |
| ASP.NET      | 4.4%                |
| Static files | 2.1%                |
| Python       | 1.2%                |
| ColdFusion   | 0.2%                |
| Perl         | 0.1%                |

Source: [W3Techs](https://w3techs.com/technologies/overview/programming_language)

So no, PHP is not dead. It still sits far ahead of every other server-side language in W3Techs’ public-web tracking.

Before you keep reading, here’s **how W3Techs counts usage:**

- The percentages are among **sites where a server-side language is detectable**, so not all sites on the internet.
- A site can use **more than one** server-side language, and W3Techs updates these surveys **daily**.

Learn more about [the methodology of W3Techs](https://w3techs.com/technologies).

## Which PHP versions are most used

PHP 8 is now clearly the default on the public web.

| PHP Version | Usage (12 Mar 2026) |
| ----------- | ------------------- |
| 8           | 57.4%               |
| 7           | 33.7%               |
| 5           | 8.8%                |
| 4           | 0.1%                |

Source: [W3Techs](https://w3techs.com/technologies/details/pl-php)

That is a meaningful shift. A year earlier, PHP 7 still had a much stronger grip. In 2026, PHP 8 is comfortably ahead.

WordPress now recommends [PHP 8.3 or greater](https://wordpress.org/about/requirements/), even though the minimum version is still 7.2.24. The recommendation tells you where the ecosystem wants people to be.

## The PHP job market in 2026

Job data is always messy, but the UK still gives a useful snapshot. Here’s what IT Jobs Watch shows in its rolling six-month view ending on March 12, 2026:

| Role                  | Median salary | Rank |
| --------------------- | ------------- | ---- |
| PHP Developer         | £47,500       | 518  |
| Senior PHP Developer  | £60,000       | 666  |
| PHP Laravel Developer | £50,000       | 502  |

Sources: [PHP Developer](https://www.itjobswatch.co.uk/jobs/uk/php%20developer.do), [Senior PHP Developer](https://www.itjobswatch.co.uk/jobs/uk/senior%20php%20developer.do), [PHP Laravel Developer](https://www.itjobswatch.co.uk/jobs/uk/php%20laravel%20developer.do)

Generic PHP roles softened a bit, but senior and Laravel-focused roles are still paying real money. That is not what a dying ecosystem looks like.

## CMS market share: WordPress still rules

WordPress continues to be one of PHP’s biggest advantages.

| CMS         | Share of all sites | Share among known CMS |
| ----------- | ------------------ | --------------------- |
| WordPress   | 42.4%              | 59.7%                 |
| Shopify     | 5.1%               | 7.2%                  |
| Wix         | 4.3%               | 6.0%                  |
| Squarespace | 2.5%               | 3.5%                  |
| Joomla      | 1.3%               | 1.8%                  |
| Webflow     | 0.9%               | 1.2%                  |
| Drupal      | 0.7%               | 1.0%                  |

("No CMS" or custom builds account for 29.0% of all sites.)

Source: [W3Techs](https://w3techs.com/technologies/overview/content_management/)

Even after years of people predicting a collapse, WordPress is still the biggest CMS on the web by a wide margin.

## WordPress versions and plugins

Most WordPress sites now sit on version 6.

| Version | Usage (12 Mar 2026) |
| ------- | ------------------- |
| 6       | 91.6%               |
| 5       | 5.9%                |
| 4       | 2.3%                |
| 3       | 0.2%                |

And these are still the most used plugins:

| Plugin      | Share |
| ----------- | ----- |
| Elementor   | 31.0% |
| WooCommerce | 20.1% |
| WPBakery    | 8.0%  |

Source: [W3Techs](https://w3techs.com/technologies/details/cm-wordpress)

Elementor and WooCommerce are still massive ecosystems inside the larger WordPress ecosystem. That alone keeps a lot of PHP work alive.

## PHP frameworks in 2026

Download counts on Packagist are tricky. Laravel ships as a single framework package, while **Symfony usage is spread across many components**. Comparing `laravel/framework` to `symfony/symfony` undercounts Symfony by design.

If you want a single high-level number, Symfony’s own stats page reports **35,740,476,420 total downloads** across its components.

On GitHub, the rough popularity snapshot still looks like this:

| Framework        | Stars |
| ---------------- | ----- |
| Laravel          | 84.0k |
| Symfony          | 31.0k |
| CodeIgniter (v3) | 18.2k |
| CakePHP          | 8.8k  |

Sources: [Symfony downloads](https://symfony.com/stats/downloads), [Laravel](https://github.com/laravel/laravel), [Symfony](https://github.com/symfony/symfony), [CodeIgniter](https://github.com/bcit-ci/CodeIgniter), [CakePHP](https://github.com/cakephp/cakephp)

Laravel is still the framework most people reach for first, but Symfony remains enormous in real-world usage once you count the component ecosystem correctly.

## PHP releases in 2026

Here’s where we stand right now:

* PHP 8.3 stays in security support until **December 31, 2027**
* PHP 8.4 stays in active support until **December 31, 2026**
* PHP 8.5 is the newest stable branch
* PHP 8.6 is expected on **November 19, 2026**

Sources: [php.net](https://www.php.net/supported-versions.php), [PHP.Watch](https://php.watch/versions/8.6)

So if you are still on PHP 7, you are not living on the edge. You are living in the past.

## So, is PHP dead?

Not even close.
PHP still powers most of the web, WordPress still dominates CMS usage, Laravel remains huge, Symfony remains huge, and the language still ships a new major version every year.

It may not be the noisiest ecosystem on the internet, but that is part of the appeal.
The web runs on boring, reliable technology more often than people like to admit, and PHP is still one of the clearest examples of that.
