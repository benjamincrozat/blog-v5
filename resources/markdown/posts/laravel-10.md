---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/6/laravel-10_ovwmiu.png
Title: "Laravel 10 is out! Here are every new features and changes."
Description: "Laravel 10 has been released on February 14, 2023. Let's dive into every relevant new features and changes."
Published at: 2022-09-15
Modified at: 2023-11-02
Categories: laravel
---

## Introduction

Laravel 9 is retiring. The framework gained a shiny new 10th version, and I'll tell you everything about it.

## Laravel 10 release date

**Laravel 10 was released on February 14, 2023, and currently is the latest version of the framework.**

But take it slow! It doesn't mean you have to update all your projects immediately.

Laravel 9 will receive bug fixes until August 23, 2023 and security fixes until February 6, 2024.

| Version | PHP | Release | Bug fixes until | Security fixes until |
| ------- | --- | ------------ | --------------- | -------------------- |
| 9 | 8.0 - 8.1 | February 8, 2022 | August 8, 2023 | February 6, 2024 |
| 10 | 8.1 - 8.2 | February 14, 2023 | August 6, 2024 | February 4, 2025 |

## Is Laravel 10 LTS (Long Term Support)?

**No, Laravel 10 isn't LTS, but it provides two years of support.**

The framework last had LTS in version 6 and you can [learn all about LTS versions here](/laravel-versions).

