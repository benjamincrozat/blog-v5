---
id: "01KKEW27BSJDEWEBSTHRMRPRM0"
title: "25 Laravel best practices, tips, and tricks"
slug: "laravel-best-practices"
author: "benjamincrozat"
description: "Learning a framework can be overwhelming, but time and execution will make you a master. Here are some best practices to help you toward your goal."
categories:
  - "laravel"
published_at: 2022-10-30T01:00:00+02:00
modified_at: 2025-09-16T04:23:00+02:00
serp_title: "25 Laravel best practices, tips, and tricks (2025)"
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01JYZ22BGRDPV8C7SCG9FJWR63.webp"
sponsored_at: null
---
## Introduction

For most Laravel projects, the best practices come down to two points:
- Stick to the defaults.
- Defer as much work as possible to the framework.

Whether you are running Laravel 12, 11, or 10, I will show how I improve any codebase with clear tips and examples.

**By the way, in addition to this article, I recommend these books to keep leveling up with Laravel:**
- [Battle ready Laravel](/recommends/battle-ready-laravel) by Ash Allen. This will teach you many new things to take your Laravel apps to the next level.
- [Consuming APIs with Laravel](/recommends/consuming-apis-laravel) by Ash Allen. If you thought you knew REST APIs, see what Ash has to say.
- [Mastering Laravel Validation Rules](/recommends/mastering-laravel-validation-rules) by Aaron Saray and Joel Clermont. Learn to ensure data integrity with practical examples.

## Laravel best practices, tips, and tricks

### Keep Laravel up to date

