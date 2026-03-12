---
id: "01KKEW27AZMFC1T80AF1TRRGXA"
title: "Laravel 10 is out! Here are every new features and changes."
slug: "laravel-10"
author: "benjamincrozat"
description: "Laravel 10 was released on February 14, 2023. I break down its new features, upgrade steps, and support timelines."
categories:
  - "laravel"
published_at: 2022-09-15T00:00:00+02:00
modified_at: 2025-10-04T21:33:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/fBo7M3NZnT8zspS.png"
sponsored_at: null
---
## Introduction

Laravel 9 is retiring. The framework has a new 10th version, and I walk you through what’s new.

## Laravel 10 release date

**Laravel 10 was released on February 14, 2023.**

But take it slow. It doesn’t mean you have to update all your projects immediately.

Laravel 9 will receive bug fixes until August 8, 2023 and security fixes until February 6, 2024.

| Version | PHP | Release | Bug fixes until | Security fixes until |
| ------- | --- | ------------ | --------------- | -------------------- |
| 9 | 8.0–8.2 | February 8, 2022 | August 8, 2023 | February 6, 2024 |
| 10 | Minimum PHP: 8.1+ | February 14, 2023 | August 6, 2024 | February 4, 2025 |

## Is Laravel 10 LTS (long term support)?

**No, Laravel 10 isn’t LTS, but it provides two years of support.**

The framework last had LTS in version 6 and you can [learn all about LTS versions here](/laravel-versions).

