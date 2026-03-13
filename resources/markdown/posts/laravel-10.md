---
id: "01KKEW27AZMFC1T80AF1TRRGXA"
title: "Laravel 10: features, support status, and upgrade advice"
slug: "laravel-10"
author: "benjamincrozat"
description: "Laravel 10 shipped on February 14, 2023. Here is what changed, when support ended, and whether it still makes sense as a late upgrade step."
categories:
  - "laravel"
published_at: 2022-09-14T22:00:00Z
modified_at: 2026-03-13T15:22:32Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/fBo7M3NZnT8zspS.png"
sponsored_at: null
---
## Introduction

Laravel 10 was released on February 14, 2023. If you are reading this on March 13, 2026, the most important thing to know is that Laravel 10 is no longer supported. Bug fixes ended on August 6, 2024, and security fixes ended on February 4, 2025, according to the official [release notes](https://laravel.com/docs/10.x/releases#support-policy).

That does not make this release irrelevant. A lot of teams still touch Laravel 10 while upgrading older applications, checking package compatibility, or trying to understand where newer conventions first appeared. This guide focuses on what Laravel 10 changed, what still matters during a late upgrade, and when you should skip straight to a newer version.

## Support status: should you still target Laravel 10?

Laravel 10 is not an LTS release. Laravel has not shipped an LTS version since Laravel 6, and the framework now follows shorter major-release support windows. Here is the timeline that matters today:

| Version | Release date | Bug fixes until | Security fixes until | Status on March 13, 2026 |
| ------- | ------------ | --------------- | -------------------- | ------------------------ |
| 10 | February 14, 2023 | August 6, 2024 | February 4, 2025 | End of life |
| 11 | March 12, 2024 | September 3, 2025 | March 12, 2026 | End of life |
| 12 | February 24, 2025 | August 13, 2026 | February 24, 2027 | Supported |

For most teams, that means:

- Do not start a new project on Laravel 10.
- Do not stop on Laravel 10 if you are planning a real long-term upgrade.
- Do use Laravel 10 as a stepping stone if an older application or a package constraint makes a version-by-version upgrade safer.

If you need the full history, my [Laravel versions guide](/laravel-versions) gives you the broader timeline.

## How to install Laravel 10 today

The old `laravel new hello-world` advice is no longer the right move if you specifically want Laravel 10. The Laravel installer creates the current major version, so you should pin 10.x explicitly with Composer:

```bash
composer create-project laravel/laravel:^10.0 my-app
```

I verified that command still creates a fresh Laravel 10 project today. The current 10.x skeleton I pulled in required PHP 8.1+, Composer 2.2+, PHPUnit 10, and `nunomaduro/collision` 7, which lines up with the official [upgrade guide](https://laravel.com/docs/10.x/upgrade#updating-dependencies).

One small detail that still catches people: a fresh Laravel 10 app does not include a `lang/` directory by default. If you publish language files or ship custom translations, the official [upgrade guide](https://laravel.com/docs/10.x/upgrade#the-language-directory) tells you to generate that directory yourself.

## What Laravel 10 changed that still matters

Laravel 10 was not a dramatic rewrite, but it introduced a few quality-of-life changes that still show up in modern codebases.

### Laravel Pennant made feature flags first-party

The [Laravel 10 release notes](https://laravel.com/docs/10.x/releases#laravel-pennant) introduced [Laravel Pennant](https://laravel.com/docs/10.x/pennant), a first-party package for feature flags. If you have ever needed to ship code behind a toggle, limit rollouts to a subset of users, or run a quick experiment, this was a meaningful addition.

```php
use Illuminate\Support\Lottery;
use Laravel\Pennant\Feature;

Feature::define('new-onboarding-flow', function () {
    return Lottery::odds(1, 10);
});
```

Pennant is one of those features that made Laravel feel more complete for product teams. You no longer had to reach for a third-party flag package for every small rollout.

### The Process facade made shell work much nicer

Laravel 10 also added the [Process facade](https://laravel.com/docs/10.x/processes), which wraps Symfony Process with a more Laravel-style API. If your app talks to CLIs, image tools, workers, or imports, this is one of the most practical additions from the release.

```php
use Illuminate\Support\Facades\Process;

$result = Process::run('ls -la');

return $result->output();
```

It also supports pools and concurrent execution, which made it easier to replace custom shell wrappers with something the framework already understands.

### Native types became the default in the application skeleton

Laravel 10 moved the official skeleton further toward native PHP typing. That sounds small, but it marked a real shift in the framework's defaults. New apps and generated code became clearer, static analysis got better, and the ecosystem kept moving away from docblock-heavy boilerplate.

You can see the same direction in the official Laravel packages and newer application stubs. If a codebase starts to feel "more modern Laravel" around this era, this change is part of the reason.

### Test tooling got sharper

Laravel 10 kept tightening the feedback loop around testing. The docs added [test profiling](https://laravel.com/docs/10.x/testing#profiling-tests), so `php artisan test --profile` shows your slowest tests first. That is a small feature, but it is exactly the kind of thing that helps on a real project with a growing suite.

The 10.x skeleton also standardized around newer PHPUnit and Collision versions, which matters when you are reviving an older app that skipped a few upgrades.

### Common schema changes stopped depending on Doctrine DBAL

Another understated improvement: Laravel 10 expanded native schema operations so many migration changes no longer need `doctrine/dbal`. That made upgrades and deploys a little less fragile, especially on projects that only pulled DBAL in for occasional column changes.

If your team still has `doctrine/dbal` in `composer.json` for old reasons, Laravel 10 is often where you can start questioning whether you still need it.

## What usually breaks during a late Laravel 10 upgrade

The official [Laravel 10 upgrade guide](https://laravel.com/docs/10.x/upgrade) calls the upgrade medium-impact, but a few items still deserve a manual check:

- PHP 8.1 is the minimum supported version, so any PHP 8.0 environment must move first.
- Composer 2.2 or newer is required.
- `monolog/monolog` moved to version 3, so custom logging integrations can need cleanup.
- `dispatchNow()` was removed. Replace it with `dispatchSync()`.
- Predis 1 is no longer supported, so old Redis setups need `predis/predis:^2.0` or PhpRedis.
- Custom validation rules now target `Illuminate\Contracts\Validation\ValidationRule`, which affects projects with older rule classes.
- If your application customizes translation files, publish the `lang/` directory before assuming it exists.

If you are actively upgrading a Laravel 9 app, my [Laravel 10 upgrade guide](/laravel-10-upgrade-guide) walks through the step-by-step process in more detail.

## Should you stop at Laravel 10?

Usually, no.

If your application is already on Laravel 10, the right move in 2026 is to plan the next jump because security support ended on February 4, 2025. If you are still on Laravel 9 or older, Laravel 10 can be a temporary bridge, but Laravel 12 is the version you should be aiming to run in production now.

That is the main difference between how this article read in 2023 and how it should read today: Laravel 10 is now more useful as an upgrade checkpoint than as a destination.

The smartest next reads from here depend on whether you are moving one step or finishing the whole climb:

- [Follow the practical path from Laravel 9 to 10](/laravel-10-upgrade-guide)
- [See which Laravel 11 changes matter after 10](/laravel-11)
- [Check the Laravel 11 upgrade traps before you move again](/laravel-11-upgrade-guide)
- [See why Laravel 12 is the version to target now](/laravel-12)
- [Double-check the whole release timeline before you plan upgrades](/laravel-versions)