Like I said, each major version offer two years of bug and security fixes, which is plenty of time to prepare your application to [upgrade to the next major version](https://benjamincrozat.com/laravel-10-upgrade-guide).

## How to install Laravel 10?

Using the official [Laravel installer](https://laravel.com/docs/10.x/installation#your-first-laravel-project):

```bash
laravel new hello-world
```

Or, if you prefer to use Composer explicitly:

```bash
composer create-project --prefer-dist laravel/laravel hello-world
```

## How to upgrade to Laravel v10?

Upgrading to Laravel 10 requires more than just following upgrade instructions. Before proceeding, consider thinking this through. 

Check out [my guide to upgrading to Laravel 10](https://benjamincrozat.com/laravel-10-upgrade-guide) if you need more clarification about the process and considerations you should have before giving the green light. I will also talk about a miracle solution to automatize the process, which should be very helpful to businesses like agencies.

## What's new in Laravel 10: features and changes

### Laravel Pennant: a Feature Flags first-party package for Laravel 10

![Laravel Pennant](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/74/conversions/FonDXMhXgAAY16E_nr7fzx-medium.jpg)

**[Laravel Pennant](https://laravel.com/docs/10.x/pennant) is a first-party package that adds feature flags to any Laravel 10 project.**

```bash
composer require laravel/pennant
```

Features flags are a way to enable or disable features at runtime without changing your code.

For instance, you can deploy a feature only for a select set of users in your production environment. This is great for A/B testing.

```php
use Laravel\Pennant\Feature;
use Illuminate\Support\Lottery;
 
Feature::define('new-onboarding-flow', function () {
    return Lottery::odds(1, 10);
});
```

Check if the user user has access to the feature:

```php
if (Feature::active('new-onboarding-flow')) {
    //
}
```

There's even a Blade directive:

```blade
@feature('new-onboarding-flow')
    …
@endfeature
```

Learn more about Laravel Pennant on the [official documentation](https://laravel.com/docs/10.x/pennant).

Laravel News also has a [step-by-step tutorial](https://laravel-news.com/laravel-pennant).

### Handle external processes with ease

Laravel 10 introduced a simple yet comprehensive API for the [Symfony Process component](https://symfony.com/doc/current/components/process.html), enabling you to run external processes within your Laravel application easily. And I absolutely love it!

The process functionality in Laravel is designed to cater to the most frequent usage scenarios, resulting in an exceptional experience for developers. 🔥

This is how you use it:

```php
use Illuminate\Support\Facades\Process;
 
$result = Process::run('ls -la');
 
return $result->output();
```

You can even run processes concurrently. I love the inspiration that comes from Laravel's HTTP client.

```php
use Illuminate\Process\Pool;
use Illuminate\Support\Facades\Pool;
 
[$first, $second, $third] = Process::concurrently(function (Pool $pool) {
    $pool->command('cat first.txt');
    $pool->command('cat second.txt');
    $pool->command('cat third.txt');
});
 
return $first->output();
```

There's more to learn about processes on the [official documentation](https://laravel.com/docs/10.x/processes).

See the pull request on GitHub: [[10.x] Process DX Layer](https://github.com/laravel/framework/pull/45314)

### Identify slow-running tests

The Artisan command `php artisan test` can now receive a `--profile` option that will allow you to easily spot the slowest tests.

I'm glad; this is extremely useful.

This command is provided by the package `nunomaduro/collision` in its 7th version. Make sure you made the necessary changes if you upgraded from Laravel 9.

![Identify slow-running tests in Laravel 10](https://user-images.githubusercontent.com/5457236/217328439-d8d983ec-d0fc-4cde-93d9-ae5bccf5df14.png)

### Laravel v10 uses invokable validation rules by default

In Laravel 9, [invokable validation rules](https://laravel.com/docs/9.x/validation#custom-validation-rules) could be generated using the `--invokable` flag with the `php artisan make:rule` command. Starting from Laravel 10, you don't need it anymore.

```php
php artisan make:rule Uppercase
```

To remind you a bit of what invokable validation rules are, here's what they look like:

```php
namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class Uppercase implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): Illuminate\Translation\PotentiallyTranslatedString $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        if (strtoupper($value) !== $value) {
            $fail('The :attribute must be uppercase.');
        }
    }
}
```

The boilerplate code is considerably smaller and easier to understand than when using the old way. Thanks to Laravel 10, I think people will be less intimidated by the perspective of making custom validation rules.

See the pull request on GitHub: [[10.x] Make invokable rules default](https://github.com/laravel/docs/pull/8165)

### The Laravel 10 skeleton uses native types instead of docblocks

Starting with Laravel 10, the skeleton will now use native types instead of docblocks.

For instance, in the Laravel skeleton, the `schedule()` method in *app/Console/Kernel.php* will look like this:

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

The team also added generic type annotations, which improves autocompletion even further (given your code editor supports generics).

See the pull request on GitHub: [[10.x] Uses PHP Native Type Declarations 🐘](https://github.com/laravel/laravel/pull/6010)

### First party Laravel packages also use native types

Official packages for Laravel won't be left out of this transition.

Native type hints will be used everywhere across the Laravel organization.

You can check out [this PR](https://github.com/laravel/jetstream/pull/1175), that initiates the switch from dockblocks to native type hints in [Laravel Jetstream](https://jetstream.laravel.com).

### Laravel 10 lets you customize the path of config files

A contributor added the possibility to set a custom path for config files. This is useful for projects slowly migrating to Laravel that can't handle a radical directory structure change.

In your *bootstrap/app.php*, use the `configPath()` method from the `$app` object.

```php
$app->configPath(__DIR__ . '/../some/path');
```

(And did you also know about `bootstrapPath()`, `databasePath()`, `langPath()`, etc.? Laravel is highly customizable.)

Learn more: [[10.x] Config path customization](https://github.com/laravel/framework/pull/46053)

### doctrine/dbal is not needed anymore to modify columns in migrations

Column modifications happen in your migrations like so:

```php
…

return new class extends Migration
{
    public function up()
    {
        Schema::table('foo', function (Blueprint $table) {
            $table->unsignedBigInteger('bar')->change();
        });
    }
  
    …
}
```

In Laravel 9, you had to install [doctrine/dbal](https://github.com/doctrine/dbal) to do the job. But now, migrations support the native operations provided by most of the databases Laravel supports.

Suppose you have multiple database connections and already installed Doctrine DBAL. In that case, you should call `Schema::useNativeSchemaOperationsIfPossible()` to use native operations before falling back on the package (for instance, SQLite doesn't support this yet).

```php
use Illuminate\Support\Facades\Schema;

…
  
class AppServiceProvider extends ServiceProvider
{
    public function boot()
	{
	    Schema::useNativeSchemaOperationsIfPossible();
	}
}
```

Learn more:
- [[10.x] Add support for native column modifying](https://github.com/laravel/framework/pull/45487)
- [[9.x] Add support for native rename/drop column commands](https://github.com/laravel/framework/pull/45258)

### Laravel 10 requires at least Composer 2.2

[Composer 1.x has been deprecated in 2021](https://blog.packagist.com/deprecating-composer-1-support/).

Therefore, to ensure solid foundations for every new Laravel 10 project, Nuno Maduro proposed to require at least Composer 2.2 (release in December 2021), which also appears to be an LTS version that will be updated until the end of 2023 (Composer is at version 2.5.3 at the time I write these lines).

Learn more: [[10.x] Requires Composer ^2.2](https://github.com/laravel/framework/pull/46096)

### Dropped support for PHP 8.0

Laravel 10 dropping support for PHP 8.0 and requiring 8.1 as a minimum means two things if you want to upgrade:
- Either migrate to PHP 8.1
- Or PHP 8.2

But remember: your Laravel apps don't need to be updated to the latest and greatest as soon as they're released.

Especially if you have projects with paid clients or employees who depend on them to do their work.

They need to slowly but surely move forward by doing extensive testing. Don't rush.

See the pull request on GitHub: [[10.x] Drop PHP 8.0](https://github.com/laravel/laravel/pull/5854)

![PHP 8.1](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/75/conversions/Screenshot_2022-12-16_at_17.32.08_fcrhvi-medium.jpg)

### Dropped support for Predis v1

If you're forcing the usage of Predis v1 in your project, you might want to upgrade to v2.

To see what changed in Predis v2, take a look at the [changelog](https://github.com/predis/predis/blob/main/CHANGELOG.md#v200-2022-06-08).

See the pull request on GitHub: [[10.x] Drop Predis v1 support](https://github.com/laravel/framework/pull/44209)

In my opinion, instead of using Predis, you should consider using [PHP's native Redis extension](https://github.com/phpredis/phpredis), which is faster and could speed up your website if you have a lot of traffic.

### dispatchNow() has been removed from Laravel 10

`dispatchNow()` is a popular method in Laravel. It was deprecated in Laravel 9 in favor of [`dispatchSync()`](https://laravel.com/docs/9.x/queues#synchronous-dispatching). Laravel 10 will remove it, so be sure to search and replace it in all of your projects. It may be a breaking change, but it's an extremely easy fix.

See the pull request on GitHub: [[10.x] Remove deprecated dispatchNow functionality](https://github.com/laravel/framework/pull/42591)

### Many deprecated methods and properties have been removed in Laravel 10

Releasing a major version also means the Laravel team can finally remove features that have been deprecated in Laravel 9. It also means you should carefully test any Laravel application you might want to migrate to version 10.

Here's a list of all PRs taking care of that:
- [[10.x] Remove deprecated Route::home method](https://github.com/laravel/framework/pull/42614)
- [[10.x] Remove deprecated assertTimesSent](https://github.com/laravel/framework/pull/42592)
- [[10.x] Remove deprecated method](https://github.com/laravel/framework/pull/42590)
- [[10.x] Remove deprecated dates property](https://github.com/laravel/framework/pull/42587)
- [[10.x] Use native php 8.1 array_is_list function](https://github.com/laravel/framework/pull/41347)
- [[10.x] Remove deprecations](https://github.com/laravel/framework/pull/41136)

## How to contribute to Laravel 10?

Did you know you could create the next big feature for Laravel 10?

1. See what's going on for Laravel 10 on GitHub: https://github.com/laravel/framework/pulls. Pull Requests will tell you what's already been done.
2. Take one of your pain points with the framework and create a solution yourself.
3. Send the PR over to the laravel/framework repository, collect feedback, improve and get merged.

One important tip to increase your chances of being merged: add something to the framework that's a win for developers, but not a pain to maintain for Taylor and his team in the long run.

![Pull requests on the laravel/framework repository.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/76/conversions/Screenshot_2022-12-16_at_17.41.05_emctz5-medium.jpg)

## Laravel v10 Bug Hunt: win $1K for fixing bugs

![Laravel 10 Bug Hunt](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/77/conversions/fUKAbwEdvBvch2YmrOx2j8izeE2avyECiR4o69gF_kuoazm-medium.jpg)

[Taylor Otwell announced](https://twitter.com/taylorotwell/status/1612474064089612288?s=61&t=8otyXo8M-TVJPJtuVyb8ng) the Laravel 10 bug hunt.

Fix bugs, and be one of the random winner to get $1K richer!

This contest will end as soon as Laravel 10 is released.

Here are the rules:
- Only PRs sent to the 10.x branch of the [laravel/framework](https://github.com/laravel/framework) repository are eligible.
- Only "true" bug fixes are accepted. New features, refactoring, or typo fixes will not be counted.
- Every bug fix must include a test.
- Accepted bug fixes will be labelled, and a random winner will be selected at the end of the contest.

More details on the official Laravel blog: [Laravel 10 Bug Hunt](https://blog.laravel.com/laravel-v10-bug-hunt)

