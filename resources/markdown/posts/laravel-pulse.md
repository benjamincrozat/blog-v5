---
id: "01KKEW27DCZPXQBM87JV2KV9GJ"
title: "Laravel Pulse: what it does and how to install it"
slug: "laravel-pulse"
author: "benjamincrozat"
description: "Discover Laravel Pulse, a free open-source monitoring dashboard for Laravel apps, plus what it tracks, how to install it, and how to secure it."
categories:
  - "laravel"
  - "packages"
published_at: 2023-11-17T00:00:00+01:00
modified_at: 2026-03-20T12:41:49Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/09C9GV4aDcqTtbh.jpg"
sponsored_at: null
---
## What Laravel Pulse is

**[Laravel Pulse](https://pulse.laravel.com) is a free, open-source monitoring dashboard for the [Laravel framework](https://laravel.com).** It gives you at-a-glance insight into application usage, server stats, queue throughput, performance bottlenecks, trending exceptions, and custom cards.

Taylor Otwell, the creator of Laravel, [said](https://twitter.com/taylorotwell/status/1725210034399797365) that the tool was born out of frustration he had with Laravel Forge and its inability to quickly diagnose why the service was underperforming and which users were causing that.

![Laravel Pulse's dashboard in action.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-pulse-e87805b3b21317bee441.webp/public)

## The features Laravel Pulse offers

- **Application usage**: see which users make the most requests, hit the slowest endpoints, and dispatch the most jobs.
- **Server stats**: monitor CPU, memory, and disk usage from one dashboard.
- **Queue monitoring**: spot backlog trends before queue delays become user-visible.
- **Performance monitoring**: review slow routes, slow queries, slow jobs, and outgoing requests.
- **Trending exceptions**: see which exceptions are surfacing most often across the app.
- **Custom cards**: build your own metrics when the built-in cards are not enough.
- **Custom dashboard layout**: change the dashboard layout to fit the way your team works.

## Install Laravel Pulse

Pulse's first-party storage implementation currently requires a MySQL, MariaDB, or PostgreSQL database. If you are using a different engine, you will need a separate supported database for Pulse data.

You may install Pulse using Composer:

```bash
composer require laravel/pulse
```

Next, publish the Pulse configuration and migration files:

```bash
php artisan vendor:publish --provider="Laravel\Pulse\PulseServiceProvider"
```

Then run the migrations:

```bash
php artisan migrate
```

Once this is done, open your browser and visit `/pulse`.

![Laravel Pulse right after it has been installed.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-pulse-8c9ee7a064375fb17457.webp/public)

## Let Laravel Pulse monitor your server

Most Pulse recorders capture entries automatically, but the servers recorder and some third-party cards need a background process. Start it with:

```bash
php artisan pulse:check
```

Keep that daemon running in the background with a process monitor such as [Supervisor](http://supervisord.org). If you use Pulse's Redis ingest path, you will also need `php artisan pulse:work`, and both long-lived commands should be restarted during deployments with `php artisan pulse:restart`.

![Laravel Pulse's php artisan pulse:check command in action.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-pulse-bf75437dd76cff38c2dd.webp/public)

## Make Laravel Pulse secure

By default, Pulse is only accessible in the `local` environment. For production, define the `viewPulse` gate in your `AppServiceProvider` and apply whatever check you need:

```php
namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user): bool {
            return in_array($user->email, [
                'johndoe@example.com',
                // ...
            ], true);
        });
    }
}
```

## Install custom cards made by the community

The Laravel community is already working on custom cards to make it even more useful. Here's the link to the article where I try to gather the best ones I've found: [The best custom cards for Laravel Pulse](/best-laravel-pulse-custom-cards)

## Contribute to Laravel Pulse

Laravel Pulse is free, open source, and available through a GitHub repository at [laravel/pulse](https://github.com/laravel/pulse). You can send as many Pull Requests as you want for bug fixes and enhancements.

![Laravel Pulse's GitHub repository.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-pulse-b189909fa59cbde54f4d.webp/public)

## Laravel Pulse troubleshooting

### Laravel Pulse returns a 404 not found error

For anyone having a 404 after installing Laravel Pulse, here's a potential solution: You may have a wildcard hijacking the `pulse` route.

Here are possible fixes:
- Change the `path` configuration value in `config/pulse.php` to something like `/pulse/dashboard`.
- Or a more elegant solution would be to filter your wildcard route like so (which is what I did for this blog):

```php
Route::get('/{post:slug}', [PostController::class, 'show'])->name('posts.show')
    ->where('post', '^(?!pulse$).*$');
```

Basically, we are instructing Laravel to match the route only if it isn't `pulse`.

### My Laravel Pulse dashboard is empty

If your Laravel Pulse dashboard is empty, chances are that there's a problem with Livewire. If you open your developer tools and check for errors, you will most likely see a 404 not found error on */livewire/livewire.js*. Luckily, I wrote about this recurring issue caused by how Livewire serves its JavaScript by default: [Fix the /livewire/livewire.js 404 not found error](https://benjamincrozat.com/livewire-js-404-not-found)

If you want Pulse to become part of how you actually run the app, these are the next reads I would open:

- [Steal ideas for Laravel Pulse cards worth building](/best-laravel-pulse-custom-cards)
- [Build your own Laravel Pulse card once the built-ins are not enough](/custom-laravel-pulse-card)
- [Fix the Livewire JS 404 before it blocks the whole page](/livewire-js-404-not-found)
- [Build better Artisan prompts without extra ceremony](/laravel-prompts)
- [See when Laravel Volt is the simpler Livewire option](/laravel-volt)
