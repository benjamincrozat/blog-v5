---
id: "01KKEW27CX8RCPAS0DE6DQRSXN"
title: "Laravel migrations: commands, examples, and safe deploys"
slug: "laravel-migrations"
author: "benjamincrozat"
description: "Use Laravel migrations to create and change tables, preview SQL, inspect status, deploy with an isolation lock, roll back, and build schema dumps."
categories:
  - "laravel"
published_at: 2022-09-11T22:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/tTXXRvK0zEcrJgK.jpg"
sponsored_at: null
---
Laravel migrations are version-controlled changes to your database schema. Create one with `make:migration`, review its `up()` and `down()` methods, then run it with `migrate`.

```bash
php artisan make:migration create_posts_table
php artisan migrate
```

## Laravel migration commands at a glance

| Job | Command |
| --- | --- |
| Create a migration | `php artisan make:migration create_posts_table` |
| Show migration status | `php artisan migrate:status` |
| Show only pending migrations | `php artisan migrate:status --pending` |
| Preview SQL without changing the database | `php artisan migrate --pretend` |
| Run pending migrations | `php artisan migrate` |
| Give each migration its own batch | `php artisan migrate --step` |
| Prevent several servers from migrating together | `php artisan migrate --isolated` |
| Roll back the latest batch | `php artisan migrate:rollback` |
| Roll back one migration | `php artisan migrate:rollback --step=1` |
| Roll back everything and migrate again | `php artisan migrate:refresh` |
| Drop every table and migrate again | `php artisan migrate:fresh` |
| Write the current schema to a dump | `php artisan schema:dump` |

I checked these commands against Laravel 13.25. Use `--help` on your installed Laravel version when maintaining an older project.

## Create a table migration

Laravel infers the table and operation from a clear snake-case name:

```bash
php artisan make:migration create_posts_table
```

The generated filename starts with a timestamp, which gives migrations their order. Define the change in `up()` and its practical reverse in `down()`:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
```

You do not need a PascalCase migration name. A descriptive name such as `create_posts_table` or `add_published_at_to_posts_table` helps Artisan generate the right stub and helps humans understand the change.

If the name cannot express the table cleanly, be explicit:

```bash
php artisan make:migration create_billets_table --create=billets
php artisan make:migration add_status_to_posts --table=posts
```

You can also create a model and migration together:

```bash
php artisan make:model Post --migration
```

## Change an existing table

Use `Schema::table()` rather than editing an old migration that has already run in another environment:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->index();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn('is_featured');
        });
    }
};
```

Editing an already-deployed migration makes a fresh database differ from an upgraded database. A new migration keeps both paths visible.

## Inspect and preview before running

Start with the status:

```bash
php artisan migrate:status --pending
```

Then ask Laravel to print the SQL without executing it:

```bash
php artisan migrate --pretend
```

`--pretend` is especially useful for spotting SQL that may lock a table, build a large index, or behave differently across engines. It does not measure the lock or replace testing against the same database engine and a realistic amount of data.

## Run migrations safely in production

Laravel asks for confirmation in production unless you pass `--force`:

```bash
php artisan migrate --force
```

If several application servers deploy at the same time, use an isolation lock:

```bash
php artisan migrate --force --isolated
```

`--isolated` needs a default cache store that supports atomic locks. It prevents two servers from running the migration command concurrently; it does not make every schema operation online or reversible.

My production checklist is short:

1. back up data when the change can destroy or rewrite it
2. run the migration on the same database engine in staging
3. inspect `migrate --pretend` output
4. check how the database handles locks and long index changes
5. deploy code that can tolerate the transition when zero downtime matters
6. run `migrate --force --isolated`
7. verify the schema and the affected application path

For risky changes, split the deploy into compatible steps: add the new column, deploy code that writes both shapes, backfill, switch reads, then remove the old column later.

## Roll back deliberately

This rolls back the latest migration batch:

```bash
php artisan migrate:rollback
```

This limits the rollback to one migration:

```bash
php artisan migrate:rollback --step=1
```

`down()` can reverse a schema operation, but it cannot magically restore deleted or transformed data. A rollback plan may need a backup, a forward-fix migration, or both.

Useful local reset commands are:

```bash
php artisan migrate:refresh --seed
php artisan migrate:fresh --seed
```

`migrate:refresh` rolls migrations back through their `down()` methods and runs them again. `migrate:fresh` skips that history, drops every table on the configured connection, and rebuilds the database.

Do not assume `migrate:fresh` is blocked in production. Laravel can run it there with `--force`. Never point it at a production or shared database unless dropping every table is the exact intended operation.

## Keep large projects fast with schema dumps

Once a project has years of old migrations, write the current schema to a file:

```bash
php artisan schema:dump
```

Laravel loads the dump first when it builds a database with no migration history, then runs newer migrations.

After the dump is reviewed and committed, `--prune` also deletes migrations represented by that schema:

```bash
php artisan schema:dump --prune
```

If tests use another connection, create its dump explicitly:

```bash
php artisan schema:dump --database=testing
```

The [Laravel 13 migration documentation](https://laravel.com/docs/13.x/migrations) is the source for column types, indexes, foreign keys, and database-specific details.

Related guides:

- [Use database transactions for application writes](/database-transactions-laravel)
- [Use everyday Artisan commands with more confidence](/laravel-artisan)
- [Write query-builder where clauses without surprises](/laravel-query-builder-where-clauses)
- [Audit the Laravel habits that keep projects maintainable](/laravel-best-practices)
