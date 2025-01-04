---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/33/6678_pnn8xm.png
Title: "Laravel retrospective: what changed since version 5.8?"
Description: "Dive into the history of Laravel. If you went away for some time, this is the right place to resume your journey."
Published at: 2023-05-08
Modified at: 2023-08-06
Categories: laravel
---

## Introduction

You may belong to a group of people who left Laravel a few years ago and would like a quick summary of everything that changed now that you are coming back.

Well, this is your lucky day. Instead of letting you pour hours into reading every upgrade guide, I leveraged the power of AI to compile a (non-exhaustive) list that will give you a great overview.

## Changes in the directory structure

### Moved directories

1. In Laravel 9, the *resources/lang* directory has been moved to the root project directory.

### New directories

1. *factories*: in Laravel 8, model factories were rewritten to support classes, and they are stored in the `database/factories` directory.
2. *seeders*: still in Laravel 8 , seeder classes are now stored in the `database/seeders` directory.

## Removed or changed functionalities

1. Authentication scaffolding moved to [laravel/ui](https://github.com/laravel/ui) repository.
2. The `str_` and `array_` helpers were removed from the framework in Laravel 6.0 and moved to the [laravel/helpers package](https://github.com/laravel/helpers).
3. The `Input` facade was removed in Laravel 6.0, and developers are encouraged to use the `Request` facade instead.
4. The `mandrill` and `sparkpost` mail drivers were removed in Laravel 6.0.
5. The `rackspace` storage driver was removed in Laravel 6.0.
6. The undocumented `addHidden` and `addVisible` methods were removed from Eloquent in Laravel 7.0.
7. The undocumented `promotion` Markdown mail component was removed in Laravel 7.0.
8. Support for duplicate route names was removed in Laravel 7.0.
9. Factory "types" feature was removed from Eloquent in Laravel 7.0.
10. The `retryAfter` method and property were renamed to `backoff` in Laravel 8.0.
11. The `timeoutAt` property was renamed to `retryUntil` in Laravel 8.0.
12. The `allOnQueue()` and `allOnConnection()` methods were removed when using job chaining in Laravel 8.0.
13. The default Markdown mail templates were changed in Laravel 7.0 and 8.0, and the old templates were removed.
14. The `password` validation rule was renamed to `current_password` in Laravel 9.0.
15. The Eloquent model's `$dates` property was deprecated in Laravel 10.0, and developers are encouraged to use the `$casts` property instead.
16. The `Redirect::home` method was deprecated in Laravel 10.0, and developers should use `Redirect::route('home')` instead.
17. The deprecated `Bus::dispatchNow` and `dispatch_now` methods were replaced with `Bus::dispatchSync` and `dispatch_sync` methods, respectively, in Laravel 10.0.
18. The deprecated `MocksApplicationServices` trait was removed from tests in Laravel 10.0.

## More detailed changes since Laravel 5.8 for each major version

### Changes in Laravel 6.0 from 5.8

- PHP 7.2 or greater is now required.
- Carbon 1.x is no longer supported. Upgrade to Carbon 2.0.
- `str_` and `array_` helpers have been moved to the `laravel/helpers` package and removed from the framework.
- Authorization policies should now define a `viewAny` method, which will be called when a user accesses the controller's `index` method.
- The default Redis client has changed from `predis` to `phpredis`. To keep using `predis`, set the `redis.client` configuration option to `predis`.
- The `BelongsTo::update` method now functions as an ad-hoc update query, meaning it does not provide mass assignment protection or fire Eloquent events.
- The Eloquent model's `toArray` method will now cast any attributes that implement `Illuminate\Contracts\Support\Arrayable` to an array.
- The route path for verifying emails has changed from `/email/verify/{id}` to `/email/verify/{id}/{hash}`.
- The `Input` facade has been removed. Use the `Request` facade instead.
- The `between` method in the scheduler has updated behavior.
- The `mandrill` and `sparkpost` mail drivers have been removed.
- The `rackspace` storage driver has been removed.
- Passing associative array parameters to the `route` helper or `URL::route` method will now attach these values to the query string instead of using them as URI values when generating URLs for routes.

### Changes in Laravel 7.0 from 6.x

- Symfony 5 and PHP 7.2.5 are now required.
- Updated dependencies in `composer.json` file.
- Authentication scaffolding moved to `laravel/ui` repository.
- Added `recentlyCreatedToken` method to `TokenRepositoryInterface`.
- Renamed `Blade::component` method to `Blade::aliasComponent`.
- Introduced first-party support for Blade "tag components".
- Removed undocumented `addHidden` and `addVisible` methods from Eloquent.
- Added `booting` and `booted` methods to Eloquent.
- Changed date serialization format for `toArray` and `toJson` methods on Eloquent models.
- Removed "factory types" feature from Eloquent.
- Updated `getOriginal` method to respect casts and mutators.
- Removed Zend Diactoros library for generating PSR-7 responses and replaced it with `nyholm/psr7`.
- Changed default Markdown mail templates and removed the undocumented `promotion` Markdown mail component.
- Removed support for duplicate route names.
- Integrated Cross-Origin Resource Sharing (CORS) support by default.
- Made `array` session driver data persistent for the current request.
- Automatically escaped values for `assertSee`, `assertDontSee`, `assertSeeText`, `assertDontSeeText`, `assertSeeInOrder`, and `assertSeeTextInOrder` assertions on the `TestResponse` class.
- Renamed `Illuminate\Foundation\Testing\TestResponse` class to `Illuminate\Testing\TestResponse`.
- Renamed `Illuminate\Foundation\Testing\Assert` class to `Illuminate\Testing\Assert`.
- Updated the `different` validation rule to fail if one of the specified parameters is missing from the request.

### Changes in Laravel 8.0 from 7.x

- Minimum PHP version is now 7.3.0.
- Model factories have been rewritten to support classes.
- Seeder and factory namespaces have been added.
- The `retryAfter` method and property have been renamed to `backoff`.
- The `timeoutAt` property has been renamed to `retryUntil`.
- The `allOnQueue()` and `allOnConnection()` methods have been removed when using job chaining.
- Paginator now uses the Tailwind CSS framework for default styling.
- Automatic controller namespace prefixing is now set to `null` by default in RouteServiceProvider.
- The `decodeResponseJson` method in the TestResponse class no longer accepts arguments.
- The `assertExactJson` method now requires numeric keys of compared arrays to match and be in the same order. Use `assertSimilarJson` for comparing without requiring order.

### Changes in Laravel 9.0 from 8.x

- PHP 8.0.2 is now required.
- Updated dependencies in `composer.json`, including `laravel/framework`, `nunomaduro/collision`, and replacing `facade/ignition` with `spatie/laravel-ignition`.
- Migrated from Flysystem 1.x to 3.x, which may require some application changes.
- Replaced SwiftMailer with Symfony Mailer, resulting in several changes related to sending emails.
- Laravel's dependency on `opis/closure` has been replaced by `laravel/serializable-closure`.
- The `password` validation rule has been renamed to `current_password`.
- Unvalidated array keys are now excluded from validated data by default.
- The *resources/lang* directory is now located in the root project directory.
- The `FILESYSTEM_DRIVER` environment variable has been renamed to `FILESYSTEM_DISK`.

### Changes in Laravel 10.0 from 9.x

- Laravel now requires PHP 8.1.0 or greater and Composer 2.2.0 or greater.
- Update several dependencies in `composer.json` file for Laravel 10 support.
- Remove or update the `minimum-stability` setting in your application's `composer.json` file.
- Use `app()->usePublicPath(__DIR__.'/public')` instead of binding `path.public` into the container for customizing "public path".
- Remove the call to the `registerPolicies` method from the `boot` method of your application's `AuthServiceProvider`.
- Schedule the `cache:prune-stale-tags` Artisan command for Redis cache tags support.
- Update how to retrieve the grammar's raw string value for database expressions by using the `getValue` method instead of casting to a string.
- Pass a string connection name as the first argument to the `Illuminate\Database\QueryException` constructor.
- Remove the use of the deprecated Eloquent model's `$dates` property and use the `$casts` property instead.
- Rename the `getBaseQuery` method on the `Illuminate\Database\Eloquent\Relations\Relation` class to `toBase`.
- Use the `lang:publish` Artisan command to publish the language directory in new Laravel applications.
- Update your application to work with Monolog 3.x by reviewing Monolog's upgrade guide and updating any third-party packages.
- Replace the deprecated `Bus::dispatchNow` and `dispatch_now` methods with `Bus::dispatchSync` and `dispatch_sync` methods, respectively.
- Optionally, rename the `$routeMiddleware` property of the `App\Http\Kernel` class to `$middlewareAliases`.
- Update the return values of the `RateLimiter::attempt` method.
- Remove the deprecated `Redirect::home` method and use `Redirect::route('home')` instead.
- Remove the deprecated `MocksApplicationServices` trait from your tests and use fakes instead, such as `Event::fake`, `Bus::fake`, and `Notification::fake`.
- Update closure based custom validation rules to handle the new behavior of the `$fail` callback.

## Official packages added since Laravel 5.8

The following packages have been added since Laravel 5.8:
- [Breeze](https://laravel.com/docs/starter-kits#laravel-breeze)
- [Cashier (Paddle)](https://laravel.com/docs/billing#paddle-payment-gateway)
- [Folio](https://github.com/laravel/folio)
- [Fortify](https://laravel.com/docs/fortify)
- [Homestead](https://laravel.com/docs/homestead)
- [Jetstream](https://jetstream.laravel.com)
- [Mix](https://laravel.com/docs/mix)
- [Octane](https://laravel.com/docs/octane)
- [Pennant](https://laravel.com/docs/pennant)
- [Pint](https://laravel.com/docs/pint)
- [Prompts](https://laravel.com/docs/prompts)
- [Sail](https://laravel.com/docs/sail)
- [Sanctum](https://laravel.com/docs/sanctum)
- [Valet](https://laravel.com/docs/valet)

