---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/17/best-practices_enpotj.png
Title: "20+ Laravel best practices, tips and tricks to use in 2024"
Description: "Learning a framework can be overwhelming, but time and execution will make you a master. Here are some best practices to help you toward your goal."
Published at: 2022-10-30
Modified at: 2024-08-29
Categories: laravel
---

## Introduction to Laravel best practices

For most Laravel projects, the best practices can be summarized as two points:
- Stick to the defaults.
- Defer as much work as possible to the framework.

Whether you are running Laravel 11, 10, or 9, let's see in detail how I can help you improve any codebase with tons of tips and tricks.

**By the way, in addition to this article, let me recommend you great books to keep leveling up with Laravel:**
- [Battle ready Laravel](/recommends/battle-ready-laravel) by Ash Allen. This will teach you a lot of new things to take your Laravel apps to the next level.
- [Consuming APIs with Laravel](/recommends/consuming-apis-laravel) by Ash Allen too. If you thought you knew a lot about REST APIs, maybe you should see what Ash has to say.
- [Mastering Laravel Validation Rules](/recommends/mastering-laravel-validation-rules) by Aaron Saray and Joel Clermont. This book teaches you how to ensure data integrity in your apps like a pro with super handy real world examples.

## Laravel best practices, tips, and tricks

### Keep Laravel up to date

