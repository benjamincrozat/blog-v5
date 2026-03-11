---
id: "01KKEW27BWD8HYVKQ1X4B0M5AK"
title: "9 ways to clear Laravel's cache"
slug: "laravel-clear-cache"
author: "benjamincrozat"
description: "When in doubt, clear the cache. In this article, you'll learn why and how to do it like a pro."
categories:
  - "laravel"
published_at: 2022-09-10T00:00:00+02:00
modified_at: 2025-07-15T15:54:00+02:00
serp_title: "9 ways to clear Laravel's cache (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/rOFuL6jd7Tu4wFz.jpg"
sponsored_at: null
---
# TL;DR

Run `php artisan optimize:clear` during development, but prefer targeted commands in production.

![The terminal after running "php artisan optimize" to clear Laravel's cache.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/247/conversions/fUYsLdG7jk25WIM9Y9RZWNawuPaX6q-metaQ2xlYW5TaG90IDIwMjMtMTEtMDkgYXQgMTQuNDAuNDhAMngucG5n--medium.jpg)

## Quick reference commands

* **Everything:** `php artisan optimize:clear`
* **Application cache:** `php artisan cache:clear`
* **Specific cache store (Redis):** `php artisan cache:clear --store=redis`
* **Specific cache tags:** `php artisan cache:clear --tags=tag1,tag2`
* **Config cache:** `php artisan config:clear`
* **Routes cache:** `php artisan route:clear`
* **Views cache:** `php artisan view:clear`
* **Events cache:** `php artisan event:clear`
* **Schedule locks:** `php artisan schedule:clear-cache`

## What's actually cached in Laravel?

Laravel caches several types of data:

1. **Application Data:** Results of database queries or API calls.
2. **Configuration Files:** Cached config files for faster load.
3. **Routes:** Cached routes for quicker route matching.
4. **Views:** Cached compiled Blade templates.
5. **Events:** Cached event listeners.
6. **Schedule Locks:** Prevent task overlaps.

## Clear everything (`optimize:clear`)

The fastest way to clear every Laravel cache is:

```bash
php artisan optimize:clear
```

This clears configuration, bootstrap files, events, routes, views, and application caches. Ideal during development but avoid in production as it forces Laravel to rebuild caches, temporarily impacting performance.

## Clear a specific cache

### Application cache

Clear general application cache:

```bash
php artisan cache:clear
```

Clear Redis-specific cache store:

```bash
php artisan cache:clear --store=redis
```

Clear tagged cache items:

```bash
php artisan cache:clear --tags=user:123,posts
```

### Configuration cache

Clears cached config files:

```bash
php artisan config:clear
```

### Routes cache

Clears cached routes:

```bash
php artisan route:clear
```

### Views cache

Clears cached Blade views:

```bash
php artisan view:clear
```

### Events cache

Clears cached event listeners:

```bash
php artisan event:clear
```

### Schedule locks

Clears schedule lock cache (useful if cron jobs are stuck):

```bash
php artisan schedule:clear-cache
```

## Programmatic clearing (`Cache::forget` vs `flush`)

In your PHP code, clear a specific key:

```php
Cache::forget('key');
```

Or flush everything (**dangerous on production!**):

```php
Cache::flush();
```

Prefer targeted clears (`forget`) in production to avoid performance hits.

## One-click route for shared hosting

Add this route for easy cache clearing when SSH access is unavailable:

```php
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
	
    return back()->with('status', 'All caches cleared.');
})->middleware('auth');
```

**Never expose this publicly in production.**

## Troubleshooting: what if Artisan commands fail?

If Artisan commands themselves throw errors:

1. Manually delete files in `bootstrap/cache/*.php`.
2. Run `composer dump-autoload` to refresh class mappings.
3. If a deleted provider causes errors, temporarily recreate the provider class, run `config:clear`, then safely remove it again.

## Performance & deployment best Practices

* **Development:** Frequent clearing is okay.
* **Production:** Rarely clear caches. Prefer cache warming with `config:cache` and `route:cache` during deployment.
* **Never** permanently disable caching in production (`CACHE_DRIVER=null`), it's for debugging only.

## Laravel Version Compatibility

| Laravel Version | Supported Commands                                    |
| --------------- | ----------------------------------------------------- |
| ≤ 8             | Basic commands supported; `optimize:clear` from 8.24+ |
| 9–12            | All commands listed above fully supported             |

## FAQ

**Does `optimize:clear` delete Redis data?**
No. It clears application bootstrap, routes, config, views, and events—not Redis data.

**Is `Cache::flush()` safe in production?**
No. It removes all cached data instantly, potentially causing database spikes.

**What changed in Laravel 12 regarding cache?**
No significant cache command changes. The commands listed here fully apply.