---
id: "01KKVHFWJ8A10XRN0TZ87YRB22"
title: "How to seed realistic data in Laravel with seeders and factories"
slug: "laravel-seeder"
author: "benjamincrozat"
description: "Learn when to use Laravel seeders, when to use factories, and how to generate realistic sample data for local development and tests without making your database messy."
categories:
  - "laravel"
published_at: 2026-03-16T14:41:07+00:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/laravel-seeder.png"
sponsored_at: null
---
## Introduction

**Laravel seeders and factories solve related problems, but they are not the same tool.**

Use this rule:

- use **seeders** to define what data should exist
- use **factories** to define how fake model data should be generated

That distinction makes the whole workflow easier to reason about.

If you only remember one practical pattern from this article, make it this:

> Use seeders for fixed reference data, and use factories inside seeders when you need lots of realistic records.

## Seeder vs factory in Laravel

Here is the cleanest mental model:

| Tool | Best for | Example |
| --- | --- | --- |
| seeder | known data that should exist | roles, plans, feature flags |
| factory | generating many model instances | users, posts, comments, orders |

A seeder decides **what** gets inserted.

A factory decides **how** each fake record should look.

You will often use both together.

## Create a seeder

Generate one with Artisan:

```bash
php artisan make:seeder RolesTableSeeder
```

Laravel creates a class in `database/seeders`.

Example:

```php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'editor', 'author'] as $name) {
            Role::query()->firstOrCreate(['name' => $name]);
        }
    }
}
```

This is a good seeder because the data is stable and the `firstOrCreate()` call makes it idempotent.

## Run seeders

Use the standard seeding command:

```bash
php artisan db:seed
```

To run one specific seeder:

```bash
php artisan db:seed --class=RolesTableSeeder
```

And when you want to rebuild the database locally:

```bash
php artisan migrate:fresh --seed
```

That command is especially useful during local development because it rebuilds the schema and then repopulates the data in one go.

If you need a refresher on the schema side before seeding it, [this Laravel migrations guide](/laravel-migrations) is the right companion.

## Use `DatabaseSeeder` as the entry point

Laravel’s `DatabaseSeeder` class is where you usually organize the full seeding flow.

Example:

```php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesTableSeeder::class,
            DemoContentSeeder::class,
        ]);
    }
}
```

That keeps the main seeding path explicit instead of scattering it across unrelated files.

## Create a factory

Factories are for generating lots of believable model data.

Laravel can generate one for you:

```bash
php artisan make:factory PostFactory --model=Post
```

Example:

```php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'slug' => fake()->slug(),
            'excerpt' => fake()->paragraph(),
            'body' => fake()->paragraphs(5, true),
            'published_at' => now(),
        ];
    }
}
```

This factory does not decide when posts should exist. It only defines how a fake post should be built.

## Use factories inside seeders

This is the pattern most Laravel apps actually need.

Example:

```php
namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoContentSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()
            ->count(10)
            ->has(Post::factory()->count(5))
            ->create();
    }
}
```

That gives you:

- one seeder controlling the big picture
- factories generating the volume and variation

This is usually the right balance between structure and realism.

## Seed realistic data instead of random garbage

A common mistake is generating data that looks fake enough to be useless.

A few better defaults:

- use factory states for meaningful variations
- keep relationships believable
- create a small amount of fixed reference data first
- avoid generating thousands of records unless you actually need them

Example with factory states:

```php
User::factory()->count(5)->create();
User::factory()->count(2)->unverified()->create();
User::factory()->count(1)->admin()->create();
```

That makes local testing more useful than “30 random users with random everything.”

## Use seeders for fixed reference data

These are good seeder candidates:

- roles
- plans
- countries
- permissions
- app settings defaults

These are usually bad seeder candidates:

- thousands of fake blog posts nobody needs
- random records with unrealistic relationships
- data that is better created directly inside a test

If the data is part of the application’s baseline behavior, seed it.

If the data only exists to support a specific test case, factories inside the test are often cleaner.

## Seed data for tests

For automated tests, factories are usually the first tool to reach for.

Example:

```php
$user = User::factory()->create();
$posts = Post::factory()->count(3)->for($user)->create();
```

That keeps the test setup close to the test itself.

Seeders can still be useful in tests when a whole fixed baseline is required, but factories are more flexible for isolated scenarios.

## Make seeders idempotent when the data is meant to be rerun

If a seeder can run more than once, write it so reruns do not create junk.

Good options:

- `firstOrCreate()`
- `updateOrCreate()`
- truncating only in explicitly local/demo workflows

Example:

```php
Role::query()->updateOrCreate(
    ['name' => 'admin'],
    ['label' => 'Administrator'],
);
```

That is safer than blindly calling `create()` every time.

## Common pitfalls

### Putting fixed data in factories

Factories are not the right place for permanent reference data like roles or plans. That logic belongs in seeders.

### Putting all fake data directly in seeders

If the seeder is manually building dozens of fake models in arrays, it usually means a factory should exist.

### Using `migrate:fresh --seed` like a production tool

This is a local-development convenience, not something to casually run against live data.

### Forgetting relationships

Random orphaned records are not realistic sample data. If your app depends on relationships, the seeded data should reflect that.

## A practical default workflow

If I were setting up a normal Laravel app today, the baseline would look like this:

1. Create seeders for fixed lookup data like roles and plans.
2. Create factories for user-generated or high-volume models.
3. Call the seeders from `DatabaseSeeder`.
4. Use factories inside seeders for realistic demo content.
5. Use factories directly in tests unless a shared baseline is genuinely useful.

That is enough structure for most apps without making the seeding layer feel overengineered.

## Conclusion

Laravel seeders and factories work best together when each has a clear job. Seeders define the overall dataset your app needs. Factories generate believable model instances quickly.

Keep seeders for stable reference data, use factories for flexible model generation, and combine the two when you want realistic sample data for local development.

If you are still tightening the data side of a Laravel app after this, these are the next reads I would keep open:

- [Get the migration layer right before you seed anything](/laravel-migrations)
- [Validate nested request data once the sample data gets complex](/laravel-array-validation)
- [Keep multi-step writes safe when generated data feeds real workflows](/database-transactions-laravel)
