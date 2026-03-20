---
id: "01KKEW27DCZPXQBM87JV2KV9GJ"
title: "Laravel Pulse: what it tracks and how to set it up"
slug: "laravel-pulse"
author: "benjamincrozat"
description: "Learn what Laravel Pulse tracks, how to install and secure it, when to run pulse:check or pulse:work, and when it is a better fit than Telescope."
categories:
  - "laravel"
  - "packages"
published_at: 2023-11-16T23:00:00Z
modified_at: 2026-03-20T13:05:54Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/laravel-pulse/hero.png"
sponsored_at: null
---
## What Laravel Pulse is

**[Laravel Pulse](https://pulse.laravel.com) is Laravel's first-party dashboard for application health and performance.** It is the package I would reach for when I want one screen that answers practical questions like:

- which routes are suddenly slow
- whether queues are backing up
- which users are generating the most load
- whether a server is under memory or CPU pressure
- which exceptions are trending right now

Pulse is much more of an **at-a-glance operations dashboard** than a step-by-step debugger. If you need deep per-request inspection, [Laravel Debugbar](/laravel-debugbar) or Telescope are closer fits. If you want quick visibility into the app's overall health without leaving Laravel, Pulse is the stronger tool.

![Current Laravel Pulse dashboard preview from the official site](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/laravel-pulse/hero.png/public)

## What Laravel Pulse tracks

Out of the box, Pulse can surface:

- **Application usage**: top users by requests, slow requests, and jobs
- **Slow requests and routes**: useful when the app feels slower but the cause is not obvious yet
- **Slow queries and slow jobs**: good early warning before users start complaining
- **Queues**: enough to tell whether work is piling up or processing normally
- **Server health**: CPU, memory, and disk usage from each machine running `pulse:check`
- **Exceptions**: recurring errors that deserve attention
- **Custom cards**: useful when the built-in metrics are close, but not quite your app's real bottleneck

That mix is why Pulse feels practical. It is not trying to replace a full observability stack. It gives Laravel teams a dashboard that is close to the application and quick to extend.

![Laravel Pulse application usage card from the official feature preview](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/laravel-pulse/application-usage.png/public)

## Install Laravel Pulse

Pulse's first-party storage driver needs **MySQL, MariaDB, or PostgreSQL**. If your app uses another database engine, keep that app database and give Pulse its own supported connection.

The basic install is short:

```bash
composer require laravel/pulse
```

Next, publish the Pulse configuration and migration files:

```bash
php artisan vendor:publish --provider="Laravel\Pulse\PulseServiceProvider"
```

Then create the tables:

```bash
php artisan migrate
```

After that, open `/pulse`.

If you want Pulse data isolated from your main app database, point `PULSE_DB_CONNECTION` to a dedicated connection in `.env`.

## What to run in production

This part is where many Pulse tutorials stay too light.

Most recorders start collecting data once the package is installed and the app has traffic. But the **servers** card needs a long-running process:

```bash
php artisan pulse:check
```

Run that under a process monitor such as Supervisor. If you deploy on multiple servers, run it on every server you want represented in the dashboard.

If you switch Pulse to **Redis ingest** for busier apps, add this worker too:

```bash
php artisan pulse:work
```

And during deploys, restart the long-lived Pulse processes so they pick up new code:

```bash
php artisan pulse:restart
```

That is the minimum operational setup I would remember:

- install the package
- migrate
- visit `/pulse`
- keep `pulse:check` alive
- add `pulse:work` only when you use Redis ingest
- restart Pulse workers during deploys

## Secure the Pulse dashboard

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

The simplest real-world rule is usually “admins only” or “specific team emails only.”

## Customize the dashboard without much ceremony

Pulse publishes a dashboard view you can edit, and the dashboard itself is powered by Livewire. That means you can rearrange cards, make some cards wider, or publish the dashboard view and tailor it without building a separate frontend.

That is a big part of the appeal. Pulse starts useful, then grows with your app:

- show more server information
- expand the cards your team checks most
- add your own custom cards for business-specific signals

If that is the direction you want to go, [these Laravel Pulse custom cards](/best-laravel-pulse-custom-cards) are a good source of ideas, and [this custom Pulse card tutorial](/custom-laravel-pulse-card) is the next step once you are ready to build your own.

## Laravel Pulse vs Telescope

This is the comparison I find most useful:

- **Use Pulse** when you want trends, summaries, and “what is unhealthy right now?”
- **Use Telescope** when you want to inspect individual requests, queries, jobs, and exceptions in detail

Pulse helps you notice a problem quickly. Telescope helps you dissect it. They are complementary, not interchangeable.

## Laravel Pulse troubleshooting

### Laravel Pulse returns a 404 not found error

If `/pulse` returns a 404 after installation, a wildcard route may be swallowing the path before Pulse can handle it.

You have two common fixes:

- change the Pulse path in `config/pulse.php`
- exclude `pulse` from your wildcard route

```php
Route::get('/{post:slug}', [PostController::class, 'show'])->name('posts.show')
    ->where('post', '^(?!pulse$).*$');
```

That route constraint tells Laravel to match the wildcard only when the slug is **not** `pulse`.

### Laravel Pulse looks empty

An empty dashboard usually means one of these:

- you just installed Pulse and there is not enough traffic yet
- `pulse:check` is not running, so the servers card has nothing to show
- you enabled Redis ingest but forgot `pulse:work`
- Livewire assets or requests are failing

If the page loads but stays blank, open your browser devtools and check whether Livewire is erroring. A common Laravel setup issue is the `/livewire/livewire.js` asset returning 404, which I covered here: [Fix the /livewire/livewire.js 404 not found error](/livewire-js-404-not-found)

### Should you use a separate database for Pulse?

For smaller apps, I would usually keep Pulse in the main database and revisit later.

For busier apps, or if you want cleaner operational separation, a dedicated Pulse connection is a good idea. The docs support that through `PULSE_DB_CONNECTION`, and it keeps Pulse's write volume away from your main app tables.

## Is Laravel Pulse worth it?

Yes, if you want a Laravel-native monitoring dashboard that gets you from “something feels off” to “here is the slow route / noisy user / backed-up queue” quickly.

That is the sweet spot:

- lighter than a full external observability stack
- more operationally useful than no dashboard at all
- easier to extend than most teams expect on day one

If you want Pulse to become part of how you actually run the app, these are the next reads I would open:

- [Steal ideas for Laravel Pulse cards worth building](/best-laravel-pulse-custom-cards)
- [Build your own Laravel Pulse card once the built-ins are not enough](/custom-laravel-pulse-card)
- [Use Laravel Debugbar when you need request-by-request inspection](/laravel-debugbar)
- [Fix the Livewire JS 404 before it blocks the whole page](/livewire-js-404-not-found)
- [See how Laravel maintenance mode fits the same operational toolkit](/laravel-maintenance-mode)