Like I said, each major version offers two years of bug and security fixes, which is plenty of time to prepare your application to [upgrade to the next major version](https://benjamincrozat.com/laravel-10-upgrade-guide).

## How to install Laravel 10?

Using the official [Laravel installer](https://laravel.com/docs/10.x/installation#your-first-laravel-project):

```bash
laravel new hello-world
```

Or, if you prefer to use Composer explicitly:

```bash
composer create-project --prefer-dist laravel/laravel hello-world
```

Note: Laravel 10 requires PHP 8.1+.

## How to upgrade to Laravel v10?

Upgrading to Laravel 10 requires more than just following upgrade instructions. Before proceeding, think this through.

Check out [my guide to upgrading to Laravel 10](https://benjamincrozat.com/laravel-10-upgrade-guide) if you need more clarification about the process and considerations you should have before giving the green light. I also talk about a practical way to automate parts of the process, which is helpful for agencies and larger teams.

## What’s new in Laravel 10: features and changes

### [Feature flags with Laravel Pennant](https://laravel.com/docs/10.x/pennant)

<img src="https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/74/conversions/FonDXMhXgAAY16E_nr7fzx-medium.jpg" alt="Laravel Pennant feature flags diagram" loading="lazy" />

**[Laravel Pennant](https://laravel.com/docs/10.x/pennant) is a first-party package that adds feature flags to any Laravel 10 project.**

```bash
composer require laravel/pennant
```

Feature flags are a way to enable or disable features at runtime without changing your code.

For instance, you can deploy a feature only for a select set of users in your production environment. This is great for A/B testing.

```php
use Laravel\Pennant\Feature;
use Illuminate\Support\Lottery;
 
Feature::define('new-onboarding-flow', function () {
    return Lottery::odds(1, 10);
});
```

Check if the user has access to the feature:

```php
if (Feature::active('new-onboarding-flow')) {
    // ...
}
```

There’s even a Blade directive:

```blade
@feature('new-onboarding-flow')
    ...
@endfeature
```

Learn more about Laravel Pennant on the [official documentation](https://laravel.com/docs/10.x/pennant). Laravel News also has a [step-by-step tutorial](https://laravel-news.com/laravel-pennant).

### [New Process facade](https://laravel.com/docs/10.x/processes)

Laravel 10 introduced a simple yet comprehensive API for the Symfony Process component, enabling you to run external processes within your Laravel application easily.

This is how you use it:

```php
use Illuminate\Support\Facades\Process;
 
$result = Process::run('ls -la');
 
return $result->output();
```

You can even run processes concurrently:

```php
use Illuminate\Process\Pool;
use Illuminate\Support\Facades\Process;
 
[$first, $second, $third] = Process::concurrently(function (Pool $pool) {
    $pool->command('cat first.txt');
    $pool->command('cat second.txt');
    $pool->command('cat third.txt');
});
 
return $first->output();
```

There’s more to learn about processes in the [official documentation](https://laravel.com/docs/10.x/processes). See the pull request on GitHub: [Process DX Layer PR (#45314)](https://github.com/laravel/framework/pull/45314).

### [Test profiling (--profile)](https://laravel.com/docs/10.x/testing#profiling-tests)

The Artisan command `php artisan test` can receive a `--profile` option that shows the 10 slowest tests, so you can spot bottlenecks quickly. Parallel testing pairs well with this (`php artisan test --parallel`).

If your project upgrades to PHPUnit 10, make sure you are on `nunomaduro/collision` ^7.0.

<img src="https://user-images.githubusercontent.com/5457236/217328439-d8d983ec-d0fc-4cde-93d9-ae5bccf5df14.png" alt="Screenshot of Laravel test profiling --profile output" loading="lazy" />

### [Validation rules are invokable by default](https://laravel.com/docs/10.x/validation#custom-validation-rules)

In Laravel 9, invokable validation rules could be generated using the `--invokable` flag with `php artisan make:rule`. Starting with Laravel 10, new rules implement `Illuminate\Contracts\Validation\ValidationRule` and define a `validate` method.

```bash
php artisan make:rule Uppercase
```

```php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Uppercase implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strtoupper((string) $value) !== (string) $value) {
            $fail('The :attribute must be uppercase.');
        }
    }
}
```

This boilerplate is small and easy to understand. See the `ValidationRule` interface in the [API docs](https://api.laravel.com/docs/10.x/Illuminate/Contracts/Validation/ValidationRule.html).

### Native type declarations in the skeleton

Starting with Laravel 10, the skeleton uses native types instead of docblocks.

For instance, in the Laravel skeleton, the `schedule()` method in app/Console/Kernel.php looks like this:

```diff
/**
 * Define the application's command schedule.
- * 
- * @param  Illuminate\Console\Scheduling\Schedule  $schedule 
- * @return void 
 */
- protected function schedule($schedule)
+ protected function schedule(Schedule $schedule): void
```

The team also added generic type annotations, which improves autocompletion even further (given your code editor supports generics). See the pull request on GitHub: [PHP native type declarations PR (#6010)](https://github.com/laravel/laravel/pull/6010).

### First-party packages also use native types

Official packages for Laravel won’t be left out of this transition. Native type hints will be used across the Laravel organization. You can check out [this PR](https://github.com/laravel/jetstream/pull/1175), which starts the switch from docblocks to native type hints in [Laravel Jetstream](https://jetstream.laravel.com).

### Config path customization

A contributor added the possibility to set a custom path for config files. This is useful for projects slowly migrating to Laravel that can’t handle a big directory structure change.

In your bootstrap/app.php, use the `useConfigPath()` method on the `$app` object:

```php
$app->useConfigPath(__DIR__ . '/../some/path');
```

(And did you also know about `bootstrapPath()`, `databasePath()`, `langPath()`, etc.? Laravel is highly customizable.) Learn more: [Config path customization PR (#46053)](https://github.com/laravel/framework/pull/46053).

### [Schema native operations in migrations](https://api.laravel.com/docs/10.x/Illuminate/Database/Schema/Builder.html)

Column modifications can now use native database operations in most drivers, so you don’t need `doctrine/dbal` for common changes.

```php
// ...

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('foo', function (Blueprint $table) {
            $table->unsignedBigInteger('bar')->change();
        });
    }
  
    // ...
};
```

Drivers vary, and SQLite is a notable exception. If your project still has Doctrine DBAL installed (for example, to support multiple connections), you can ask Laravel to use native operations when possible and fall back to DBAL only when needed:

```php
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Schema::useNativeSchemaOperationsIfPossible();
    }
}
```

Tip: when changing column types, re-apply attributes like `unsigned` or `default` if your driver drops them during the change.

### [Composer 2.2 or newer requirement](https://laravel.com/docs/10.x/upgrade)

To ensure solid foundations for every new Laravel 10 project, the framework requires Composer 2.2 or newer.

### [Dropped support for PHP 8.0](https://laravel.com/docs/10.x/upgrade)

Laravel 10 drops support for PHP 8.0 and requires PHP 8.1 at minimum. If you want to upgrade, move to PHP 8.1 or 8.2. Don’t rush—plan and test carefully.

<img src="https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/75/conversions/Screenshot_2022-12-16_at_17.32.08_fcrhvi-medium.jpg" alt="Laravel 10 PHP 8.1 requirement screenshot" loading="lazy" />

### [Predis v1 removal](https://laravel.com/docs/10.x/redis)

If you’re forcing the usage of Predis v1 in your project, upgrade to v2 (`predis/predis` ^2.0) or consider using [PHP’s native Redis extension](https://github.com/phpredis/phpredis), which is often faster.

See the pull request on GitHub: [Drop Predis v1 support PR](https://github.com/laravel/framework/pull/44209).

### [dispatchNow() removed](https://laravel.com/docs/10.x/upgrade)

`dispatchNow()` was deprecated in Laravel 9 in favor of [`dispatchSync()`](https://laravel.com/docs/9.x/queues#synchronous-dispatching) and was removed in Laravel 10. Search and replace it across your codebase—this is an easy fix. See the pull request on GitHub: [Remove deprecated dispatchNow functionality PR](https://github.com/laravel/framework/pull/42591).

### Many deprecated methods and properties removed

Releasing a major version also means the Laravel team can remove features that were deprecated in Laravel 9. Carefully test any Laravel application you plan to migrate to version 10.

Here’s a list of related PRs:
- [Remove deprecated Route::home method](https://github.com/laravel/framework/pull/42614)
- [Remove deprecated assertTimesSent](https://github.com/laravel/framework/pull/42592)
- [Remove deprecated method](https://github.com/laravel/framework/pull/42590)
- [Remove deprecated dates property](https://github.com/laravel/framework/pull/42587)
- [Use native PHP 8.1 array_is_list function](https://github.com/laravel/framework/pull/41347)
- [Remove deprecations](https://github.com/laravel/framework/pull/41136)

## How to contribute to Laravel 10?

Did you know you could create the next big feature for Laravel 10?

1. See what’s going on for Laravel 10 on GitHub: browse [laravel/framework pull requests](https://github.com/laravel/framework/pulls). Pull requests will tell you what’s already been done.
2. Take one of your pain points with the framework and create a solution yourself.
3. Send the PR to the laravel/framework repository, collect feedback, improve, and get merged.

One important tip to increase your chances of being merged: add something to the framework that’s a win for developers, but not a pain to maintain for Taylor and his team in the long run.

<img src="https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/76/conversions/Screenshot_2022-12-16_at_17.41.05_emctz5-medium.jpg" alt="Screenshot of pull requests on the laravel/framework repository" loading="lazy" />

## Laravel v10 bug hunt: win $1K for fixing bugs

<img src="https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/77/conversions/fUKAbwEdvBvch2YmrOx2j8izeE2avyECiR4o69gF_kuoazm-medium.jpg" alt="Laravel 10 Bug Hunt banner" loading="lazy" />

[Taylor Otwell announced the Laravel 10 Bug Hunt](https://laravel.com/blog/laravel-v10-bug-hunt). Fix bugs, and you could be one of the random winners who receive $1K.

The contest ended when Laravel 10.0 stable shipped on February 14, 2023.

Here are the rules:
- Only PRs sent to the 10.x branch of the [laravel/framework](https://github.com/laravel/framework) repository are eligible.
- Only "true" bug fixes are accepted. New features, refactoring, or typo fixes will not be counted.
- Every bug fix must include a test.
- Accepted bug fixes will be labeled, and a random winner will be selected at the end of the contest.

More details on the official Laravel blog: [Laravel 10 Bug Hunt](https://laravel.com/blog/laravel-v10-bug-hunt)

## Conclusion

Laravel 10 brings a lot of quality-of-life updates: Pennant for feature flags, the new Process facade, invokable validation rules, native type declarations, and clear Composer/PHP requirements (Composer 2.2+, PHP 8.1+). Keep an eye on support timelines (Laravel 9 bug fixes until August 8, 2023; security fixes until February 6, 2024).

Ready to move forward? Start with my [upgrade to Laravel 10 guide](https://benjamincrozat.com/laravel-10-upgrade-guide).

If Laravel 10 is only one stop in a longer upgrade path for you, these are the release reads I would keep open:

- [Laravel 10: the upgrade guide from version 9](/laravel-10-upgrade-guide)
- [Laravel 11 is out! Here are every new big changes and features.](/laravel-11)
- [Laravel 11: an easy and step by step upgrade guide](/laravel-11-upgrade-guide)
- [Laravel 12: new starter kits, changes, and upgrade tips](/laravel-12)
- [The history of Laravel's versions (2011-2025)](/laravel-versions)
