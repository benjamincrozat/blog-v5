---
id: "01KKVD1J18153MR130SA589FS9"
title: "How to use subqueries in Laravel with real examples"
slug: "laravel-subquery"
author: "benjamincrozat"
description: "Learn how to write Laravel subqueries with practical examples for addSelect(), where subqueries, and joinSub() in Eloquent and the query builder."
categories:
  - "laravel"
published_at: 2026-03-16T13:23:23+00:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/laravel-subquery.png"
sponsored_at: null
---
## Introduction

**A Laravel subquery is just a query nested inside another query.**

In practice, there are three shapes you will use most often:

- add a subquery to the `select` list
- compare a value against a subquery in `where()`
- join against a subquery with `joinSub()`

That is enough to cover most real-world cases without dropping down to raw SQL.

This guide focuses on those three patterns with concrete examples you can actually reuse.

## When to use a subquery in Laravel

A subquery is useful when the main query depends on a smaller query result.

Typical examples:

- show each destination with its latest flight name
- fetch users whose amount is below the average
- join users to a derived table like “latest published posts per user”

If a normal relationship, join, or aggregate can express the same thing more clearly, prefer that. Subqueries are powerful, but readability still matters.

## Add a subquery to the select list

Laravel supports subquery selects directly in Eloquent with `addSelect()`.

This is a good fit when you want one extra derived column on each row.

Example: get destinations with the name of their last arriving flight.

```php
use App\Models\Destination;
use App\Models\Flight;

$destinations = Destination::query()
    ->addSelect([
        'last_flight' => Flight::query()
            ->select('name')
            ->whereColumn('destination_id', 'destinations.id')
            ->orderByDesc('arrived_at')
            ->limit(1),
    ])
    ->get();
```

That gives each destination row a `last_flight` attribute without writing raw SQL.

Why this pattern works well:

- the intent stays close to the Eloquent model
- the relationship between the outer and inner query is explicit through `whereColumn()`
- you avoid a manual join when you only need one derived value

## Use a subquery inside `where()`

Sometimes you want to compare a column against a value returned by another query.

Laravel supports that too.

Example: get incomes that are below the average amount:

```php
use Illuminate\Support\Facades\DB;

$incomes = DB::table('incomes')
    ->where('amount', '<', function ($query) {
        $query->selectRaw('avg(amount)')
            ->from('incomes');
    })
    ->get();
```

That is a clean way to express “amount < average amount” without leaving the query builder.

You can also use a subquery to compare against a value coming from a related table.

Example: get users whose most recent membership is of a certain type:

```php
use App\Models\User;
use Illuminate\Database\Query\Builder;

$users = User::query()
    ->where(function (Builder $query) {
        $query->select('type')
            ->from('membership')
            ->whereColumn('membership.user_id', 'users.id')
            ->orderByDesc('membership.start_date')
            ->limit(1);
    }, 'Pro')
    ->get();
```

This is one of the most useful Laravel subquery patterns because it keeps the logic readable while still expressing a “latest related row” condition.

## Join against a subquery with `joinSub()`

Use `joinSub()` when the subquery should behave like a derived table in your `FROM` clause.

Example: join each user with the timestamp of their latest published post.

```php
use Illuminate\Support\Facades\DB;

$latestPosts = DB::table('posts')
    ->select('user_id', DB::raw('MAX(created_at) as last_post_created_at'))
    ->where('is_published', true)
    ->groupBy('user_id');

$users = DB::table('users')
    ->joinSub($latestPosts, 'latest_posts', function ($join) {
        $join->on('users.id', '=', 'latest_posts.user_id');
    })
    ->get();
```

This is the right tool when you need grouped or aggregated derived data and then want to join it back to a main table.

Laravel also supports `leftJoinSub()` and `rightJoinSub()` for the same pattern when the join direction matters.

## A practical mental model

If you are not sure which approach to use, this rule usually works:

- use `addSelect()` when you want one derived column per row
- use a subquery in `where()` when the main filter depends on another query
- use `joinSub()` when the derived data should behave like a table

That keeps the code easier to reason about than blindly reaching for one pattern everywhere.

## Common pitfalls

### Forgetting `whereColumn()` in correlated subqueries

This is the mistake that breaks a lot of “latest related row” examples.

If the inner query needs the current row from the outer query, use `whereColumn()`:

```php
->whereColumn('destination_id', 'destinations.id')
```

Without that, the subquery is no longer tied to the outer row correctly.

### Using a subquery when a simple join would be clearer

Subqueries are not automatically better. If a plain join or relationship makes the query easier to understand, use that instead.

### Hiding too much SQL logic in `DB::raw()`

A small aggregate like `avg(amount)` or `MAX(created_at)` is reasonable. If the whole query turns into raw SQL fragments, readability drops fast.

## A before-and-after example

Here is the kind of shift that makes Laravel subqueries worthwhile.

Without a subquery, developers often fall back to multiple queries or post-processing in PHP.

With a subquery select:

```php
$destinations = Destination::query()
    ->addSelect([
        'last_flight' => Flight::query()
            ->select('name')
            ->whereColumn('destination_id', 'destinations.id')
            ->latest('arrived_at')
            ->limit(1),
    ])
    ->get();
```

That keeps the logic in the database where it belongs and usually makes the code easier to follow than “query first, loop later, enrich in PHP.”

## When to reach for raw SQL instead

If the query becomes hard to explain in Laravel code, it may be a sign that raw SQL or a database view would be clearer.

That is not a failure. The goal is not to use the fanciest builder API. The goal is to keep the query correct and maintainable.

## Conclusion

Laravel subqueries are useful once you separate the three main jobs:

- `addSelect()` for derived columns
- `where()` subqueries for derived filters
- `joinSub()` for derived tables

That is the practical core. Most subquery work in Laravel is one of those three patterns.

If you are still tightening up query-builder work after this, these are the next reads I would keep open:

- [Get the where-clause side of the query right first](/laravel-query-builder-where-clauses)
- [Sort query results cleanly when the SQL starts to sprawl](/laravel-order-by)
- [Keep writes safe when the query logic feeds a multi-step change](/database-transactions-laravel)
