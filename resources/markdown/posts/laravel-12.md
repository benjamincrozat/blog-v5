---
id: "01KKEW27B9KMJCRAM3TKA1J9DN"
title: "Laravel 12: starter kits, support status, and upgrade advice"
slug: "laravel-12"
author: "benjamincrozat"
description: "Laravel 12 shipped on February 24, 2025. Here is what changed, what late upgraders should check, and why it is the Laravel version to target now."
categories:
  - "laravel"
published_at: 2024-03-10T23:00:00Z
modified_at: 2026-03-13T15:29:49Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/NR16LmxmtqoscGx.png"
sponsored_at: null
---
## Introduction

Laravel 12 was released on February 24, 2025. Unlike Laravel 10 and Laravel 11, it is still inside its support window on March 13, 2026, which is why this is the version most teams should be targeting now for production upgrades and new work.

The official [release notes](https://laravel.com/docs/12.x/releases) describe Laravel 12 as another low-drama major release, and that is mostly true. The two headline changes are the new starter-kit lineup and a few upgrade details that matter a lot if your app touches UUIDs, Carbon, or SVG uploads.

## Support status: why Laravel 12 is the version to target now

Laravel's current support windows make the upgrade decision much clearer than they did when 12 first shipped:

| Version | Release date | Bug fixes until | Security fixes until | Status on March 13, 2026 |
| ------- | ------------ | --------------- | -------------------- | ------------------------ |
| 11 | March 12, 2024 | September 3, 2025 | March 12, 2026 | End of life |
| 12 | February 24, 2025 | August 13, 2026 | February 24, 2027 | Supported |

That means:

- New projects should start on Laravel 12.
- Laravel 11 projects should move to 12 as soon as they can do it safely.
- Laravel 10 projects should treat 11 and 12 as the real finish line, not stop early.

If you want the full history, my [Laravel versions guide](/laravel-versions) puts the whole release ladder in one place.

## How to install Laravel 12 today

If you want the nicest setup flow, use the Laravel installer:

```bash
laravel new my-app
```

That is where you pick your starter kit, authentication flavor, database, and testing stack. The current [starter-kit documentation](https://laravel.com/docs/12.x/starter-kits) also makes it clear that `laravel new` is where the WorkOS AuthKit variants are offered.

If you want to pin Laravel 12 explicitly, Composer is still the safest evergreen command:

```bash
composer create-project laravel/laravel:^12.0 my-app
```

I verified that command still creates a fresh Laravel 12 app today. The current 12.x skeleton requires PHP 8.2+ and keeps the lean Laravel 11 structure, so you get the modern `bootstrap/app.php` flow rather than the older multi-file skeleton.

## What Laravel 12 changed that still matters

### The starter kits are the real headline

Laravel 12 introduced new official starter kits for [React, Svelte, Vue, and Livewire](https://laravel.com/docs/12.x/starter-kits). The React, Svelte, and Vue kits use Inertia 2, TypeScript, and shadcn-style component systems, while the Livewire kit uses Flux UI and Laravel Volt.

That matters because Laravel 12 is the point where the framework's recommended starting experience became much clearer:

- Pick React, Svelte, or Vue if you want a richer front end with Inertia.
- Pick Livewire if you want to stay closer to Blade and server-driven UI.
- Pick a WorkOS AuthKit variant if social login, passkeys, or SSO matter from day one.

The release notes also say that Breeze and Jetstream will not receive further updates, so Laravel 12 effectively resets the "official default starter" conversation around this newer lineup.

### UUIDv7 became the default for `HasUuids`

The official [upgrade guide](https://laravel.com/docs/12.x/upgrade#models-and-uuidv7) notes that models using `HasUuids` now generate ordered UUIDv7 values by default.

That is good for index locality and sorting, but it can matter if your app or database assumptions are tied to UUIDv4 semantics. If you need to keep the old behavior, alias the alternative trait:

```php
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;
```

For many apps this is a non-event. For apps with ID parsing, ordering assumptions, or external integrations, it deserves a deliberate check.

### Carbon 3 is now mandatory

Laravel 11 could support Carbon 2 or Carbon 3. Laravel 12 does not. The official [upgrade guide](https://laravel.com/docs/12.x/upgrade#carbon-3) says Carbon 2 support is removed, so all Laravel 12 apps now require Carbon 3.

This is not usually a huge rewrite, but it is one of the highest-signal checks in the upgrade because date logic has a habit of hiding in old helpers, package code, and tests.

### SVG uploads got stricter by default

Laravel 12 changed the `image` validation rule so it [no longer accepts SVGs by default](https://laravel.com/docs/12.x/upgrade#image-validation). If your admin UI, CMS, or user uploads rely on SVGs, you now need to opt in explicitly with `image:allow_svg` or `File::image(allowSvg: true)`.

This is the kind of subtle change that can easily look like a random regression if you do not know it is coming.

### The upgrade kept its "small major release" feel

The official guide estimates about five minutes for the upgrade itself, which is aggressive, but the spirit is right: Laravel 12 is much closer to a careful dependency and behavior audit than to a framework rewrite.

That is why I would describe Laravel 12 as a practical major release, not a flashy one.

## What I would check first before upgrading to Laravel 12

If you are moving to Laravel 12 now, this is the checklist I would start with:

1. Upgrade one major at a time. If you are on Laravel 10, go through 11 before 12.
2. Make sure the runtime is on PHP 8.2 or newer.
3. Update framework and test dependencies as shown in the official [upgrade guide](https://laravel.com/docs/12.x/upgrade#updating-dependencies).
4. Search for models using `HasUuids` and decide whether UUIDv7 is acceptable.
5. Audit any direct Carbon usage, especially date math and older tests.
6. Search for SVG upload flows and make the validation rule explicit where needed.
7. If you implement `Illuminate\Contracts\Routing\ResponseFactory`, add the `streamJson` method mentioned in the upgrade guide.
8. If you use MariaDB schema dumps, make sure `mariadb` and `mariadb-dump` are available on the machine that runs `schema:dump`.

If you are currently on Laravel 11, this is one of the easier upgrades in recent Laravel history. If you are still on Laravel 10 or older, the work is less about Laravel 12 itself and more about the versions you have skipped on the way here.

## Should you move to Laravel 12 now?

Usually, yes.

Laravel 12 is the current supported destination in this generation of the framework. It gives you the newer starter-kit ecosystem, keeps the leaner application structure from Laravel 11, and does not ask for a dramatic rewrite in return.

The main reason to delay would be your own application, not Laravel 12. If package compatibility, test gaps, or business timing make the upgrade risky this week, fix that first. But from a framework perspective, Laravel 12 is the version worth aiming for.

If you want a few good next steps after this article, I would keep these pages nearby:

- [See the Laravel 11 release in current context before you leave it behind](/laravel-11)
- [Follow the practical path from Laravel 10 to 11](/laravel-11-upgrade-guide)
- [Double-check the whole release timeline before you plan upgrades](/laravel-versions)
- [Verify which Laravel version is actually running in your app](/check-laravel-version)
- [See what is shaping the next Laravel major](/laravel-13)
