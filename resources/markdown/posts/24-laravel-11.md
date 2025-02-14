---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/7960a1ab-1e0c-4f64-96de-810bbcde5f07
Title: "Laravel 11 is out! Here are every new big changes and features."
Description: "Laravel 11 was released on March 12th, 2024 and is packed with tons of new bits. Let's dive into every relevant new change and feature you need to know about."
Published at: 2023-01-05
Modified at: 2024-03-12
Categories: laravel
---

## When was Laravel 11 released?

**Laravel 11 was released on March 12, 2024**.

The adoption of a new major version should never be rushed, though.

The framework last had LTS (Long-Term Support) in version 6, but **each major version has two years of updates**, which should give you enough time to get your codebase in check and upgrade it.

According to the [Support Policy](https://laravel.com/docs/10.x/releases#support-policy), Laravel 10 will receive bug fixes until August 6th, 2024 and security fixes until February 4th, 2025.

| Version | PHP | Release | Bug fixes until | Security fixes until |
| ------- | --- | ------- | --------------- | -------------------- |
| 10 | 8.1 | February 14, 2023 | August 6, 2024 | February 4, 2025 |
| 11 | 8.2 | March 12, 2024 | September 3, 2025 | March 12, 2026 |

## Upgrade a project to Laravel 11 or create a new one

To upgrade a project to Laravel 11, follow my simple to understand [upgrade guide](/laravel-11-upgrade-guide) that contains tons of tips.

And to create a new Laravel 11 project, [get the official installer](https://laravel.com/docs/11.x/installation#creating-a-laravel-project) and run the following command:

```bash
laravel new hello-world
```

Or, if you prefer to use Composer explicitly:

```bash
composer create-project laravel/laravel hello-world
```

## What's new in Laravel 11: features and changes

### A slimmer application skeleton

With the introduction of Laravel 11, it was time to declutter and redefine how a Laravel application is structured. The goal? To give you a leaner and more efficient starting point for your projects. And let me tell you, they've delivered.

#### The all-new bootstrap/app.php file

The heart of this makeover is the `bootstrap/app.php` file, revitalized to act as your central command station. It's here you'll tweak application routing, middleware, service providers, exception handling, and a bunch more – all from one spot. Think of it as the captain's chair of a spaceship.

#### Simplified service providers

Are you used to juggling multiple service providers? Laravel 11 says, "No more!" Now, there's just one `AppServiceProvider`. This change consolidates the functions of the old service providers into either the `bootstrap/app.php` or the `AppServiceProvider`, making your codebase tidier.

#### Optional API and broadcast route files

Not every app needs API and broadcasting capabilities out of the gate. Laravel 11 acknowledges this by not including *api.php* and *channels.php* route files by default. Need them? Just a simple [Artisan command away](/install-route-files-laravel). Laravel's flexibility shines here, allowing you to add features only when you need them.

```bash
php artisan install:api
php artisan install:broadcasting
```

#### Gone are the default middleware classes

Gone are the days of a cluttered middleware folder. Laravel 11 has moved these middleware into the framework itself, letting you enjoy a cleaner application structure without losing the ability to customize middleware behavior from `bootstrap/app.php`. Learn more: (How to customize default middleware in Laravel 11)[/customize-middleware-laravel-11]

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo('/admin/login');
})
```

#### Exception handling also moved to bootstrap/app.php

In the spirit of consolidation, exception handling has also moved to the cozy confines of `bootstrap/app.php`. This keeps your application's structure lean and means you won't have to hunt through multiple files to manage exceptions.

Here's a code sample from Laravel 11's release notes (*bootstrap/app.php*):

```php
->withExceptions(function (Exceptions $exceptions) {
    $exceptions->dontReport(MissedFlightException::class);
 
    $exceptions->reportable(function (InvalidOrderException $e) {
        // ...
    });
})
```

#### Direct tasks scheduling in routes/console.php

Scheduling tasks is now as simple as adding a few lines to your `routes/console.php` file, thanks to the new `Schedule` facade. No need for a console kernel anymore.

In *routes/console.php*:

```php
use Illuminate\Support\Facades\Schedule;
 
