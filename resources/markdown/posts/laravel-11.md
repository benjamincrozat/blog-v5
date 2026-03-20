---
id: "01KKEW27B5Z43M95WBDPTWFANX"
title: "Laravel 11: key changes, support status, and upgrade advice"
slug: "laravel-11"
author: "benjamincrozat"
description: "Laravel 11 shipped on March 12, 2024. Here is what changed, why it reached end of life, and whether it still makes sense as a late upgrade step."
categories:
  - "laravel"
published_at: 2023-01-04T23:00:00Z
modified_at: 2026-03-20T12:41:49Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/iMgO07qBJBb4MJr.png"
sponsored_at: null
---
## Introduction

Laravel 11 was released on March 12, 2024. As of March 20, 2026, it is end of life: bug fixes ended on September 3, 2025, and security fixes ended on March 12, 2026, according to the official [release notes](https://laravel.com/docs/11.x/releases#support-policy).

That makes Laravel 11 a strange but still useful release to study. It introduced a much slimmer application skeleton and several framework improvements that still shape modern Laravel apps, but it is no longer the version you should aim to stay on in production.

## Support status: should you still target Laravel 11?

Laravel 11 is not an LTS release. In practical terms, the current support picture looks like this:

| Version | Release date | Bug fixes until | Security fixes until | Status on March 20, 2026 |
| ------- | ------------ | --------------- | -------------------- | ------------------------ |
| 10 | February 14, 2023 | August 6, 2024 | February 4, 2025 | End of life |
| 11 | March 12, 2024 | September 3, 2025 | March 12, 2026 | End of life |
| 12 | February 24, 2025 | August 13, 2026 | February 24, 2027 | Supported |

So the short answer is:

- Do not launch a new production app on Laravel 11 now.
- Do not stop on Laravel 11 if you are planning a larger modernization effort.
- Do use Laravel 11 as a stepping stone if you need a smaller, version-by-version path from older code to Laravel 12.

If you want the longer release history, my [Laravel versions guide](/laravel-versions) gives you the full timeline.

## How to install Laravel 11 today

If you explicitly need Laravel 11 for an existing project or compatibility test, pin the version with Composer instead of relying on the installer to pick the right major:

```bash
composer create-project laravel/laravel:^11.0 my-app
```

The current 11.x skeleton still uses the slim `bootstrap/app.php` setup, requires PHP 8.2+, includes only `routes/web.php` and `routes/console.php` by default, and leaves `app/Http` with just controllers.

That is important because the official [release notes](https://laravel.com/docs/11.x/releases#streamlined-application-structure) introduced the slim structure for new applications only. Existing Laravel 10 apps can still upgrade to 11 without rewriting themselves into the new layout.

## What Laravel 11 changed that still matters

The release notes describe Laravel 11 as a continuation of the Laravel 10 improvements, with a [streamlined application structure, per-second rate limiting, health routing, graceful encryption key rotation, queue testing improvements, and more](https://laravel.com/docs/11.x/releases). For most teams, these are the durable changes worth remembering.

### The slim application structure changed new projects a lot

Laravel 11's biggest visible change was the default skeleton. New projects moved more setup into `bootstrap/app.php`, trimmed the number of framework files in the app itself, and made optional pieces truly optional.

Here is the default shape of a fresh Laravel 11 `bootstrap/app.php`:

```php
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
```

That one file now handles routing, middleware, exceptions, and the health endpoint. It is the main reason a fresh Laravel 11 app feels lighter than a Laravel 10 app.

### API and broadcasting files became opt-in

New Laravel 11 apps do not include `routes/api.php` or `routes/channels.php` by default. If you need them, install them when you actually need them:

```bash
php artisan install:api
php artisan install:broadcasting
```

That sounds minor, but it is part of the same philosophy: do not ship boilerplate you may never use. If you need a walkthrough, I have a guide on [restoring route files in Laravel 11](/install-route-files-laravel).

### Health routing became a first-class feature

Laravel 11 added built-in [health routing](https://laravel.com/docs/11.x/deployment#the-health-route) through the `health: '/up'` configuration in `bootstrap/app.php`. That makes it easier to wire Laravel apps into load balancers, container platforms, and uptime checks without rolling your own probe endpoint every time.

It is a small feature, but it is one of the most operationally useful additions in the release.

### Per-second rate limiting fixed a real blind spot

Before Laravel 11, per-minute limits were easy, but they still allowed traffic spikes inside that minute. Laravel 11 added [per-second rate limiting](https://laravel.com/docs/11.x/routing#rate-limiting), which is much better when you want to smooth bursts instead of just counting total volume.

```php
RateLimiter::for('invoices', function (Request $request) {
    return Limit::perSecond(2);
});
```

If you protect login forms, webhooks, or expensive endpoints, this is one of the more practical framework-level changes from the release.

### Graceful encryption key rotation lowered the risk of rotating secrets

Laravel 11 also introduced [graceful encryption key rotation](https://laravel.com/docs/11.x/releases#graceful-encryption-key-rotation). If you have ever hesitated to rotate `APP_KEY` because it could break old encrypted payloads, this change matters. It gives teams a cleaner path for operational hygiene without forcing an all-at-once cutover.

### The upgrade stayed moderate, even with the new defaults

The official [upgrade guide](https://laravel.com/docs/11.x/upgrade#estimated-upgrade-time-15-minutes) estimates about 15 minutes for the upgrade itself. That estimate is optimistic for a messy app, but the underlying point is fair: Laravel 11 changed the starting skeleton more than it changed existing applications.

That is why many upgrades from 10 to 11 feel smaller than the launch article made them sound in 2024.

## What usually matters during a late Laravel 11 upgrade

If you are upgrading now, these are the checks I would make first:

- PHP 8.2 is required, so older runtimes must move first.
- The new application structure is optional for existing apps. Do not rewrite files just to match the new skeleton.
- Review [password rehashing](https://laravel.com/docs/11.x/upgrade#password-rehashing) if your authentication flow or user provider is customized.
- Review [Carbon 3](https://laravel.com/docs/11.x/upgrade#carbon-3) if your app or packages rely heavily on `diffIn*` methods, because return values and signs changed.
- Check package compatibility with `composer why-not laravel/framework 11.0` before touching your constraints.

If you want the version-by-version mechanics, my [Laravel 11 upgrade guide](/laravel-11-upgrade-guide) goes deeper on the actual upgrade process.

## Should you stop at Laravel 11?

Usually, no.

Laravel 11 was a meaningful release, and its slimmer defaults absolutely influenced the versions that came after it. But as of March 20, 2026, it is already past its security support window. If you are planning work today, Laravel 12 is the version to target for production.

That means Laravel 11 is now best understood as a transitional release: very important historically, still useful as an upgrade step, but not a destination.

If you are figuring out the next move after reading this, these are the pages I would keep open:

- [Follow the practical upgrade path from Laravel 10 to 11](/laravel-11-upgrade-guide)
- [Adjust middleware in the new Laravel 11 structure](/customize-middleware-laravel-11)
- [Bring back the missing route files only when you need them](/install-route-files-laravel)
- [See why Laravel 12 is the version to target now](/laravel-12)
- [Double-check the whole release timeline before you plan upgrades](/laravel-versions)
