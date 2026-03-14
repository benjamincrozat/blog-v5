---
id: "01KKEW27CTWXZ230CF1KRMBQ8Z"
title: "Laravel maintenance mode: down, up, secret, and render"
slug: "laravel-maintenance-mode"
author: "benjamincrozat"
description: "Use Laravel maintenance mode with php artisan down and php artisan up, then add refresh, secret, render, or redirect options when deployments need more control."
categories:
  - "laravel"
published_at: 2023-12-07T00:00:00+01:00
modified_at: 2026-03-14T10:17:05Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/nd6cIH8gRhRuIh8.png"
sponsored_at: null
---
## Enabling Laravel's maintenance mode

Use Laravel maintenance mode when you need to pause requests during deployment, maintenance, or risky server changes. The basic command is simple:

```bash
php artisan down
```

What if you want to give users a heads up that the site will be back shortly? Just add a refresh option:

```bash
php artisan down --refresh=15
```
 
This will tell the user's browser to reload the page after 15 seconds.

![Laravel's maintenance mode in action.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-maintenance-mode-f0246133c24c421b46f5.webp/public)

## Sneaking past maintenance mode

Now, here's the useful trick. You can bypass maintenance mode with a secret token. Create a token using:

```bash
php artisan down --secret="your-secret-token"
```

Visit your app’s URL with the token appended (http://example.test/WeHrMT6odmCLXWkE for example), and you’ll get a bypass cookie. 

And if you prefer Laravel to create a token for you, recent Laravel versions let you do this:

```bash
php artisan down --with-secret
```

Just remember, keep that secret simple and URL-friendly.

## Pre-rendering Laravel's maintenance view

Want to avoid errors when users hit your site mid-update? You can pre-render a maintenance view that shows up instantly:

```bash
php artisan down --render="errors::503"
```

This is served up before Laravel fully boots, so it's quick to the draw.

(The 503 HTTP code means "Service Unavailable," hence the need to render this error page.)

## Redirects during maintenance

Maybe you'd rather redirect users elsewhere while you tidy up. No problem:

```bash
php artisan down --redirect=/
```

This steers visitors to wherever you specify.

## Disabling maintenance mode

All done? Great, let’s bring your app back with:

```bash
php artisan up
```

And just like that, you’re live again!

## Customize Laravel's maintenance page

You’re the boss when it comes to how your maintenance page looks. Set up your own template at `resources/views/errors/503.blade.php` and make it your own.

## What about queued jobs during maintenance mode?

Worry not; queued jobs are put on pause in maintenance mode. They'll pick up right where they left off once you're back in action.

## Why use the maintenance mode in the real-world?

Laravel's maintenance mode can be extremely useful when, for instance, deploying applications in production.

Here's a simplified version of the deploy script of this blog before I switched to zero downtime deployments:

```bash
cd /path/to/project

# Put the blog down and show a pre-rendered page for a 503 response.
php artisan down --render="errors::503"

git pull origin main

composer install --no-interaction --no-suggest --prefer-dist --optimize-autoloader

php artisan migrate --force
php artisan config:cache
# …

npm i
npm run dev

# Deployment is finished, let's put the blog back up.
php artisan up
```

As you can see, to avoid people sumbling upon various errors while the code changes, `composer install` runs, or the database is updated, I put the blog down and show a custom 503 (Service Unavailable) page.

Now, since I'm using [Ploi](/recommends/ploi) to handle my deployments with zero downtime, this trick isn't needed anymore. But for those running in legacy environments, I think you'll find it handy.

If you are thinking about safer deploys instead of just flipping maintenance mode on and off, these next reads are worth keeping open:

- [Use the Artisan commands you run every day with more confidence](/laravel-artisan)
- [See whether Laravel Forge still fits the way you deploy](/laravel-forge)
- [See how to deploy a PHP or Laravel app on Sevalla step by step](/deploy-php-laravel-apps-sevalla)
- [See what Laravel Pulse can surface before users do](/laravel-pulse)
- [Pick up Laravel habits that keep projects easier to maintain](/laravel-best-practices)
