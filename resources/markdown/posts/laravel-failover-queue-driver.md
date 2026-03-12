---
id: "01KKEW27C71MD14JW9CQ0BPPP9"
title: "The Laravel failover queue driver: stop losing jobs"
slug: "laravel-failover-queue-driver"
author: "benjamincrozat"
description: "Learn how to set up the Laravel failover queue driver so you don’t lose jobs when Redis, SQS, or another queue goes down. I walk you through a copy-paste setup, simple tests, and small gotchas."
categories:
  - "laravel"
published_at: 2025-10-16T13:37:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K7NMB5JN0PFM0W6V09A560ZM.png"
sponsored_at: null
---
## What is the Laravel failover queue driver?

It’s a queue driver named [failover](https://laravel.com/docs/12.x/queues#queue-failover) and [available starting in Laravel 12.34](https://github.com/laravel/framework/releases/tag/v12.34.0). First, list your queue connections in order in *config/queues.php*. Then, when you **push** a job, Laravel tries the first one. If it fails, it automatically tries the next one and so on.

**Benefits**:
- It's effortless.
- You only have your config to change.
- You have less chances of losing jobs to dead connections.

## Quick start (copy-paste)

### 1. Set your default queue to `failover`

In `.env`:

```env
QUEUE_CONNECTION=failover
```

### 2. Add a `failover` connection

In `config/queue.php`:

```php
'connections' => [
    // Normal connections…
    'database' => [
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
        'after_commit' => false,
    ],

    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
        'block_for' => null,
        'after_commit' => false,
    ],

    // NEW: the Laravel failover queue driver.
    'failover' => [
        'driver' => 'failover',
        'connections' => [
            // Try Redis first. If it fails, fall back to database.
            'redis',
            'database',
        ],
    ],
],
```

Now dispatch jobs like you always do:

```php
SendWelcomeEmail::dispatch($user->id);
```

If Redis is down, the job is pushed to the **database** queue instead.

## How to run workers and Horizon (don’t point them at `failover`)

The failover driver falls back **when pushing** jobs. When **consuming** jobs, the `failover` connection only reads from the **first** listed connection (for example, `redis`). So if a push fell back to `database`, a worker pointed at `failover` won’t see those jobs.

Run workers for each connection you might use:

```bash
# Keep your fast path.
php artisan queue:work redis --queue=default

# Also run a worker for the fallback path.
php artisan queue:work database --queue=default
```

**Horizon example (`config/horizon.php`)**

```php
'supervisors' => [
    'redis' => [
        'connection' => 'redis',
        'queue' => ['default'],
        'balance' => 'simple',
        'maxProcesses' => 5,
    ],
    'database' => [
        'connection' => 'database',
        'queue' => ['default'],
        'balance' => 'simple',
        'maxProcesses' => 2,
    ],
],
```

This way, if pushes fall back to `database`, you still have a worker ready to process those jobs.

## Three practical patterns (pick one)

### A. Speed first, safety second (Redis → database)

Good for most apps.

```php
'connections' => [
    'failover' => [
        'driver' => 'failover',
        'connections' => ['redis', 'database'],
    ],
],
```

### B. Small tools and admin panels (Redis → sync)

If anything breaks, just run the job now so users aren’t blocked.

```php
'connections' => [
    'failover' => [
        'driver' => 'failover',
        'connections' => ['redis', 'sync'],
    ],
],
```

### C. Bigger apps (Redis → SQS → database)

Keep performance and add cloud durability.

```php
'connections' => [
    'failover' => [
        'driver' => 'failover',
        'connections' => ['redis', 'sqs', 'database'],
    ],
],
```

## How you can test the failover driver

1. Start your worker(s) (Horizon or `php artisan queue:work` for each connection).
2. **Stop Redis** to simulate a failure:
   - Docker: `docker stop redis`
   - Homebrew: `brew services stop redis`
3. Dispatch a job.
4. Check your logs and, if you use database fallback, check the `jobs` table:

```sql
SELECT id, queue, attempts, available_at
FROM jobs
ORDER BY id DESC
LIMIT 10;
```

5. Start Redis again when you’re done.

You’ll see jobs pushed to the next connection when Redis is down.

## Get a heads-up when failover happens (simple event listener)

Laravel fires a `QueueFailedOver` event when it switches connections. You can log it (or ping Slack).

**App\Providers\EventServiceProvider.php**

```php
protected $listen = [
    \Illuminate\Queue\Events\QueueFailedOver::class => [
        \App\Listeners\NotifyOnQueueFailover::class,
    ],
];
```

**App\Listeners\NotifyOnQueueFailover.php**

```php
namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\Events\QueueFailedOver;

class NotifyOnQueueFailover
{
    public function handle(QueueFailedOver $event): void
    {
        Log::critical('Queue failover occurred', [
            'failed_connection' => $event->connectionName,
            'job' => is_object($event->command) ? get_class($event->command) : $event->command,
        ]);

        // Or send to Slack / email from here.
    }
}
```

Now you’ll know right away when the Laravel failover queue driver had to save you.

## Gotchas I noted while testing

* **Failover is for pushing.** It doesn’t merge or drain multiple connections later. Workers won’t “fall through” to read another connection.
* If **all** listed connections fail at push-time, Laravel throws an exception and the job is **not** queued.
* `pushRaw()` won’t fire the `QueueFailedOver` event; only `push()` and `later()` do.
* Make jobs [**idempotent**](https://www.google.com/search?rls=en&q=definition+of+idempotent&ie=UTF-8&oe=UTF-8) (safe to run twice with the same result) in case a flaky connection causes retries.
* If you fall back to `database` or `sqs`, make sure your jobs serialize cleanly.

## FAQ

### Does this work with Horizon?

Yes. But don’t point Horizon at `failover`. Run a supervisor for each connection you list (for example, one for `redis`, one for `database`).

### Can I use any connection type?

Yes. `redis`, `sqs`, `database`, `beanstalkd`, or even `sync`.

## Final thought

I like features that are “set it and forget it.” The Laravel failover queue driver is exactly that. You set `QUEUE_CONNECTION=failover`, list your backups in `config/queue.php`, and your app keeps moving when one service goes down. That’s less stress for you, and a smoother experience for your users.

If "The Laravel failover queue driver: stop losing jobs" was useful, these related posts make strong next steps:

- [Laravel Pulse: monitor your apps for free](/laravel-pulse)
- [19 Laravel security best practices for 2025](/laravel-security-best-practices)
- [The history of Laravel's versions (2011-2025)](/laravel-versions)
- [Laravel 10 is out! Here are every new features and changes.](/laravel-10)
- [Laravel firstOrCreate, firstOrNew, createOrFirst and friends](/laravel-firstorcreate-firstornew-createorfirst-updateorcreate-updateorinsert)
- [Laravel 11 is out! Here are every new big changes and features.](/laravel-11)
- [Laravel Dropbox Driver package: how to install and use it](/laravel-dropbox-driver)
- [9 testing best practices for Laravel in 2025](/laravel-testing-best-practices)
- [9 ways to clear Laravel's cache](/laravel-clear-cache)
- [Laravel 9: the mindful upgrade guide](/laravel-9-upgrade-guide)

