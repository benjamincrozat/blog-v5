---
id: "01KKEW27BWD8HYVKQ1X4B0M5AK"
title: "Laravel clear cache: every command and what it clears"
slug: "laravel-clear-cache"
author: "benjamincrozat"
description: "Use the right Laravel 13 cache command for application data, config, routes, views, events, or schedule locks without clearing more than you intended."
categories:
  - "laravel"
published_at: 2022-09-09T22:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/rOFuL6jd7Tu4wFz.jpg"
sponsored_at: null
---
To clear Laravel's framework caches during local development, run:

```bash
php artisan optimize:clear
```

In production, use the smallest command that fixes the problem. `optimize:clear` also calls `cache:clear`, so it can flush real application data from the default cache store.

![The terminal after running `php artisan optimize:clear` in Laravel.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-clear-cache-718bf1cf4dfab9897025.jpg/public)

## Laravel cache commands at a glance

| What you want to clear | Command | What must be rebuilt |
| --- | --- | --- |
| Framework caches plus the default application cache | `php artisan optimize:clear` | Config, routes, views, events, and application data as needed |
| Default application cache store | `php artisan cache:clear` | The app must refill cached values |
| A named cache store | `php artisan cache:clear redis` | Values in that store must be refilled |
| Tagged cache values | `php artisan cache:clear --tags=users,posts` | Only the tagged values |
| Cached configuration | `php artisan config:clear` | Laravel reads config files again |
| Cached routes | `php artisan route:clear` | Laravel loads route definitions again |
| Compiled Blade views | `php artisan view:clear` | Views compile on demand |
| Cached event discovery | `php artisan event:clear` | Event discovery runs again |
| Scheduler mutexes | `php artisan schedule:clear-cache` | Scheduler locks are recreated as jobs run |

I checked these commands against Laravel 13.25. The named cache store is a positional argument in Laravel 13: use `cache:clear redis`, not `cache:clear --store=redis`.

## What `optimize:clear` really clears

Laravel 13 runs these tasks inside `optimize:clear`:

- `config:clear`
- `cache:clear`
- `clear-compiled`
- `event:clear`
- `route:clear`
- `view:clear`

That makes it useful when a local app is stuck on stale framework state. It is broader than many production incidents need.

If you want the framework reset but need to preserve application cache data, Laravel 13 supports:

```bash
php artisan optimize:clear --except=cache
```

You can also pass a comma-separated list to `--except` when more than one task must be skipped.

## Clear the application cache

Use this for values stored through Laravel's cache API:

```bash
php artisan cache:clear
```

To target a configured store, pass its name as the argument:

```bash
php artisan cache:clear redis
```

To clear tagged items on a store that supports tags:

```bash
php artisan cache:clear --tags=users,posts
```

You can combine the store and tag options:

```bash
php artisan cache:clear redis --tags=users,posts
```

### Be careful with shared Redis or Memcached stores

Laravel's [cache documentation](https://laravel.com/docs/13.x/cache#removing-items-from-the-cache) warns that flushing a cache does not respect the configured cache prefix. On a shared store, `cache:clear` or `Cache::flush()` can remove entries owned by another application.

Use separate Redis databases or separate cache infrastructure when applications must not be flushed together.

## Clear config, routes, views, or events

### Configuration

```bash
php artisan config:clear
```

Run this when Laravel is still using an old environment or configuration value. Remember that `env()` should only be called from config files once configuration is cached.

### Routes

```bash
php artisan route:clear
```

Use this when a route was added, removed, or changed but production still behaves as if the old route table exists.

### Blade views

```bash
php artisan view:clear
```

This removes compiled Blade templates. It does not delete the Blade source files in `resources/views`.

### Events

```bash
php artisan event:clear
```

This clears Laravel's event discovery cache. It does not delete listeners or remove queued jobs.

### Scheduler locks

```bash
php artisan schedule:clear-cache
```

Use this when a task protected by `withoutOverlapping()` is stuck behind a stale scheduler mutex.

## Clear one application value in code

Prefer one key over the whole store:

```php
use Illuminate\Support\Facades\Cache;

Cache::forget('dashboard:stats');
```

For tagged data:

```php
Cache::tags(['users', 'reports'])->flush();
```

Avoid `Cache::flush()` unless you truly intend to empty the entire backing store.

## Rebuild production caches after a deployment

Laravel's matching optimization command is:

```bash
php artisan optimize
```

It caches configuration, events, routes, and views. A common deployment order is:

```bash
composer install --no-dev --prefer-dist --optimize-autoloader
php artisan migrate --force
php artisan optimize
```

Do not add `cache:clear` to every deployment by habit. Application cache may contain expensive query results, rate-limit state, or other data that should expire normally.

## What to do on shared hosting without SSH

Use the host's deployment hook, command runner, scheduled task UI, or support channel to run the exact Artisan command you need.

Do not add a permanent `/clear-cache` web route. Even behind authentication, it creates an unnecessary production control surface and makes it too easy to flush the wrong store from a browser request.

If Artisan itself cannot boot, verify the PHP binary, permissions, and active config first. Deleting random files from `bootstrap/cache` should not be the first move.

If cache maintenance is only one part of the operational cleanup, these are the next useful reads:

- [Use the Artisan commands you run every day with more confidence](/laravel-artisan)
- [Use flexible caching when freshness matters as much as speed](/flexible-caching-in-laravel)
- [See how the failover queue driver keeps jobs from disappearing](/laravel-failover-queue-driver)
- [See what Laravel Pulse can surface before users do](/laravel-pulse)
