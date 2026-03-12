---
id: "01KKEW27BBGFKC1KETMVEMKHM5"
title: "Laravel 13: new features and upgrade guide"
slug: "laravel-13"
author: "benjamincrozat"
description: "Laravel 13 brings the AI SDK, JSON:API resources, vector search, queue routing, and stronger security defaults. Here is what changed and how to upgrade from Laravel 12."
categories:
  - "laravel"
published_at: 2025-07-06T21:26:00+02:00
modified_at: 2026-03-12T15:01:28+01:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "01JZZD9NKMTM9H87RFVTHT0GWX.png"
sponsored_at: null
---
## Introduction

I first published this article as an early tracker. As of March 12, 2026, Laravel's official [13.x release notes](https://laravel.com/docs/13.x/releases) and [13.x upgrade guide](https://laravel.com/docs/13.x/upgrade) are live, so I have refreshed this page around what the framework now documents publicly.

One detail is still a little unusual: the official [support policy](https://laravel.com/docs/13.x/releases#support-policy) still lists Laravel 13's release window as Q1 2026 instead of showing a precise calendar date. So this guide focuses on the supported PHP versions, the headline features, and the practical upgrade path from [Laravel 12](/laravel-12).

## Release date and support timeline

Laravel still follows its yearly major release cadence. The official [support policy](https://laravel.com/docs/13.x/releases#support-policy) says non-LTS releases receive 18 months of bug fixes and 2 years of security fixes.

Laravel 12 remains in a comfortable support window, so there is no need to panic-upgrade. If you want the broader history behind Laravel's release cadence, I also maintain a simple [Laravel versions](/laravel-versions) reference.

| Version | PHP | Release | Bug fixes until | Security fixes until |
| ------- | --- | ------- | --------------- | -------------------- |
| 12 | 8.2-8.5 | February 24, 2025 | August 13, 2026 | February 24, 2027 |
| 13 | 8.3-8.5 | Q1 2026 on the official page | Q3 2027 | Q1 2028 |

## Requirements

Laravel 13 requires PHP 8.3 or newer, and the official support table currently shows support through PHP 8.5. If you are still on PHP 8.2, stay on Laravel 12 until your runtime is ready.

The official `13.x` application skeleton already reflects that target. Its [`composer.json`](https://github.com/laravel/laravel/blob/13.x/composer.json) requires PHP `^8.3` and `laravel/framework:^13.0`, which lines up with the release notes and upgrade guide.

## What's new in Laravel 13

Laravel describes 13 as a fairly small upgrade in breaking-change terms, but a meaningful release in capabilities. These are the additions I think most teams will care about.

### Laravel AI SDK

The biggest new platform feature is the first-party [Laravel AI SDK](https://laravel.com/ai). It gives Laravel a unified API for text generation, tool-calling agents, embeddings, audio, images, and vector-store workflows.

That matters even if you are not building a chatbot. It gives the framework a first-party path for semantic search, content generation, automation, and AI-assisted product features without forcing you into a provider-specific abstraction on day one.

```php
use App\Ai\Agents\SalesCoach;

$response = SalesCoach::make()->prompt('Analyze this sales transcript...');

return (string) $response;
```

The installation guide also now includes a dedicated [Laravel and AI](https://laravel.com/docs/13.x/installation#laravel-and-ai) section and points readers to [Laravel Boost](https://laravel.com/docs/13.x/ai) for AI-assisted development workflows.

### First-party JSON:API resources

Laravel 13 ships with first-party [JSON:API resources](https://laravel.com/docs/13.x/eloquent-resources#jsonapi-resources). If you build APIs for mobile apps or third-party clients, this is a welcome change because you no longer need to assemble JSON:API-shaped responses by hand.

The new `JsonApiResource` handles resource objects, relationships, sparse fieldsets, includes, links, and the correct `application/vnd.api+json` response header.

### Stronger request forgery protection

Laravel's CSRF layer has been formalized as [`PreventRequestForgery`](https://laravel.com/docs/13.x/csrf#preventing-csrf-requests). In modern browsers, it first checks the `Sec-Fetch-Site` header to verify same-origin requests, then falls back to normal CSRF token validation when origin verification is unavailable.

That is a practical security improvement, not just a rename. If your app has custom CSRF assumptions, subdomain flows, or older browser constraints, this is one of the first sections I would read in the official upgrade guide.

### Queue routing and cache TTL extension

Laravel 13 adds [queue routing](https://laravel.com/docs/13.x/queues#queue-routing) by job class, which makes central queue decisions easier to read and maintain:

```php
Queue::route(ProcessPodcast::class, connection: 'redis', queue: 'podcasts');
```

It also adds [`Cache::touch(...)`](https://laravel.com/docs/13.x/cache), which lets you extend an existing cache item's TTL without reading and writing the value again. Small feature, useful in real apps.

### Semantic and vector search

Laravel 13 goes much further on semantic search. The official [search documentation](https://laravel.com/docs/13.x/search#semantic-vector-search) now covers vector columns, `pgvector`, embeddings, similarity search, and reranking.

If you are already using PostgreSQL, this is one of the most interesting additions in the release because Laravel now has a much more coherent story for AI-powered search:

```php
$documents = DB::table('documents')
    ->whereVectorSimilarTo('embedding', 'Best wineries in Napa Valley')
    ->limit(10)
    ->get();
```

This part of the release pairs naturally with the AI SDK because you can generate embeddings from strings and query them with first-party APIs.

## Upgrading from Laravel 12 to 13

The official [upgrade guide](https://laravel.com/docs/13.x/upgrade) estimates about 10 minutes for many applications, which matches Laravel's "minimal breaking changes" message for this cycle.

### Start with dependencies

Update these packages first:

- `laravel/framework` to `^13.0`
- `phpunit/phpunit` to `^12.0`
- `pestphp/pest` to `^4.0`

If you create fresh apps with the Laravel installer, update that too:

```bash
composer global update laravel/installer
```

If you use Herd's bundled installer, upgrade Herd instead.

### Review the few changes that can bite

These are the upgrade guide sections I would check even on a small app:

- [Request forgery protection](https://laravel.com/docs/13.x/upgrade#request-forgery-protection) if you depend on custom CSRF behavior, older browsers, or subdomain request flows.
- [Cache `serializable_classes`](https://laravel.com/docs/13.x/upgrade#cache-serializable_classes-configuration) if you store PHP objects in cache. Laravel now hardens cache unserialization by default.
- [Cache prefixes and session cookie names](https://laravel.com/docs/13.x/upgrade#cache-prefixes-and-session-cookie-names) if you relied on framework-generated defaults.
- [Custom contracts and custom cache stores](https://laravel.com/docs/13.x/upgrade) if you implement framework interfaces yourself. Several contracts gained new methods or signatures.
- [MySQL `DELETE` queries with `JOIN`, `ORDER BY`, and `LIMIT`](https://laravel.com/docs/13.x/upgrade#mysql-delete-queries-with-join-order-by-and-limit) because queries that were previously compiled loosely may now throw database errors on unsupported engines.
- [Polymorphic pivot table naming](https://laravel.com/docs/13.x/upgrade#polymorphic-pivot-table-name-generation) if you use custom morph pivot models and relied on inferred table names.

For most teams, the message is simple: Laravel 13 looks like an easy framework upgrade, but a few infrastructure-level defaults around cache, request forgery protection, and database SQL generation deserve a careful read.

## Installing Laravel 13

For a new application, the current installation docs still recommend the Laravel installer:

```bash
laravel new example-app
cd example-app
npm install && npm run build
composer run dev
```

Before doing that, make sure your local environment runs PHP 8.3 or newer and that your Laravel installer or Herd installation is up to date for 13.x compatibility.

## FAQ

### When is Laravel 13 coming out?

As of March 12, 2026, the official [support policy](https://laravel.com/docs/13.x/releases#support-policy) still says Q1 2026 instead of showing an exact release date. The official 13.x release notes and upgrade guide are already public, which is why this article now focuses on the documented feature set and upgrade path.

### What PHP version does Laravel 13 require?

PHP 8.3 or newer. The official support table currently lists Laravel 13 support for PHP 8.3 through 8.5.

### How long is Laravel 12 supported?

Laravel 12 receives bug fixes until August 13, 2026 and security fixes until February 24, 2027, per the official [support policy](https://laravel.com/docs/13.x/releases#support-policy).

### Is Laravel 13 a big upgrade?

Probably not for most apps. Laravel's [release notes](https://laravel.com/docs/13.x/releases) call out minimal breaking changes, and the official [upgrade guide](https://laravel.com/docs/13.x/upgrade) estimates about 10 minutes for many Laravel 12 applications.

### What should I check first before upgrading?

I would start with request forgery protection, cache serialization rules, cache and session naming defaults, and any custom framework contract implementations. Those are the places where the official upgrade guide lists the highest practical risk.

## Conclusion

Laravel 13 looks like a good example of Laravel's current strategy: keep the upgrade small, then add meaningful new platform features on top. The AI SDK, JSON:API resources, semantic search support, queue routing, and stronger request forgery defaults are the highlights.

If you are already on Laravel 12 with PHP 8.3+, I would start reading the official [upgrade guide](https://laravel.com/docs/13.x/upgrade) now. If you are not ready yet, Laravel 12 still has plenty of support runway left.
