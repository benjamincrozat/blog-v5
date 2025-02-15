---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/5/data_stptpp.jpg
Title: "Laravel migrations: a simplified and step by step guide"
Description: "Migrations are essential in any Laravel app using a database. I will tell you what they are, why you should use them and how you can generate them."
Published at: 2022-09-12
Modified at: 2024-02-02
Categories: laravel
---

## What are migrations in Laravel

**In Laravel, migrations are a way to manage and apply changes to your database schema.** See them like a phpMyAdmin, but with code instead of a user interface. **Migrations also allow you to keep all your team members and environments in sync.**

In theory, you could start working on a new project, clone it onto your machine, and run `php artisan migrate` to generate a fresh and up to date database. Why? Because since migrations are based on code, they also are versioned just like the rest of the project.

Interesting, right? Let's learn more about migrations!

## Laravel's make:migration command

### Basic usage

Creating a migration can be done thanks to Artisan with the command below:

```bash
php artisan make:migration CreatePostsTable
```

1. Write the migration's name in PascalCase.
2. Begin with the "Create" prefix.
3. Continue with the desired table's name.
4. End with the "Table" suffix.
5. Artisan will create a new file and convert its name to snake_case (making the name more readable).
6. A timestamp will be added as a prefix.

```
INFO Created migration [2022_09_12_142156_create_posts_table]. 
```

A migration looks like this:

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // These are the columns you want to add to your table.
            $table->string('title');
            $table->text('content');

            // These are the "created_at" and "updated_at" columns.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
```

But there's more. Did you know you can pass multiple parameters?

```
php artisan make: migration

Options:
 --create[=CREATE] The table to be created
 --table[=TABLE] The table to migrate
 --path[=PATH] The location where the migration file should be created
 --realpath Indicate any provided migration file paths are pre-resolved absolute paths
 --fullpath Output the full path of the migration
```

Let's see how to use them and why.

### Create a migration with the --create option

The `--create` option tells Artisan to use something other than the table name it inferred from the migration's name. For instance, it could be helpful if you use another language from tables' names.

```bash
php artisan make:migration CreatePostsTable --create=billets
```

### Create a migration with the --table option

The `--table` option tells Artisan we don't need to create a new table but rather update an existing one. If you don't follow Laravel's conventions for naming your migrations, this is the option you need.

```bash
php artisan make:migration Whatever --table=posts
```

### Create a migration with its model

What I love about Artisan is the possibility of effortlessly creating a model with its migration. For that, we need to use another command, though.

```bash
php artisan make:model Post --migration
```

You can even use the shorthand option for the migration:

```bash
php artisan make:model Post -m 
```

And if you look at the help, you will appreciate what Artisan can do for you even more.

```
php artisan make:model -h

Description:
 Create a new Eloquent model class

Usage:
 make:model [options] [--] <name>

Arguments:
 name The name of the class

Options:
 -a, --all Generate a migration, seeder, factory, policy, resource controller, and form request classes for the model
 -c, --controller Create a new controller for the model
 -f, --factory Create a new factory for the model
 --force Create the class even if the model already exists
 -m, --migration Create a new migration file for the model
 --morph-pivot Indicates if the generated model should be a custom polymorphic intermediate table model
 --policy Create a new policy for the model
 -s, --seed Create a new seeder for the model
 -p, --pivot Indicates if the generated model should be a custom intermediate table model
 -r, --resource Indicates if the generated controller should be a resource controller
 --api Indicates if the generated controller should be an API resource controller
 -R, --requests Create new form request classes and use them in the resource controller
 --test Generate an accompanying PHPUnit test for the model
 --pest Generate an accompanying Pest test for the model
```

## Use the migrate command to run your newest migrations

To migrate your database, use the `php artisan migrate` command. 

```
INFO  Running migrations.  

2014_10_12_000000_create_users_table ................................................................................................ 4ms DONE
2014_10_12_100000_create_password_resets_table ...................................................................................... 1ms DONE
2018_01_01_000000_create_action_events_table ........................................................................................ 7ms DONE
2019_05_10_000000_add_fields_to_action_events_table ................................................................................. 1ms DONE
2019_08_19_000000_create_failed_jobs_table .......................................................................................... 1ms DONE
```

## Wipe out your database using migrate:fresh

The `php artisan migrate:fresh` command will wipe out your database before migrating.

```
Dropping all tables ................................................................................................................. 7ms DONE

INFO  Preparing database.  

Creating migration table ............................................................................................................ 3ms DONE

INFO  Running migrations.  

2014_10_12_000000_create_users_table ................................................................................................ 2ms DONE
2014_10_12_100000_create_password_resets_table ...................................................................................... 1ms DONE
2018_01_01_000000_create_action_events_table ........................................................................................ 6ms DONE
2019_05_10_000000_add_fields_to_action_events_table ................................................................................. 1ms DONE
2019_08_19_000000_create_failed_jobs_table .......................................................................................... 1ms DONE
```

This command won't work in production to prevent disasters. 😬

## Roll back migrations when something goes wrong

Roll back any change using the `php artisan migrate:rollback` command. As you can see below, migrations are rolled back in the inverse order.

```
INFO  Rolling back migrations.  

2019_08_19_000000_create_failed_jobs_table .......................................................................................... 1ms DONE
2019_05_10_000000_add_fields_to_action_events_table ................................................................................. 8ms DONE
2018_01_01_000000_create_action_events_table ........................................................................................ 1ms DONE
2014_10_12_100000_create_password_resets_table ...................................................................................... 1ms DONE
2014_10_12_000000_create_users_table ................................................................................................ 1ms DONE
```

So make sure to use the `down()` method correctly.

The `down()` method must do the opposite of the `up()` method.

```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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

## Conclusion

You now have all the skills to keep leveling up with migrations. Why don't you learn in the official documentation about all the [available methods from the Blueprint class](https://laravel.com/docs/migrations#available-column-types)?