[Keeping Laravel up to date](https://laravel.com/docs/upgrade) provides the following benefits:
- **Improved security**: Laravel regularly releases security fixes.
- **Better performances**: Laravel updates often include performance improvements, such as faster load times and more efficient code.
- **New features and functionality**: These are why we use and love Laravel and the reason it changed our lives.
- **Compatibility with the latest [official](https://packagist.org/?query=laravel%2F) and [community packages](https://packagist.org).**

If Laravel updates scare you, it's because your codebase isn't tested. You are afraid a major update will break your code in a way that makes it almost impossible to sort out. If that's the case, testing is a best practice you should implement. More on that below.

### Keep packages up to date

Access to dozens of packages from the official Laravel ecosystem as well as thousands of community packages are what make our job easier.

But **the more packages you use, the more points of failure** you can be subject to.

Regularly running `composer update` is one of the easiest best practices to adopt and goes a long way toward a more secure codebase.

But of course, it's the same thing as in the previous section: if your code isn't well tested, unexpected regressions can occur. But don't worry; the next questions will give you a starting point to level up on that front! 💪

### Keep your project tested to prevent critical bugs

![Keep your project tested](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/100/conversions/Screenshot_2023-01-24_at_13.07.02_kbbhcm-medium.jpg)

[Writing automated tests](https://laravel.com/docs/testing) is a vast and lesser-known topic among developers.

But did you know it's also one among a limited set of best practices that ensures reliability?

Here are the benefits of a good test suite:
- Fewer bugs.
- Happier customers.
- Happier employers.
- Confident developers. You won't fear breaking something when coming back to the project after a while.
- New hires can be productive from day one, especially if you follow Laravel's guidelines. Changed some code? No problem. Just run `php artisan test` see what you broke, fix, and repeat! 

**Being able to make a project immensely more stable thanks to automated testing will do wonders for your career. **

[Laracasts](https://laracasts.com) provides free testing courses to help you get started. One with PHPUnit, the industry standard, and one with Pest, the best testing framework on this planet that modernizes and simplifies testing in PHP.

1. [PHP Testing Jargon](https://laracasts.com/topics/phpunit).
2. [Pest From Scratch](https://laracasts.com/topics/pest) (this is the one I recommend).

### Stick to the default folder structure

Do you know why you're using a framework?

1. It **frames** your **work** with a set of guidelines that you can follow to ensure every member of your team is on the same page;
2. It provides many complex, tedious, and battle-tested features for free, so you can focus on coding what is specific to your project.

So, is it considered a best practice to stick to Laravel's default project structure?
1. Convenience: Laravel's default way of doing things [is documented](https://laravel.com/docs). When you come back on a project weeks or months later, you will thank your past self for this.
2. Working with team mates is considerably easier. They know Laravel, just like you. Use this common knowledge to help the project move forward instead of reinventing the wheel every time.

When shouldn't you stick to the defaults?

When the size of your project **actually requires** to do things differently.

Read more on [architecture best practices](https://benjamincrozat.com/laravel-architecture-best-practices#introduction).

![Stick to the default folder structure](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/101/conversions/CleanShot_2023-07-10_at_06.53.48_2x_jpbv9m-medium.jpg)

### Use custom form requests for complex validation

The main reasons to use custom [form requests](https://laravel.com/docs/validation#form-request-validation) are:
1. Reusing validation across multiple controllers;
2. Offloading code from bloated controllers;

Creating custom form requests is as simple as running this Artisan command:

```bash
php artisan make:request StorePostRequest
```

Then, in your controller, just type-hint it:

```php
use App\Http\Requests\StorePostRequest;

class PostController
{
    function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        Post::create($validated);

        //
	}
}
```

Custom form requests can also include additional logic beyond validation. For example, you can use them to normalize input data before it reaches your controller:

```php
public function prepareForValidation()
{
    $this->merge([
        'slug' => Str::slug($this->title),
    ]);
}
```

This method will automatically generate a slug from the title before validation occurs.

But that's not it. They can also manage [authorizations](https://laravel.com/docs/validation#authorizing-form-requests), if you feel like [Policies](https://laravel.com/docs/authorization#creating-policies) are overkill.

### Use single action controllers to keep the code organized

Sometimes, despite following all the best practices, your controllers become too big.

So here's a great tip: Laravel provides a way to create [Single Action Controllers](https://laravel.com/docs/controllers#single-action-controllers).

Instead of containing multiple actions (index, create, store, show, etc.), like Resource Controllers, Single Action Controllers contain just one.

To create one, use the `php artisan make:controller ShowPostController --invokable` command.

This will create a controller with only one action called `__invoke` ([learn more about the __invoke magic method](https://www.php.net/manual/en/language.oop5.magic.php#object.invoke)).

Then, in your routes, you can do this instead:

```php
{-use App\Http\Controllers\PostController;-}
{+use App\Http\Controllers\ShowPostController;+}

{-Route::get('/posts/{post}', [PostController::class, 'show']);-}
{+Route::get('/posts/{post}', ShowPostController::class);+}
```

This is a subjective best practice and it's up to you to decide whether you want to use single action controllers or not.

### Use middlewares instead of repeating code

Middlewares in Laravel allow you to filter or modify the current request. Here are some use cases:
- Checking for the required permissions;
- Check the user's language and change the locale accordingly.

And as you expected, Laravel comes with a bunch of middlewares out of the box for authentication, rate limiting, and more.

Once your middleware did what it's supposed to do, you can either block the request or let it go through.

```php
public function handle(Request $request, Closure $next) : Response
{
    if (! $request->user()->hasEnoughTokens()) {
        abort(403);
    }

    return $next($request);
}
```

A middleware can be attached to any number of routes, which helps you prevent code duplication.

Learn more about [Laravel's middlewares](https://laravel.com/docs/middleware).

### Use policies for authorization

Using [policies](https://laravel.com/docs/authorization#creating-policies) for authorization in Laravel is essential for maintaining an organized and maintainable application. Here are three key reasons to use policies:
1. **Reuse authorization logic across multiple controllers**: By centralizing authorization rules, you can ensure consistency and avoid duplicating code in different parts of your application.
2. **Offload code from bloated controllers**: Moving authorization logic to policies helps keep your controllers lean, focused on their primary responsibilities, and easier to read and maintain.
3. **Easily locate authorization-related code**: Storing policies in the app/Policies folder makes it simple for developers to find and update authorization rules as needed.

Let's look at a real-world example of using a policy:

```php
// app/Policies/PostPolicy.php
public function update(User $user, Post $post)
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

Migrations are a way to describe your database schema in plain PHP code.

See them as a phpMyAdmin, but with code instead of a user interface.

This is extremely useful to help everyone in the team replicate the same environment on their local machine and keep track of it in the Git history.

That's also how you can deploy a project to new environment (staging and production for instance) without worrying about exporting the database from another environment.

However, developers sometimes edit the database directly instead of creating a new migration. This is bad and will make other developers' life harder. There's nothing more annoying than having to ask your colleagues on Slack for a database dump.

Read more about [how migrations can improve any project](/laravel-migrations). 

### Use anonymous migrations to avoid conflicts (Laravel 8 and above)

Anonymous migrations are a great way to avoid class names conflicts. For instance, you can create as many "update_posts_table" migrations as you want without encountering errors anymore. And anything that reduces friction is a good thing.

Laravel generates anonymous migrations for you as long as you're using Laravel 9 and above:

```bash
php artisan make:migration UpdatePostsTable
```

This is how they look:

```php
<?php

use IlluminateSupportFacadesSchema;
use IlluminateDatabaseSchemaBlueprint;
use IlluminateDatabaseMigrationsMigration;

return new class extends Migration {
    …
};
```

But did you know you can also use them with Laravel 8? Just replace the class name by `return new class`, add a `;` at the end, and you'll be good to go.

### Use the down() method correctly for rollbacks

The down() method (used by the php artisan migrate:rollback command) is run when you need to rollback changes you made to your database.

Some people use it, some don't.

If you belong to the people who use it, you should make sure your `down()` method is implemented correctly.

Basically, the `down()` method must do the opposite of the `up()` method.

```php
use IlluminateSupportFacadesSchema;
use IlluminateDatabaseSchemaBlueprint;
use IlluminateDatabaseMigrationsMigration;

return new class extends Migration {
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            // The column was a boolean, but we want to switch to a datetime.
            $table->datetime('is_published')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            // When rolling back, we have to restore the column to its previous state.
            $table->boolean('is_published')->default(false)->change();
        });
    }
}
```

And if you don't belong to the people who want to use it, simply remove it.

### Use Eloquent's naming conventions for table names

Laravel's naming conventions for tables is easy and one best practice that will simplify your team's life.

First, let me remind you that the framework does it all for you when you're using Artisan commands like `php artisan make:model Post --migration --factory`.

For whatever reason, if you can't use those commands, here's an overview:
- For a `Post` model, name your table `posts`. Basically use the plural form (`comments` for `Comment`, `replies` for `Reply`, etc.);
- For a pivot table linking a `Post` to a `Comment` (e.g. `comment_post`):
	- Use both names
	- Singular form
	- Alphabetic order

[Read the documentation](https://laravel.com/docs/9.x/eloquent#eloquent-model-conventions) for more information.

### Prevent N+1 issues with eager loading

I've talked about so many best practices, but it's far to be over!

Ever heard about N+1 problems? [Eager loading](https://laravel.com/docs/eloquent-relationships#eager-loading) is a great solution to avoid them.

![N+1 problem with Eloquent](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/102/conversions/Screenshot_2022-12-11_at_10.23.52_scjggt-medium.jpg)

Let's say you are displaying a list of 30 posts with their author:
- Eloquent will make one query for those 30 posts;
- Then, 30 queries for each author, because the user relationship is lazily loaded (meaning it's loaded each time you call `$post->user` in your code).

The fix is simple: use the `with()` method, and you'll go from 31 queries to only 2.

```php
Post::with('author')->get();
```

To ensure you don't have N+1 problems, you can trigger exceptions whenever you lazy load any relationship. This restriction should be applied to your local environement only.

```php
Model::preventLazyLoading(
	  // Returns `true` unless it's the production environment.
    ! app()->isProduction()
);
```

### Use Eloquent's strict mode to prevent performance issues and bugs

[Eloquent's strict mode](https://laravel.com/docs/eloquent#enabling-eloquent-strict-mode) is a blessing for debugging.

It helps developers catch potential issues during the development phase by throwing exceptions in the following cases:
1. **Lazy loading relationships**: lazy loading can lead to performance issues, especially when dealing with large datasets. It occurs when related models are not retrieved from the database until they are explicitly accessed. In strict mode, an exception will be thrown if a relationship is lazily loaded, encouraging developers to use eager loading instead.
2. **Assigning non-fillable attributes**: the $fillable property on Eloquent models protects against mass assignment vulnerabilities. An exception will be thrown when trying to assign a non-fillable attribute, ensuring that developers handle mass assignment carefully.
3. **Accessing attributes that don't exist (or weren't retrieved)**: accessing non-existent attributes or attributes that haven't been retrieved from the database can lead to unexpected behavior or bugs. Strict mode will throw an exception in these cases, helping developers identify and fix such issues.

To enable it, add this code in the `boot()` method of your *AppServiceProvider.php*:

```php
Model::shouldBeStrict(
    // It will only be enabled outside of production, though.
    ! app()->isProduction()
);
```

### Use the new way of declaring accessors and mutators

The new way of declarating [accessors and mutators](https://laravel.com/docs/eloquent-mutators) was introduced in Laravel 9.

This is how you should declare them now:

```php
use IlluminateDatabaseEloquentCastsAttribute;

class Pokemon
{
    function name() : Attribute
    {
        $locale = app()->getLocale();

        return Attribute::make(
            get: fn ($value) => $value[$locale],
            set: fn ($value) => [$locale => $value],
        );
    }
}
```

You can even cache expensive to compute values:

```php
use IlluminateDatabaseEloquentCastsAttribute;

function someAttribute() : Attribute
{
    return Attribute::make(
        fn () => /* Do something. */
    )->shouldCache();
}
```

The old way looks like this:

```php
class Pokemon
{
    function getNameAttribute() : string
    {
        $locale = app()->getLocale();

        return $this->attributes['name'][$locale];
    }

    function setNameAttribute($value) : string
    {
        $locale = app()->getLocale();

        return $this->attributes['name'][$locale] = $value;
    }
}
```

### Use dispatchAfterResponse() for long-running tasks

Let's use the most straightforward example possible: you have a contact form. Sending an email may take between one or two seconds, depending on your method.

What if you could delay this until the user receives your server's response?

That's precisely what [`dispatchAfterResponse()`](https://laravel.com/docs/queues#dispatching-after-the-response-is-sent-to-browser) does and this is one of my favorite tips:

```php
SendContactEmail::dispatchAfterResponse($input);
```

Or, if you prefer to dispatch jobs using anonymous functions:

```php
dispatch(function () {
    // Do something.
})->afterResponse();
```

### Use queues for even longer running tasks

Imagine you have to process images uploaded by your users.

If you process every one of them as soon as they're submitted, this will happen:
- Your server will burn;
- Your users will have to wait in front of a loading screen.

This isn't good UX, and we can change that.

Laravel has a [queue system](https://laravel.com/docs/queues) that will run all those tasks sequentially or with a limited amount of parallelism.

And, to easily manage your jobs through a user interface, [Laravel Horizon](https://laravel.com/docs/horizon) is what you should use.



![Use queues for even longer running tasks](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/103/conversions/Screenshot_2023-01-24_at_13.14.33_awtguk-medium.jpg)

### Lazily refresh your database before each test

When you can get away with fake data in your local environment, the best thing to do is to test against a fresh database every time you run a test.

You can use the `Illuminate\Foundation\Testing\LazilyRefreshDatabase` trait in your *tests/TestCase.php*.

There's also a `RefreshDatabase` trait, but the lazy one is more efficient, as migrations for unused tables won't be ran during testing.

### Make use of factories to help you with fake data and tests

[Factories](https://laravel.com/docs/eloquent-factories) make testing way more manageable.

You can create one using the `php artisan make:factory PostFactory` command and add random fake data to every column like so:

```php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition() : array
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

Factories create all the resources you need when writing tests.

Here's one in action:

```php
public function test_it_shows_a_given_post()
{
    $post = Post::factory()->create();

    $this
        ->get(route('posts.show', $post))
        ->assertOk();
}
```

### Test against the production stack whenever it's possible

When running your web application in production, you probably use something other than SQLite, like MySQL. Or the array cache driver instead of Redis.

Then, why are you not using them when running your tests too? There could be bugs only present with these, and tests are supposed to help you cache before they happen in production.

I'm convinced that reliability and accuracy are more important than speed of execution in this context.

### Use database transactions to rollback changes after each test

In one of my projects, I need to create a database filled with real data provided by CSV files on GitHub.

It takes time and I can't refresh my database before every test. It's way too slow.

So when my tests alter the data, I want to rollback the changes to keep the database in its initial state. You can do so by using the `Illuminate\Foundation\Testing\DatabaseTransactions` trait in your base test case class (*tests/TestCase.php*).

### Don't waste API calls, use mocks

In Laravel, mocks can be used to avoid wasting API calls while testing and being hit with rate limit errors.

Let's say we are working on a project using Twitter's API.

In our container, we have a `Client` class used to call it.

While running our test suite, we want to avoid unecessary calls to the real thing and the best way to do it is to swap our client in the container by a mock.

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

[Learn all about mocking](https://laravel.com/docs/mocking) on Laravel's documentation.

### Prevent stray HTTP requests to identify slow tests

Here's a great tip if you want to make sure that all HTTP requests made during your tests are fake, you can use the `Http::preventStrayRequests()` method from the HTTP Facade.

It will cause an exception to be thrown if any HTTP requests that do not have a corresponding fake response is executed.

You can use this method in an individual test or for your entire test suite.

```php
Http::preventStrayRequests();
```

### Don't track your .env file

Your *.env* file contains sensitive information. 

Please, **don't track it!**

Make sure it's included in your *.gitignore*.

Most of the time, data leaks are inside jobs.

**A password manager is a better solution for sharing credentials.**

If you want your team members to have access to a curated set of sensitive information, use a password manager with a proven track record of rock-solid security.

### Don't track your compiled CSS and JavaScript

Your CSS and JavaScript are generated using originals in *resources/css* and *resource/js*.

When deploying into production, you either compile them on the server or you create an artifact before.

Especially for people still using Laravel Mix, I recommend to stop tracking them.

It's quite annoying that every time you change something, a new *public/css/app.css* or *public/js/app.js* is generated and need to be commited.

It only takes two lines in your *.gitignore* to stop this:

```
public/css
public/js
```

---

**I can provide more guidance tailored to your codebase if you [book a call](/consulting) with me.**