Schedule::command('some-service:sync')->daily();
```

#### A minimalist base controller class

The base controller class in Laravel 11 has gone on a diet. The `AuthorizesRequests` and `ValidatesRequests` traits still exist, but you will also now have to opt-in.

For instance, if you are using policies and want to do check for authorizations, you'll have to include the `AuthorizesRequests` trait in your base controller (*app/Http/Controllers/Controller.php*):

```php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller
{
    use AuthorizesRequests;
}
```

### The new health-check endpoint

Laravel 11 introduces a new [health-check endpoint](https://laravel.com/docs/11.x/deployment#the-health-route) that allows you to perform various verifications of the different parts of your application and ensure everything is running smoothly.

The endpoint can be defined in *bootstrap/app.php* like so:

```php
->withRouting(
    web: __DIR__.'/../routes/web.php',
    commands: __DIR__.'/../routes/console.php',
    health: '/up',
)
```

Whenever the endpoint is hit, a `Illuminate\Foundation\Events\DiagnosingHealth` event is dispatched.

### Per-second rate limiting

[Rate limiting](https://laravel.com/docs/11.x/routing#rate-limiting) in Laravel is incredibly easy to set up. With Laravel 11, you can now set a rate limit per second.

But why is it useful? Let's take this example:

```php
RateLimiter::for('invoices', function (Request $request) {
    {-return Limit::perMinute(120);-}
    {+return Limit::perSecond(2);+}
});
```

1. If we limit the amount of requests per minute, it means that **your users will also be able to send 120 requests in a second**.
2. But if we limit the amount of requests per second, **your users won't be able to cram 120 requests in a second**, while still being limited to 120 requests per minute.

See now why it's a great change?

### Discover the new Model::casts() method

Usually, in Laravel, you declare attribute casting in an Eloquent model like this:

```php
class User extends Model
{
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
```

With Laravel 11, you can now define your casting through a `casts()` method in your model, giving you a chance to use static methods with parameters. This is how it looks:

```php
class User extends Model
{
    protected function casts() : array
    {
        return [
            'foo' => AsCollection::using(FooCollection::class),
        ];
    }
}
```

Moreover, you can now also specify your casts as an array:

```php
class User extends Model
{
	// Even in the old $casts property!
    protected $casts = [
        'foo' => [AsCollection::class, FooCollection::class],
    ];
  
    protected function casts() : array
    {
        return [
            'foo' => [AsCollection::class, FooCollection::class],
        ];
    }
}
```

**Note that the `casts()` method is prioritized over the `$casts` property.**

All these changes are non-breaking, meaning they won't affect your current code if you update to Laravel 11.

See the pull request on GitHub: [[11.x] Adds Model::casts() method and named static methods for built-in casters](https://github.com/laravel/framework/pull/47237)

### There's a new handy trait named "Dumpable"

This pull request introduces a new `Dumpable` trait in Laravel 11, intended to replace the current `dd` and `dump` methods in most of the framework's classes.

The trait allows Laravel users and package authors to include debugging methods easily within their classes by utilizing this trait.

Here's a code example showing how it can be used:

```php
<?php

namespace App\ValueObjects;

use Illuminate\Support\Traits\Dumpable;
use Illuminate\Support\Traits\Conditionable;

class Address
{
    use Conditionable, Dumpable;

    // ...
}

$address = new Address;

// Before: 
$address->foo()->bar();

// After:
$address->foo()->dd()->bar();
```

See the pull request on GitHub: [[11.x] Adds Dumpable concern](https://github.com/laravel/framework/pull/47122)

### Dropped support for PHP 8.1

PHP 8.2 is established and PHP 8.3 is now the latest version of PHP. Laravel can now move forward and abandon 8.1.

But remember: your Laravel apps don't need to be updated to the latest and greatest as soon as they're released.

Especially if you have projects with paid clients or employees who depend on them to do their work.

Those projects need to slowly but surely move forward by doing extensive testing. Don't rush.

See the pull request on GitHub: [[11.x] Drop PHP 8.1 support](https://github.com/laravel/framework/pull/45526)

![PHP 8.2](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/127/conversions/www.php.net_releases_8.2_en.php_jqtup2-medium.jpg)

### Laravel 11 release preparation pull requests

Here's a list of every merged PR I found to prepare the release of Laravel 11:

- [[11.x] Set up Laravel v11](https://github.com/laravel/framework/pull/45525)
- [[11.x] Update Testbench dependencies for Laravel 11](https://github.com/laravel/framework/pull/45538)
- [[11.x] Removed old WithoutEvents trait](https://github.com/laravel/framework/pull/47180)
- [And more!](https://github.com/laravel/framework/pulls?q=is%3Apr+is%3Amerged+%5B11.x%5D+)

There are a lot of small details to dig into that I didn't include in this article for the sake of conciseness.

## How to contribute your own features and bug fixes to this release

Did you know you can fix the bugs you have encountered or create the next big feature for Laravel 11?

1. See what's going on for Laravel 11 on GitHub: https://github.com/laravel/framework/pulls. The Pull Requests will tell you what's already been done.
2. Take one of your pain points with the framework and create a solution yourself.
3. Send the PR over to the laravel/framework repository, collect feedback, improve, and get merged.

One important tip to increase your chances of being merged: add something to the framework that's a win for developers but not a pain to maintain for Taylor and his team in the long run.

![Pull requests on the laravel/framework repository.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/128/conversions/Screenshot_2023-01-05_at_18.03.30_qx8sjy-medium.jpg)
