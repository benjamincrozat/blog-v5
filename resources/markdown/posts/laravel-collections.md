---
id: "01KKEW27BY0J3WYK72J6M2GVDV"
title: "Laravel Collections: practical methods and examples"
slug: "laravel-collections"
author: "benjamincrozat"
description: "Learn Laravel Collections with practical examples for collect(), map(), filter(), each(), when(), only(), except(), and other methods you will use often."
categories:
  - "laravel"
published_at: 2022-11-09T00:00:00+01:00
modified_at: 2026-03-14T10:12:17Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/EXDP21mIkAUEsEP.png"
sponsored_at: null
---
## Introduction to Laravel Collections

[Laravel Collections](https://laravel.com/docs/collections) are a powerful tool for working with arrays. They wrap native PHP functions, add helpful helpers, and give you a fluent API.

If you want the short version, Laravel Collections give you chainable methods like `map()`, `filter()`, `each()`, and `when()` so everyday array work reads more clearly than raw loops.

In general, collections behave immutably: most methods return a new collection instead of changing the current one. Some methods do mutate the instance (for example, `transform`, `push`, `pop`, `shift`, `put`, `prepend`). When you want a non-mutating alternative, reach for `map`. See the docs for [`transform`](https://laravel.com/docs/11.x/collections#method-transform) and [`map`](https://laravel.com/docs/11.x/collections#method-map).

## Laravel Collection methods I always use

### Create a collection from an array

To create a collection from an array, use the `collect()` helper.

```php
$collection = collect(); // Empty collection.

$collection = collect([1, 2, 3]); // Collection from an array.
```

You can also use the static constructor `Collection::make(...)` from `Illuminate\Support\Collection`:

```php
use Illuminate\Support\Collection;

$collection = Collection::make([1, 2, 3]); // Static constructor.
```

### Transform a collection back into an array

To turn a collection into an array, call `toArray()`.

```php
$collection = collect([1, 2, 3]);

$array = $collection->toArray();
```

This isn’t necessary most of the time, but it can come in handy.

### Use collections in foreach loops

Collections work in `foreach` just like arrays.

```php
foreach ($collection as $item) {
    // ...
}
```

You can also access the key:

```php
foreach ($collection as $key => $value) {
    // ...
}
```

They are iterable because `Collection` implements PHP’s [`IteratorAggregate`](https://www.php.net/manual/en/class.iteratoraggregate.php).

### Prefer each() over foreach when you want fluency

The most basic collection use is replacing a `foreach` with `each()`.

```php
$collection = collect(['Foo', 'Bar', 'Baz']);

$collection->each(function ($value, $key) {
    // ...
});
```

Returning `false` from the callback stops the iteration early. See [`each`](https://laravel.com/docs/11.x/collections#method-each).

### Merge collections correctly

Merging two collections is simple with `merge()`.

```php
$numbers = collect([10, 20])->merge(collect([30]));
// [10, 20, 30]

$settings = collect(['theme' => 'light'])->merge(collect(['theme' => 'dark']));
// ['theme' => 'dark'] (string keys overwrite)
```

Behavior note: `merge` appends numeric-keyed values and overwrites on duplicate string keys. See [`merge`](https://laravel.com/docs/11.x/collections#method-merge).

### Use filter() to clean up

It’s common to build a temporary array inside a loop. Collections let you skip that with `filter()`:

```php
return $bar->filter(function ($baz) {
    return $baz->something();
});
```

`filter()` preserves the original keys. If order or JSON output matters, chain `->values()` to reset the indices:

```php
return $bar->filter(fn ($baz) => $baz->something())
           ->values();
```

### Use sum(), avg(), min(), and max() for numbers

Collections include handy math helpers:

```php
$numbers = collect([1, 2, 3, 4, 5]);

$numbers->sum(); // 15
$numbers->avg(); // 3  (average() is an alias)
$numbers->min(); // 1
$numbers->max(); // 5
```

See [`avg`](https://laravel.com/docs/11.x/collections#method-avg), [`sum`](https://laravel.com/docs/11.x/collections#method-sum), [`min`](https://laravel.com/docs/11.x/collections#method-min), and [`max`](https://laravel.com/docs/11.x/collections#method-max).

### Use higher order messages for concise iteration

[Higher order messages](https://laravel.com/docs/11.x/collections#higher-order-messages) (HOM) make chains shorter by calling a method on each item.

```php
foreach (User::where('foo', 'bar')->get() as $user) {
    $user->notify(new SomeNotification);
}
```

Refactor with `each()`:

```php
User::where('foo', 'bar')
    ->get()
    ->each(function (User $user) {
        $user->notify(new SomeNotification);
    });
```

Then use HOM to drop the callback:

```php
User::where('foo', 'bar')
    ->get()
    ->each
    ->notify(new SomeNotification);
```

### Keep or drop keys with only() and except()

Need specific keys?

```php
$original = [
    'foo' => 'foo',
    'bar' => 'bar',
    'baz' => 'baz',
];

$new = collect($original)->only(['foo', 'bar']);
```

Need to remove some?

```php
$new = collect($original)->except(['foo', 'bar']);
```

See [`only`](https://laravel.com/docs/11.x/collections#method-only) and [`except`](https://laravel.com/docs/11.x/collections#method-except).

### Use dump() and dd() on collections

You can call these right on the collection:

```php
// Before:
dd($collection->where('foo', 'bar'));

// After:
$collection->where('foo', 'bar')->dd();
```

`dd()` dumps and halts; `dump()` dumps and lets code continue. See [`dd`](https://laravel.com/docs/11.x/collections#method-dd) and [`dump`](https://laravel.com/docs/11.x/collections#method-dump).

### Pick random items with random()

```php
$one = collect(['Foo', 'Bar', 'Baz'])->random();
// Returns a single item.

$two = collect(['a', 'b', 'c'])->random(2);
// Returns a Collection of 2 items.
```

Requesting more items than exist throws `InvalidArgumentException`. See [`random`](https://laravel.com/docs/11.x/collections#method-random).

### Replace temporary arrays with map()

This is a common pattern:

```php
namespace App\Twitter;

use App\Twitter\Tweet;
use Illuminate\Support\Facades\Http;

class Client
{
    public function tweets()
    {
        $tweets = Http::get('https://api.twitter.com/2/users/me')
            ->json('tweets');

        $tmp = [];

        foreach ($tweets as $tweet) {
            $tmp[] = new Tweet(...$tweet);
        }

        return $tmp;
    }
}
```

With collections, you can skip the temporary variable:

```php
namespace App\Twitter;

use App\Twitter\Tweet;
use Illuminate\Support\Facades\Http;

class Client
{
    public function tweets()
    {
        return Http::get('https://api.twitter.com/2/users/me')
            ->collect('tweets') // Response::collect exists on HTTP client responses
            ->map(fn (array $value) => new Tweet(...$value))
            ->all(); // Keep returning an array
    }
}
```

When to use: prefer `map()` when you want a new collection; `transform()` mutates the existing one.

See the HTTP client’s [`collect` on responses](https://laravel.com/docs/12.x/http-client#collections).

### Use isEmpty() and isNotEmpty() for readability

```php
if ($collection->isEmpty()) {
    // Do something if the collection is empty.
}
```

```php
if ($collection->isNotEmpty()) {
    // Do something if the collection is not empty.
}
```

### Use when() and unless() to conditionally transform a collection

Collections use the `Illuminate\Support\Traits\Conditionable` trait, which adds `when()` and `unless()` for fluent conditional logic.

```php
$models = Model::query()->when(
    $something,
    fn ($query) => $query->where('something', true),
    fn ($query) => $query->where('something_else', true),
)->get();
```

This trait also powers other parts of the framework (for example, Eloquent factories, Logger, HTTP `PendingRequest`, and Carbon), so you’ll see the same pattern elsewhere. See [`when` / `unless` on collections](https://laravel.com/docs/11.x/collections#method-when).

### Extend collections with your own methods

Along with other Laravel classes, collections are “macroable,” so you can extend them at runtime. Register macros in a service provider’s `boot` method.

*app/Providers/AppServiceProvider.php*:

```php
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Collection::macro('pluralize', function () {
            return $this->map(function ($value) {
                return Str::plural($value);
            });
        });
    }
}
```

Now your custom method is available anywhere:

```php
$collection = collect(['apple', 'banana', 'strawberry']);

$collection->pluralize();
// ['apples', 'bananas', 'strawberries']
```

## Conclusion

- Prefer non-mutating methods like `map()`; know when `transform()` changes the instance.
- `each()` can replace `foreach` and stops early when the callback returns `false`.
- `only()`/`except()`, `isEmpty()`/`isNotEmpty()`, and `when()`/`unless()` make intent clear.
- Use higher order methods (`->each->notify(...)`) for clean, readable chains.
- Remember gotchas: `filter()` preserves keys; `merge()` overwrites string keys and appends numeric keys.

Next steps: explore [Eloquent collections](https://laravel.com/docs/eloquent-collections) and [lazy collections](https://laravel.com/docs/collections#lazy-collections) to work efficiently with large datasets.

If you are still thinking about "15 Laravel Collections tips to refactor your codebase", open these next:

- [Stop foreach from blowing up on the wrong input](/invalid-argument-supplied-for-foreach)
- [Tighten the API decisions most Laravel apps get wrong](/laravel-restful-api-best-practices)
- [See the biggest Laravel 11 changes in one pass](/laravel-11)
- [Validate nested arrays in Laravel without losing your mind](/laravel-array-validation)
- [Sort Eloquent results cleanly with orderBy](/laravel-order-by)
- [Look back at how Laravel changed after 5.8](/laravel-retrospective)
- [Inspect arrays without wrecking your output](/php-laravel-print-array)
- [See what Laravel 12 changed before you adopt it](/laravel-12)
- [Pick the right Eloquent create helper before duplicates sneak in](/laravel-firstorcreate-firstornew-createorfirst-updateorcreate-updateorinsert)
- [See the Laravel 10 changes that matter in real projects](/laravel-10)
