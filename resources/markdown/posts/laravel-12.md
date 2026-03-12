---
id: "01KKEW27B9KMJCRAM3TKA1J9DN"
title: "Laravel 12: new starter kits, changes, and upgrade tips"
slug: "laravel-12"
author: "benjamincrozat"
description: "Laravel 12 arrived on February 24, 2025. I cover the new starter kits and other changes in addition to how to upgrade in minutes."
categories:
  - "laravel"
published_at: 2024-03-11T00:00:00+01:00
modified_at: 2025-09-15T21:51:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/NR16LmxmtqoscGx.png"
sponsored_at: null
---
## Introduction

I first published this piece to track what might land in Laravel 12. Now that Laravel 12 shipped on February 24, 2025, I have refreshed the post to cover what actually changed, how long it is supported, and a quick path to upgrade. If you maintain a Laravel 11 application or you are starting a new project, this guide is for you. See the official [Laravel 12 Release Notes](https://laravel.com/docs/12.x/releases) for the canonical summary.

## Release date and support policy

Laravel 12 was released on February 24, 2025. Laravel 11 supports PHP 8.2–8.4 and receives bug fixes until September 3, 2025, and security fixes until March 12, 2026. Laravel 12 supports PHP 8.2–8.4 and receives bug fixes until August 13, 2026, and security fixes until February 24, 2027. Modern, non‑LTS releases receive 18 months of bug fixes and two years of security fixes.

| Version | PHP | Release | Bug Fixes Until | Security Fixes Until |
| ------- | --- | ------- | --------------- | -------------------- |
| 11 | 8.2–8.4 | March 12, 2024 | September 3, 2025 | March 12, 2026 |
| 12 | 8.2–8.4 | February 24, 2025 | August 13, 2026 | February 24, 2027 |

Note: The last LTS release was Laravel 6. LTS policy details remain documented on the Laravel 6 page.

## What’s new in Laravel 12

Laravel 12 focuses on quality of life, clear defaults, and a modern application start. Here are the headline features I think most teams will care about.

### New starter kits, plus an AuthKit variant

Laravel 12 introduces official starter kits for React, Vue, and Livewire. The React and Vue kits use Inertia 2, TypeScript, Tailwind, and shadcn UI. The Livewire kit uses Flux UI and Laravel Volt. Each kit can also be generated in a WorkOS AuthKit variant that includes social login, passkeys, and SSO. Breeze and Jetstream are no longer receiving additional updates. For guidance and options, see the [Starter Kits documentation](https://laravel.com/docs/12.x/starter-kits).

What to do: choose the kit that matches your team’s stack. Pick React or Vue if you want an SPA‑style experience through Inertia, or Livewire if you prefer Blade‑first development.

### UUIDv7 by default in HasUuids

Models that use the `HasUuids` trait now generate ordered UUIDv7 identifiers by default. If you need to keep UUIDv4, import and alias the provided trait to preserve your existing signatures:

```php
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;
```

See the [Laravel 12 Upgrade Guide](https://laravel.com/docs/12.x/upgrade#models-and-uuidv7) for details.

### Carbon 3 requirement

Laravel 12 requires Carbon 3, which replaces Carbon 2 in the framework’s dependencies. Most apps will not need code changes, but verify any direct Carbon APIs your app uses. See the [Upgrade Guide](https://laravel.com/docs/12.x/upgrade#carbon-3).

### PHP 8.4 support

Laravel 12 supports PHP 8.2 through 8.4 and aims for minimal breaking changes. If you are on PHP 8.2 or 8.3 today, upgrade your runtime when convenient. Reference the [Support Policy table](https://laravel.com/docs/12.x/releases#support-policy).

### xxHash replaces md5 in internal paths

Key internal hashing paths have moved from md5 to xxHash for better performance. This is an internal framework improvement and should not require changes in application code. See the [12.x changelog](https://github.com/laravel/framework/blob/12.x/CHANGELOG.md).

### Other noteworthy changes

- MariaDB schema dump now uses MariaDB‑native CLI tools (`mariadb-dump` and `mariadb`) for better compatibility, and the unsupported `--column-statistics` flag is not used when dumping for MariaDB.
- Chunked queries now honor user‑defined limits and offsets, which fixes edge cases in pagination or batched processing.
- `$request->mergeIfMissing()` supports nested data via dot notation.
- The `image` validation rule excludes SVGs by default. Opt in with `image:allow_svg` or `File::image(allowSvg: true)`.
- `ResponseFactory` now formally includes `streamJson`, and the responses docs cover streaming usage. See [HTTP Responses](https://laravel.com/docs/12.x/responses#streamed-json-responses).
- Validator preserves numeric rule keys.
- `Str::is()` matches multiline strings consistently.

## Upgrading from 11 to 12

Estimated time: a few minutes for most apps. Start with the official [Upgrade Guide](https://laravel.com/docs/12.x/upgrade).

**Before you upgrade:**
- Run your test suite and back up your database.
- Search your codebase for HasUuids, custom `ResponseFactory` implementations, and SVG image validation rules.

**Checklist:**

1. Update dependencies using `composer require laravel/framework:^12.0 -W` Also, update your test runner versions as noted in the guide.

2. Ensure Carbon 3 is installed and remove any Carbon 2 constraints.

3. Decide on UUID behavior. If you need UUIDv4, alias `HasVersion4Uuids` as shown above and review any ordering assumptions around IDs.

4. Review validation for images. If you rely on SVG uploads, explicitly allow them with `image:allow_svg` or `File::image(allowSvg: true)`.

5. If you use chunked queries, re‑run your tests around pagination or batched imports to confirm limits and offsets behave as expected.

6. If you run `schema:dump` on MariaDB, make sure `mariadb` and `mariadb-dump` are available on your PATH.

7. If you implement `Illuminate\Contracts\Routing\ResponseFactory`, add the `streamJson` method.

## Install Laravel 12 today

The installer is the recommended path. It prompts you for your testing framework, database, and starter kit.

```bash
laravel new example-app
```

After creation, install frontend dependencies and start the dev servers:

```bash
cd example-app
npm install && npm run build
composer run dev
```

See the [Installation guide](https://laravel.com/docs/12.x/installation) for details. If you prefer Composer, constrain to the current major:

```bash
composer create-project laravel/laravel:^12.0 my-app
```

The official docs emphasize the installer and its prompts.

## Contribute to Laravel

If you want to help, here is the short version I follow:

- Bug fixes can target the `12.x` branch, while new features should target `master` for the next release. Review the [laravel/framework pull requests](https://github.com/laravel/framework/pulls).
- Read the [Contribution Guide](https://laravel.com/docs/12.x/upgrade) for branch targeting, tests, and review expectations.

![Screenshot of the Laravel 12 release notes highlighting starter kits and UUIDv7.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-12-85022b83a00bbc25894c.webp/public)

## FAQ

- Is Laravel 12 an LTS release?
  No. The last LTS was Laravel 6. Current releases receive 18 months of bug fixes and two years of security fixes. See the [Support Policy](https://laravel.com/docs/12.x/releases#support-policy) and the [Laravel 6 LTS page](https://laravel.com/docs/6.x/releases).

- Which PHP versions does Laravel 12 support?
  PHP 8.2 through 8.4, as listed in the Release Notes.

- What changed about UUIDs, and how do I keep v4?
  HasUuids now emits UUIDv7. To keep v4, alias `HasVersion4Uuids` as `HasUuids` in your models. See the [Upgrade Guide](https://laravel.com/docs/12.x/upgrade#models-and-uuidv7).

- How do I choose a starter kit?
  Use React or Vue if you want an Inertia‑powered SPA experience with TypeScript and shadcn UI. Choose Livewire if you prefer Blade‑first development with Volt (optional) and Flux. The [Starter Kits docs](https://laravel.com/docs/12.x/starter-kits) have examples.

- How long will Laravel 12 receive security updates?
  Through February 24, 2027. See the [Release Notes](https://laravel.com/docs/12.x/releases).

## Conclusion

I upgraded a small Laravel 11 app to 12 in one sitting, and it was straightforward. If you are on PHP 8.2 or newer and do not rely on Carbon 2 or custom UUIDv4 semantics, I recommend upgrading soon to take advantage of the new starter kits and to stay within the current support window. New projects should start on 12.x by default.

If you are planning the next upgrade after landing on Laravel 12, these are the release reads I would keep open:

- [See where this fits in Laravel's release history](/laravel-versions)
- [See what Laravel 13 is shaping up to change](/laravel-13)
- [Check what changes before you move to Laravel 11](/laravel-11-upgrade-guide)
- [See the biggest Laravel 11 changes in one pass](/laravel-11)
- [Double-check which Laravel version is actually running](/check-laravel-version)
