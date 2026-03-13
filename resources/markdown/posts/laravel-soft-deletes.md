---
id: "01KKEW27DYEEZ3DJW5C45AWWXY"
title: "How to use soft deletes in Laravel"
slug: "laravel-soft-deletes"
author: "benjamincrozat"
description: "Learn how Laravel soft deletes work, how to query trashed models, restore them, test them, and prune old records."
categories:
  - "laravel"
published_at: 2022-11-23T00:00:00+01:00
modified_at: 2026-03-13T15:40:00Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBV0EQ9ZMY8VWC8Q53VAEV7T.jpeg"
sponsored_at: null
---
## What are soft deletes?

**Laravel soft deletes mark a model as deleted without removing its row from the database.**

Instead, Eloquent writes a timestamp to `deleted_at` and excludes that row from normal queries.

That gives you a restore path instead of an immediate hard delete.

## How to set up soft deletes

Laravel needs two things:

1. a `deleted_at` column
2. the `SoftDeletes` trait on the model

First, add the column in your migration:

```php
public function up()
{
	Schema::table('posts', function (Blueprint $table) {
		$table->softDeletes(); // [tl! ++]
	});
}
```

If you roll the migration back, remove it with `dropSoftDeletes()`:

```php
public function down()
{
    Schema::table('posts', function (Blueprint $table) {
		$table->dropSoftDeletes(); // [tl! ++]
	});
}
```

Then enable soft deletes on the model:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // [tl! ++]
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes; // [tl! ++]
}
```

Laravel also casts `deleted_at` to a date object for you.

## Delete, restore, and permanently remove models

To soft delete a model, call `delete()` as usual:

```php
$post->delete();
```

To check whether a model has been soft deleted, use `trashed()`:

```php
if ($post->trashed()) {
    //
}
```

To restore it:

```php
$post->restore();
```

To permanently delete a loaded model:

```php
$post->forceDelete();
```

Laravel also provides `forceDestroy()` when you want to permanently remove one or more soft-deletable models by ID:

```php
Post::forceDestroy(1);
Post::forceDestroy([1, 2, 3]);
```

## Querying soft deleted models

Soft-deleted rows are excluded from normal Eloquent queries. These helpers let you opt back in:

```php
Post::withTrashed()->get();
```

You can even query trashed models only:

```php
Post::onlyTrashed()->get();
```

If you want to be explicit about excluding trashed rows, use `withoutTrashed()`:

```php
Post::withoutTrashed()->get();
```

## How to test for a soft delete

Laravel provides dedicated assertions for this:

```php
$this->assertSoftDeleted($post);
$this->assertNotSoftDeleted($post);
```

Here is a full example:

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

## How to clean up old soft deleted models

If soft-deleted rows should only stay around for a while, use Laravel's pruning system.

For example, this prunes posts that were soft deleted more than one month ago:

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Prunable, SoftDeletes;
  
    public function prunable(): Builder
	{
		return static::where('deleted_at', '<=', now()->subMonth());
	}
}
```

Schedule `model:prune` in `routes/console.php`:

```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('model:prune')->daily();
```

When a soft-deleted model matches the prunable query, Laravel permanently removes it with `forceDelete()`.

If you want pruning to run through mass-deletion queries for efficiency, use `MassPrunable` instead of `Prunable`.

If you are still thinking about what deleted should really mean in your data model, these are the Laravel reads I would open:

- [Write where clauses with fewer query-builder surprises](/laravel-query-builder-where-clauses)
- [Sort Eloquent results cleanly with orderBy](/laravel-order-by)
- [Filter results with whereIn() without tripping over the basics](/laravel-query-builder-wherein)
- [Work with Laravel migrations without second-guessing the basics](/laravel-migrations)
- [Make Eloquent models easier for your IDE to understand](/laravel-lift)
