---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/21/6505016_kg7fdn.jpg
Title: "Soft delete models in Laravel: the definitive guide"
Description: "A soft delete in Laravel allows you to prevent mistakes by not removing sensitive data from your database right away."
Published at: 2022-11-23
Modified at: 2023-12-21
Categories: laravel
---

## What are soft deletes?

**Soft deletes allows developers to mark models as deleted without deleting them from the database.**

Imagine a `deleted_at` column in your database containing the date where your entry has been deleted.

Their main benefit is that you don't loose data anymore. You will always be able to restore it.

Obviously, there is a mechanism to help you clean up old soft deleted models that I'll show you later.

## How to set up soft deletes

Laravel requires you to take two easy steps to set up a soft delete.

First, specify that you need a column for soft deletion in your migration (I wrote a nice article about [migrations](https://benjamincrozat.com/laravel-migrations)). Once you run it, you'll see a new `deleted_at` column in your posts table.

```php
public function up()
{
	Schema::table('posts', function (Blueprint $table) {
        {+$table->softDeletes();+}
	});
}
```

In the `down()` method, you can remove the column using the `dropSoftDeletes()` method.

```php
public function down()
{
    Schema::table('posts', function (Blueprint $table) {
        {+$table->dropSoftDeletes();+}
	});
}
```

Then, in your model, import the `SoftDeletes` trait.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
{+use Illuminate\Database\Eloquent\SoftDeletes;+}
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    {+use HasFactory, SoftDeletes; +}
}
```

## How to perform a soft delete and check for it

To soft delete in Laravel, you don't have to change your habits. Use your model's `delete()` method just like before. The only difference will be that Laravel will add the current date and time inside the `deleted_at` column.

```php
$post->delete();
```

And if you want to check if a model has been soft deleted, I recommend the `trashed()` method instead of manually checking the `deleted_at` column.

```php
if ($post->trashed()) {
    //
}
```

## More helpers for soft deletes

Sometimes, you may need to include soft deleted models in your queries. The `withTrashed()` scope can help with that.

```php
Post::withTrashed()->get();
```

You can even query trashed models only:

```php
Post::onlyTrashed()->get();
```

Also, since the model is never really deleted, you can restore it at any moment using the `restore()` method. The `deleted_at` column will be back to `NULL`.

```php
$post->restore();
```

Finally, if you want to really remove a soft deletable model from the database definitely, use the `forceDelete()` method.

```php
$post->forceDelete();
```

## How to test for a soft delete

To test for a soft delete in Laravel, use the `assertSoftDeleted()` method Laravel provides.

Here's a basic example of how I'd go for it:

```php
public function test_it_soft_deletes_posts()
{
    $post = Post::factory()->create();
    
    $this
        ->deleteJson(route('posts.destroy', $post))
        ->assertNoContent();
        
    $this->assertSoftDeleted($post);
}
```

The inverse method exists as `assertNotSoftDeleted()`.

## How to clean up old soft deleted models

You can use the pruning mechanism Laravel offers to clean up old soft deleted models.

For example, import the `Prunable` trait inside your model and tell the framework to remove models that have been soft deleted since a month or more.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Prunable, SoftDeletes;
  
    public function prunable()
	{
		return static::where('deleted_a t', '<=', now()->subMonth());
	}
}
```

Don't forget to run the `model:prune` command with the scheduler. Add it to your *app/Console/Kernel.php*:

```php
protected function schedule(Schedule $schedule)
{
    {+$schedule->command('model:prune')->daily();+}
}
```

