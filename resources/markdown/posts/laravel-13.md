---
id: "01KKEW27BBGFKC1KETMVEMKHM5"
title: "Laravel 13 released: features and upgrade guide"
slug: "laravel-13"
author: "benjamincrozat"
description: "Laravel 13 was released on March 17, 2026. Here is what changed, how to install it today, and what to verify before upgrading from Laravel 12."
categories:
  - "laravel"
published_at: 2025-07-06T19:26:00Z
modified_at: 2026-03-18T11:51:37Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "01JZZD9NKMTM9H87RFVTHT0GWX.png"
sponsored_at: null
---
## Introduction

Laravel 13 was released on March 17, 2026. The official [release notes](https://laravel.com/docs/13.x/releases) frame it as a mostly smooth major, and that is the right takeaway: the framework's [`v13.0.0` release](https://github.com/laravel/framework/releases/tag/v13.0.0) is live, the [`13.x` application skeleton](https://github.com/laravel/laravel/blob/13.x/composer.json) requires PHP 8.3 and `laravel/framework:^13.0`, and I verified on March 18, 2026 that both `composer create-project laravel/laravel:^13.0` and `laravel new` create fresh Laravel 13.0.0 apps.

One small oddity is that Laravel's [support policy](https://laravel.com/docs/13.x/releases#support-policy) still lists Laravel 13's release as "Q1 2026" instead of an exact date. So for the release day, use the GitHub release above; for the support window, use Laravel's official table.

## Support status and timeline

Laravel 13 is now the current major if your app is ready for PHP 8.3 or newer. If you are still on PHP 8.2, [Laravel 12](/laravel-12) remains the right landing spot for now.

| Version | PHP | Release date | Bug fixes until | Security fixes until | Status on March 18, 2026 |
| ------- | --- | ------------ | --------------- | -------------------- | ------------------------- |
| 12 | 8.2-8.5 | February 24, 2025 | August 13, 2026 | February 24, 2027 | Supported |
| 13 | 8.3-8.5 | March 17, 2026 (`v13.0.0`) | Q3 2027 on the official table | Q1 2028 on the official table | Supported |

If you want the broader release ladder before you plan an upgrade, my [Laravel versions](/laravel-versions) guide keeps the whole timeline in one place.

## How to install Laravel 13 today

If you want the current stable release with Laravel's modern setup flow, use the installer:

```bash
laravel new my-app
```

I verified on March 18, 2026 that the current installer creates a Laravel 13.0.0 application.

If you prefer to pin the major explicitly, use Composer:

```bash
composer create-project laravel/laravel:^13.0 my-app
```

I verified that command also resolves to Laravel 13.0.0.

If you are checking an existing codebase before upgrading it, run `php artisan --version` or follow my guide on [how to check your Laravel version](/check-laravel-version).

## What changed in Laravel 13

Laravel 13 is not a flashy rewrite. The official docs emphasize minimal breaking changes, but there are a few additions that matter before you reduce it to "Laravel 12 plus one."

### Laravel AI SDK

The headline feature in the [release notes](https://laravel.com/docs/13.x/releases#laravel-ai-sdk) is the new [Laravel AI SDK](https://laravel.com/ai). It gives Laravel first-party abstractions for text generation, embeddings, agents, audio, images, and vector stores.

That matters beyond chat features. It gives Laravel a built-in path for semantic search, support tooling, workflow automation, and AI-assisted product features without making you choose a provider-specific package first.

The [installation docs](https://laravel.com/docs/13.x/installation#laravel-and-ai) also now include a dedicated "Laravel and AI" section, and existing apps are pointed to [Laravel Boost](https://laravel.com/docs/13.x/ai) for AI-assisted development workflows.

```php
use App\Ai\Agents\SalesCoach;

$response = SalesCoach::make()->prompt('Analyze this sales transcript...');

return (string) $response;
```

### First-party JSON:API resources

Laravel 13 adds [JSON:API resources](https://laravel.com/docs/13.x/eloquent-resources#jsonapi-resources) through `Illuminate\Http\Resources\JsonApi\JsonApiResource`.

If you build APIs for mobile apps or third-party clients, that is more useful than it sounds. You can now generate JSON:API-shaped resources with first-party tooling instead of stitching the spec together yourself:

```bash
php artisan make:resource PostResource --json-api
```

The generated resource includes dedicated `attributes` and `relationships` properties, which is a much cleaner default if your API actually targets JSON:API.

### Request forgery protection is stricter by default

Laravel 13 replaces the old CSRF middleware name with [request forgery protection](https://laravel.com/docs/13.x/csrf#preventing-csrf-requests) built around `PreventRequestForgery`.

In modern browsers it first checks the `Sec-Fetch-Site` header and only falls back to token validation when origin signals are unavailable. That is a real security improvement, but it is also an upgrade surface if your app has custom CSRF flows, older browser constraints, or unusual subdomain behavior.

### PHP attributes are much more useful

The [release notes](https://laravel.com/docs/13.x/releases#expanded-php-attributes) also expand Laravel's PHP attribute story. You can now attach controller middleware and authorization through attributes like `#[Middleware]` and `#[Authorize]`, and queue jobs gain new first-party attributes such as `#[DeleteWhenMissingModels]`, `#[FailOnTimeout]`, `#[Tries]`, and `#[WithoutRelations]`.

Laravel News' [Laravel 13 roundup](https://laravel-news.com/laravel-13) is useful here because it highlights how broad that change really is. Eloquent models can now use attributes like `#[Table]`, `#[Hidden]`, and `#[Fillable]`, while console commands can use `#[Signature]` and `#[Description]` instead of only class properties.

That makes the feature more than a nice syntax tweak for controllers and queues. Laravel 13 is clearly moving more framework configuration toward attributes across models, commands, form requests, API resources, factories, and test seeders.

If your team already prefers attribute-driven configuration, Laravel 13 makes that approach feel first-class instead of experimental.

### Queue routing and cache TTL extension

Laravel 13 adds [queue routing](https://laravel.com/docs/13.x/queues#queue-routing) by job class:

```php
Queue::route(ProcessPodcast::class, queue: 'podcasts', connection: 'redis');
```

That gives you one central place to decide where a job should run instead of scattering queue names across multiple job classes.

It also adds [`Cache::touch(...)`](https://laravel.com/docs/13.x/cache), which lets you extend a cache item's TTL without reading and rewriting the value. Small feature, very practical.

### Semantic and vector search

The new [search documentation](https://laravel.com/docs/13.x/search#semantic-vector-search) covers embeddings, vector columns, `pgvector`, similarity search, and reranking.

If you are already on PostgreSQL, Laravel 13 now has a much more coherent first-party story for semantic search:

```php
$documents = DB::table('documents')
    ->whereVectorSimilarTo('embedding', 'Best wineries in Napa Valley')
    ->limit(10)
    ->get();
```

This pairs naturally with the AI SDK because string inputs can be converted to embeddings through first-party APIs.

## Upgrading from Laravel 12 to 13

The official [upgrade guide](https://laravel.com/docs/13.x/upgrade) still estimates about 10 minutes for many Laravel 12 apps. That sounds optimistic, but the general message is fair: this is a much smaller upgrade than the feature list makes it look.

### Update the main dependencies first

Start with the dependency updates from the guide:

- `laravel/framework` to `^13.0`
- `laravel/tinker` to `^3.0` if you use it
- `phpunit/phpunit` to `^12.0`
- `pestphp/pest` to `^4.0` if your app uses Pest

If you create fresh apps with the Laravel installer, update that too:

```bash
composer global update laravel/installer
```

If you use Herd's bundled installer, upgrade Herd instead.

### What I would audit before deploying

Laravel 13 is easy only when your app stays close to the defaults. These are the upgrade guide sections I would read first:

- [Request forgery protection](https://laravel.com/docs/13.x/upgrade#request-forgery-protection) if you depend on custom CSRF behavior, older browsers, or subdomain request flows.
- [Cache `serializable_classes`](https://laravel.com/docs/13.x/upgrade#cache-serializable_classes-configuration) if you store PHP objects in cache. Laravel now hardens object deserialization by default.
- [Cache prefixes and session cookie names](https://laravel.com/docs/13.x/upgrade#cache-prefixes-and-session-cookie-names) if you relied on framework-generated defaults for Redis prefixes or session naming.
- [Custom contracts and custom cache stores](https://laravel.com/docs/13.x/upgrade#custom-contracts-and-custom-cache-stores) if you implement framework interfaces yourself. Several contracts and cache store APIs gained new methods or signatures.
- [MySQL `DELETE` queries with `JOIN`, `ORDER BY`, and `LIMIT`](https://laravel.com/docs/13.x/upgrade#mysql-delete-queries-with-join-order-by-and-limit) because queries that were previously compiled loosely may now throw database errors on unsupported engines.
- [Polymorphic pivot table naming](https://laravel.com/docs/13.x/upgrade#polymorphic-pivot-table-name-generation) if you use custom morph pivot models and relied on inferred table names.

If you are jumping from Laravel 11 or older, still upgrade one major at a time. Laravel 13 is easy from 12. Skipped-version upgrades are where most of the pain hides.

## FAQ

### When was Laravel 13 released?

Laravel 13 was released on March 17, 2026, when the framework's [`v13.0.0` release](https://github.com/laravel/framework/releases/tag/v13.0.0) was tagged.

### What PHP version does Laravel 13 require?

PHP 8.3 or newer. The official support table currently lists Laravel 13 support for PHP 8.3 through 8.5.

### Can I install Laravel 13 with `laravel new`?

Yes. I verified on March 18, 2026 that `laravel new my-app` creates a fresh Laravel 13.0.0 application with the current installer.

### Is Laravel 13 a big upgrade?

Probably not for a typical Laravel 12 app. The official [release notes](https://laravel.com/docs/13.x/releases) emphasize minimal breaking changes, and the official [upgrade guide](https://laravel.com/docs/13.x/upgrade) estimates about 10 minutes for many applications. The real risk is in custom cache behavior, request forgery edge cases, and hand-rolled framework integrations.

### What should I check first before upgrading?

I would start with request forgery protection, cache serialization rules, cache and session naming defaults, and any custom contracts or cache store implementations. Those are the places where Laravel 13 is most likely to surprise a mature app.

## Conclusion

Laravel 13 keeps Laravel's recent pattern intact: make the framework upgrade small, then ship more interesting capabilities on top. The AI SDK, JSON:API resources, semantic search support, expanded PHP attributes, queue routing, and stricter request forgery protection are the changes most teams will actually notice.

If your app is already on Laravel 12 with PHP 8.3+, this is a reasonable release to start testing now. If you are still on PHP 8.2 or behind on framework upgrades, Laravel 12 is still a healthy stopping point while you catch up.

If you are planning the move instead of waiting for upgrade pain to find you, keep these nearby:

- [See the Laravel 12 changes you are upgrading from](/laravel-12)
- [Compare support windows across Laravel releases](/laravel-versions)
- [Verify which Laravel version your app is actually running](/check-laravel-version)
- [Refresh the Laravel 11-to-12 upgrade path if you skipped a year](/laravel-11-upgrade-guide)