[Keeping Laravel up to date](https://laravel.com/docs/upgrade) provides the following benefits:
- Improved security: Laravel regularly releases security fixes.
- Better performance: updates often include faster load times and more efficient code.
- New features and functionality: these are why we use and love Laravel.
- Compatibility with the latest [official](https://packagist.org/?query=laravel%2F) and [community packages](https://packagist.org).

If Laravel updates scare you, it is likely because your codebase is not tested. You might fear a major update will break your code in a way that is hard to sort out. If that is the case, testing is a best practice you should adopt. More on that below.

### Keep packages up to date

Access to dozens of packages from the official Laravel ecosystem and thousands of community packages is what makes my work easier.

But the more packages you use, the more points of failure you can face.

Regularly running `composer update` is one of the easiest best practices to adopt and goes a long way toward a more secure codebase.

It is the same as in the previous section: if your code is not well tested, unexpected regressions can occur. Do not worry. The next sections give you a starting point to level up on that front. 💪

### Keep your project tested to prevent critical bugs

![Keep your Laravel project tested](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/100/conversions/Screenshot_2023-01-24_at_13.07.02_kbbhcm-medium.jpg)

[Writing automated tests](https://laravel.com/docs/testing) is a vast topic that many developers skip.

It is also one of the few best practices that ensures reliability.

Here are the benefits of a good test suite:
- Fewer bugs.
- Happier customers.
- Happier employers.
- Confident developers. You will not fear breaking something when you return to the project later.
- New hires can be productive from day one, especially if you follow Laravel's guidelines. Changed some code? No problem. Just run `php artisan test`, see what you broke, fix, and repeat.

Being able to make a project much more stable thanks to automated testing will do wonders for your career.

[Laracasts](https://laracasts.com) provides free testing courses to help you get started. One with PHPUnit, the industry standard, and one with Pest, which offers a modern, fluent API for testing in PHP.

1. [PHP Testing Jargon](https://laracasts.com/topics/phpunit).
2. [Pest From Scratch](https://laracasts.com/topics/pest) (this is the one I recommend).

### Stick to the default folder structure

Do you know why you are using a framework?

1. It frames your work with a set of guidelines you can follow so every teammate is on the same page.
2. It provides many complex, tedious, and battle‑tested features for free, so you can focus on what is specific to your project.

So, is it a best practice to stick to Laravel's default project structure?
1. Convenience: Laravel's default way of doing things [is documented](https://laravel.com/docs). When you return to a project weeks or months later, you will thank your past self for this.
2. Working with teammates is much easier. They know Laravel, just like you. Use this shared knowledge to move the project forward instead of reinventing the wheel every time.

When should you not stick to the defaults?

When the size of your project actually requires doing things differently.

Read more on [architecture best practices](https://benjamincrozat.com/laravel-architecture-best-practices#introduction).

![Stick to the default Laravel folder structure](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/101/conversions/CleanShot_2023-07-10_at_06.53.48_2x_jpbv9m-medium.jpg)

### Use custom form requests for complex validation

The main reasons to use custom [form requests](https://laravel.com/docs/validation#form-request-validation) are:
1. Reusing validation across multiple controllers.
2. Offloading code from bloated controllers.

Creating custom form requests is as simple as running this Artisan command:

```bash
php artisan make:request StorePostRequest
```

Then, in your controller, just type‑hint it:

```php
use App\Http\Requests\StorePostRequest;

class PostController
{
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        Post::create($validated);

        //
    }
}
```

Custom form requests can also include extra logic beyond validation. For example, you can use them to normalize input data before it reaches your controller:

```php
public function prepareForValidation(): void
{
    $this->merge([
        'slug' => Str::slug($this->title),
    ]);
}
```

This method will generate a slug from the title before validation occurs.

They can also manage [authorization](https://laravel.com/docs/authorization#creating-policies) checks if you feel like [Policies](https://laravel.com/docs/authorization#creating-policies) are overkill.

### Use single action controllers to keep the code organized

Sometimes, even when I follow all the best practices, controllers become too big.

Laravel provides [single action controllers](https://laravel.com/docs/controllers#single-action-controllers) to help.

Instead of multiple actions (index, create, store, show, etc.), a single action controller has just one.

To create one, use:

```bash
php artisan make:controller ShowPostController --invokable
```

This creates a controller with only one action named `__invoke` ([learn more about the __invoke magic method](https://www.php.net/manual/en/language.oop5.magic.php#object.invoke)).

Then, in your routes, you can do this instead:

```php
use App\Http\Controllers\PostController; // [tl! --]
use App\Http\Controllers\ShowPostController; // [tl! ++]

Route::get('/posts/{post}', [PostController::class, 'show']); // [tl! --]
Route::get('/posts/{post}', ShowPostController::class); // [tl! ++]
```

This is a subjective best practice. I like it for focused actions, and you can decide what fits your team.

### Use middlewares instead of repeating code

Middlewares in Laravel let you filter or modify the current request. Common uses:
- Checking for required permissions.
- Checking the user's language and changing the locale.

Laravel ships with many middlewares for authentication, rate limiting, and more.

Once your middleware does what it is supposed to do, you can either block the request or let it go through.

```php
public function handle(Request $request, Closure $next): Response
{
    if (! $request->user()->hasEnoughTokens()) {
        abort(403);
    }

    return $next($request);
}
```

A middleware can be attached to many routes, which helps you prevent code duplication.

Learn more about [Laravel middleware](https://laravel.com/docs/middleware).

### Use policies for authorization

Using [policies](https://laravel.com/docs/authorization#creating-policies) for authorization in Laravel is key to an organized and maintainable app. Here are three reasons I rely on policies:
1. Reuse authorization logic across multiple controllers.
2. Offload code from bloated controllers.
3. Make it easy to find and update authorization rules in app/Policies.

A simple example:

```php
// app/Policies/PostPolicy.php
public function update(User $user, Post $post): bool
{
    return $user->id === $post->user_id;
}

// app/Http/Controllers/PostController.php
public function update(Request $request, Post $post)
{
    $this->authorize('update', $post);

    // ...
}
```

### Keep migrations up to date

Migrations let you describe your database schema in plain PHP.

See them like phpMyAdmin, but with code instead of a user interface.

This helps everyone on the team replicate the same environment locally and keep history in Git.

That is also how you deploy to new environments, like staging and production, without exporting a database from somewhere else.

Sometimes developers edit the database directly instead of creating a migration. This is bad and makes life harder for other developers. There is nothing more annoying than asking teammates on Slack for a database dump.

Read more about [how migrations can improve any project](/laravel-migrations).

### Use anonymous migrations to avoid conflicts (Laravel 8.37+)

Anonymous migrations help you avoid class name conflicts. For example, you can create many "update_posts_table" migrations without errors. Anything that reduces friction is good.

They were introduced in Laravel 8.37 and are used by default in modern stubs.

```bash
php artisan make:migration UpdatePostsTable
```

A typical anonymous migration looks like this:

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // ...
        });
    }

    public function down(): void
    {
        // ...
    }
};
```

If you are on older 8.x projects that still have named classes, you can change the class to `return new class` and add a semicolon at the end to get the same effect.

### Use the down() method correctly for rollbacks

The `down()` method (used by the `php artisan migrate:rollback` command) is run when you need to roll back changes to your database.

Some people use it, some do not.

If you belong to the people who use it, make sure your `down()` method is implemented correctly.

It must do the opposite of the `up()` method.

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // The column was a boolean, but we want to switch to a datetime.
            $table->dateTime('is_published')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // When rolling back, restore the column to its previous state.
            $table->boolean('is_published')->default(false)->change();
        });
    }
};
```

Note: when modifying a column, re‑state any modifiers you want to keep (unsigned, default, comment). In Laravel 11/12, many databases use native schema operations for column changes, so previous DBAL requirements are reduced. See the docs on [migrations](https://laravel.com/docs/11.x/migrations#modifying-columns).

### Use Eloquent's naming conventions for table names

Laravel's naming conventions for tables are simple and a best practice that will help your team.

If you use Artisan, the framework does it for you: `php artisan make:model Post --migration --factory`.

If you cannot use those commands, here is a quick overview:
- For a `Post` model, name your table `posts`. Use the plural form (`comments` for `Comment`, `replies` for `Reply`, etc.).
- For a pivot table linking a `Post` to a `Tag` (for example, `post_tag`):
  - Use both names.
  - Singular form.
  - Alphabetical order.

Read the docs on [many‑to‑many relationships](https://laravel.com/docs/12.x/eloquent-relationships#many-to-many) for more details.

### Prevent N+1 issues with eager loading

I have talked about many best practices, and there is more.

Ever heard about N+1 problems? [Eager loading](https://laravel.com/docs/12.x/eloquent-relationships#eager-loading) is how you avoid them.

![Example of N+1 queries detected in Laravel](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/102/conversions/Screenshot_2022-12-11_at_10.23.52_scjggt-medium.jpg)

Say you display a list of 30 posts with their author:
- Eloquent will make one query for the 30 posts.
- Then 30 more queries for each author, because the `author` relationship is lazy loaded when you call `$post->author`.

The fix is simple. Use `with()`. You will go from 31 queries to only 2.

```php
Post::with('author')->get();
```

To make sure you do not have N+1 problems, you can throw exceptions when any relationship is lazy loaded. Apply this only in your local environment.

```php
use Illuminate\Database\Eloquent\Model;

if (! app()->isProduction()) {
    Model::preventLazyLoading();
}
```

### Use Eloquent's strict mode to prevent performance issues and bugs

[Eloquent strictness](https://laravel.com/docs/11.x/eloquent#preventing-lazy-loading) helps you catch issues during development by throwing exceptions when:
1. A relationship is lazy loaded.
2. You try to set attributes that are discarded.
3. You access missing attributes.

Enable strictness in `App\Providers\AppServiceProvider::boot()` and only outside production:

```php
use Illuminate\Database\Eloquent\Model;

public function boot(): void
{
    if (! app()->isProduction()) {
        Model::preventLazyLoading();
        Model::preventSilentlyDiscardingAttributes();
        Model::preventAccessingMissingAttributes();
    }
}
```

Note: `Model::shouldBeStrict()` still works, but the current docs highlight the specific methods above.

### Use the new way of declaring accessors and mutators

The new way of declaring [accessors and mutators](https://laravel.com/docs/12.x/eloquent-mutators) arrived in Laravel 9.

Here is how you should declare them now:

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pokemon
{
    public function name(): Attribute
    {
        $locale = app()->getLocale();

        return Attribute::make(
            get: fn ($value) => $value[$locale],
            set: fn ($value) => [$locale => $value],
        );
    }
}
```

You can cache expensive values (via `Attribute::shouldCache()`):

```php
use Illuminate\Database\Eloquent\Casts\Attribute;

public function someAttribute(): Attribute
{
    return Attribute::make(
        get: fn () => /* compute value */ 42,
    )->shouldCache();
}
```

The old way looked like this:

```php
class Pokemon
{
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();

        return $this->attributes['name'][$locale];
    }

    public function setNameAttribute($value): string
    {
        $locale = app()->getLocale();

        return $this->attributes['name'][$locale] = $value;
    }
}
```

### Use dispatchAfterResponse() for long-running tasks

Let us use a simple example. You have a contact form. Sending an email may take one or two seconds.

What if you could delay this until after the user gets the response?

That is what [`dispatchAfterResponse()`](https://laravel.com/docs/12.x/queues#dispatching-after-the-response-is-sent-to-the-browser) does:

```php
SendContactEmail::dispatchAfterResponse($input);
```

Or, if you prefer an inline job:

```php
dispatch(function () {
    // Do something.
})->afterResponse();
```

It works after the HTTP response if your web server uses FastCGI. Use it for sub‑second tasks, like sending mail. You do not need a worker for these.

### Use queues for even longer running tasks

Imagine you need to process images uploaded by users.

If you process every image right away, this will happen:
- Your server will burn.
- Users will wait in front of a loading screen.

This is not good UX, and we can change that.

Laravel has a [queue system](https://laravel.com/docs/12.x/queues) that runs tasks sequentially or with limited parallelism.

To manage jobs through a UI, use [Laravel Horizon](https://laravel.com/docs/horizon).

![Horizon dashboard queue metrics in Laravel](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/103/conversions/Screenshot_2023-01-24_at_13.14.33_awtguk-medium.jpg)

Small note on web performance: Google replaced FID with INP as a Core Web Vital. Keep long tasks off the request path to help INP.

### Lazily refresh your database before each test

When you can use fake data in your local environment, a great option is to test against a fresh database for each run.

Use the `Illuminate\Foundation\Testing\LazilyRefreshDatabase` trait in your `tests/TestCase.php`.

There is also a `RefreshDatabase` trait, but the lazy one is more efficient, as migrations for unused tables will not be run during testing.

### Make use of factories to help you with fake data and tests

[Factories](https://laravel.com/docs/eloquent-factories) make testing much easier.

You can create one with `php artisan make:factory PostFactory` and add fake data to each column:

```php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'content' => fake()->paragraphs(5, true),
            'description' => fake()->paragraph(),
        ];
    }
}
```

Factories create everything you need when writing tests.

Here is one in action:

```php
public function test_it_shows_a_given_post(): void
{
    $post = Post::factory()->create();

    $this
        ->get(route('posts.show', $post))
        ->assertOk();
}
```

### Test against the production stack whenever it's possible

In production, you probably use something other than SQLite, like MySQL. Or Redis instead of the array cache driver.

So why not use them when running your tests too? Bugs can appear only with those backends, and tests are supposed to help you catch them before they happen in production.

I value reliability and accuracy over raw test speed here.

### Use database transactions to rollback changes after each test

In one of my projects, I need to create a database filled with real data from CSV files on GitHub.

It takes time, and I cannot refresh my database before every test. It is too slow.

So when my tests alter the data, I want to roll back the changes to keep the database in its initial state. You can do this with the `Illuminate\Foundation\Testing\DatabaseTransactions` trait in your base test case class (`tests/TestCase.php`).

### Don't waste API calls, use mocks

In Laravel, mocks help you avoid wasting API calls during tests and hitting rate limits.

Say we use Twitter's API. In our container, we have a `Client` class.

When running the test suite, we want to avoid unnecessary calls to the real API. The best way is to swap the client in the container with a mock.

```php
$mock = $this->mock(Client::class);

$mock
    ->shouldReceive('getTweet')
    ->with('Some tweet ID')
    ->andReturn([
        'data' => [
            'author_id' => '2244994945',
            'created_at' => '2022-12-11T10:00:55.000Z',
            'id' => '1228393702244134912',
            'edit_history_tweet_ids' => ['1228393702244134912'],
            'text' => 'This is a tweet',
        ],
    ]);
```

Learn more about [mocking](https://laravel.com/docs/mocking) in the docs.

### Prevent stray HTTP requests to identify slow tests

Here is a great tip if you want to make sure all HTTP requests in your tests are faked. Use `Http::preventStrayRequests()` from the HTTP facade.

It will throw an exception if any HTTP request without a fake response is executed.

Use this in a single test or for your entire suite.

```php
use Illuminate\Support\Facades\Http;

Http::preventStrayRequests();
```

If you need to allow a few real requests, you can pair this with `Http::allowStrayRequests()` or use `Http::fake([...])` to allow‑list specific hosts or patterns. See the [HTTP client docs](https://laravel.com/docs/12.x/http-client#preventing-stray-requests).

### Don't track your .env file

Your `.env` file contains sensitive information.

Please, do not track it.

Make sure it is in your `.gitignore`.

Most of the time, data leaks happen inside jobs.

A password manager is a better solution for sharing credentials.

Also, commit a `.env.example` file so teammates know which keys they need. See [environment configuration](https://laravel.com/docs/configuration#environment-configuration).

### Don't track your compiled CSS and JavaScript

Your CSS and JavaScript are generated from sources in `resources/css` and `resources/js`.

With Vite, the build output is `public/build`.

If you build on deploy, add this to `.gitignore`:

```
/public/build
```

## FAQ

### What are the most important Laravel best practices in 2025?

The biggest wins are simple. Keep Laravel and packages updated, build a solid testing foundation, enable Laravel Eloquent strictness, use eager loading to prevent N+1, and move slow work to queues or `dispatchAfterResponse`. These practices make a Laravel app safer, faster, and easier to maintain.

### How do I prevent N+1 queries in Eloquent?

Use eager loading with `with()` on your Laravel queries, and enable `Model::preventLazyLoading()` in non‑production. This keeps related data from being loaded one row at a time. See [eager loading](https://laravel.com/docs/12.x/eloquent-relationships#eager-loading) for examples.

### Should I use Single Action Controllers vs. resource controllers?

Both work in Laravel. I use single action controllers for focused behaviors and resource controllers when I want a full CRUD shape. Pick one approach per feature so your Laravel project stays consistent.

### How do I safely modify columns in Laravel 11/12?

When you change a column, re‑state any modifiers you want to keep, like `unsigned`, `default`, or `comment`. Laravel 11/12 use more native schema operations, so DBAL needs are lower than in 9/10. See [modifying columns](https://laravel.com/docs/11.x/migrations#modifying-columns).

### What is the difference between Http::fake and preventStrayRequests?

`Http::fake` lets you provide fake responses for specific URLs or patterns in Laravel tests. `Http::preventStrayRequests()` blocks any HTTP call that does not match a fake, which prevents unplanned real network calls and helps you find slow tests. See the [HTTP client](https://laravel.com/docs/12.x/http-client).

### When should I use dispatchAfterResponse vs. queues?

Use `dispatchAfterResponse` for very short, one‑off tasks that can run right after the HTTP response, like sending a confirmation email. Use Laravel queues for heavier or many tasks, like processing images or syncing large data sets, and manage them with Horizon.

## Conclusion

- Keep Laravel and packages updated, and rely on the defaults when you can.
- Build a test suite with factories and database refresh or transactions, and prevent stray HTTP requests.
- Enable Eloquent strictness and use eager loading to avoid N+1 problems.
- Defer slow work with `dispatchAfterResponse` and move heavy tasks to queues and Horizon.

If you want a few more Laravel rabbit holes after this:

- [3 crucial Laravel architecture best practices for 2025](/laravel-architecture-best-practices)
- [Laravel 11: an easy and step by step upgrade guide](/laravel-11-upgrade-guide)
- [Validation in Laravel made easy](/laravel-validation)





