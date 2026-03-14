---
id: "01KKEW27D7086V9HV2YSPF4CY7"
title: "Laravel orderBy(): examples for asc, desc, and more"
slug: "laravel-order-by"
author: "benjamincrozat"
description: "Use Laravel orderBy() to sort Eloquent results by one or more columns, switch to orderByDesc(), reach for orderByRaw() when needed, and reset sorting with reorder()."
categories:
  - "laravel"
published_at: 2023-09-09T00:00:00+02:00
modified_at: 2026-03-14T10:04:32Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/rgz5ybN0xhHeW42.jpg"
sponsored_at: null
---
## How to use Laravel orderBy()

**Use Laravel's `orderBy()` method to sort query results by a column in ascending or descending order.**

Pass the column name first, then the direction if you need descending order. Chain more `orderBy()` calls for tie-breakers, use `orderByDesc()` for readability, and reach for `reorder()` when you need to replace an earlier sort.

If you need `orderByDesc()`, multiple-column sorting, or a quick way to replace an existing order, this guide covers those too. Here's the foundation of `orderBy()`:

```php
$users = User::query()
    ->orderBy('name', 'desc')
    ->get();
```

In this snippet, Eloquent fetches users and sorts them by `name` in descending order.

Its parameters are:

* **The column's name**.
* **The order direction**: Either `asc` (the default value) for ascending or `desc` for descending.

[See the official docs for ordering queries.](https://laravel.com/docs/11.x/queries#ordering)

## The orderByDesc() method

If you want to sort your results in descending order, you can also use the `orderByDesc()` method, which is a shortcut for `orderBy('column', 'desc')`:

```php
$users = User::query()
    ->orderByDesc('name')
    ->get();
```

It's all in the details!

## Multi-column sorting using orderBy()

What if you want to sort by multiple columns? Simple. Just chain multiple `orderBy()` methods:

```php
$users = User::query()
    ->orderBy('name', 'desc')
    ->orderBy('email', 'asc')
    ->get();
```

This way, Eloquent sorts users by their names first. If two or more users have the same name, it then sorts those users by their email in ascending order.

I actually learned that only after years of SQL and Laravel experience.

## Getting fancy with orderByRaw()

When you need a more complex sorting mechanism, Laravel's got you covered with `orderByRaw()`:

```php
$orders = User::query()
    ->orderByRaw('updated_at - created_at DESC')
    ->get();
```

This advanced method lets you sort the results based on the difference between the `updated_at` and `created_at` timestamps. Handy, right?
**If you need to use user input, always use bindings to prevent SQL injection:**

```php
$query->orderByRaw('some_column > ?', [$value]);
```

[See the official docs for raw orderings.](https://laravel.com/docs/11.x/queries#raw-orderings)

> *Heads-up:* `updated_at - created_at` works in MySQL and Postgres, but not in SQL Server. On SQL Server, use `DATEDIFF(SECOND, created_at, updated_at)`.

## Use reorder() to unorder what's already been ordered

If you need to undo the ordering of a query you are building based on some condition, you can use the `reorder()` method:

```php
$ordered = User::query()->orderBy('name');

$unordered = $ordered->reorder()->get();
```

And if you wish to reset and apply a completely new ordering without calling `orderBy()` again:

```php
$ordered = User::query()->orderBy('name');

$reorderedByEmail = $ordered->reorder('email', 'desc')->get();
```

I'll never get bored of Laravel's convenience!
[Docs: Removing existing orderings](https://laravel.com/docs/11.x/queries#removing-existing-orderings)

**See also:**

* [`latest()`](https://laravel.com/docs/11.x/queries#retrieving-latest-or-oldest-records) / [`oldest()`](https://laravel.com/docs/11.x/queries#retrieving-latest-or-oldest-records): terser helpers that default to `created_at` but can take any column.
* [`inRandomOrder()`](https://laravel.com/docs/11.x/queries#random-ordering): for that random "featured" user or contest winner logic.

If you are cleaning up query logic and want the rest of that toolbox to feel just as clear, these are the next reads I would open:

- [Write where clauses with fewer query-builder surprises](/laravel-query-builder-where-clauses)
- [Filter results with whereIn() without tripping over the basics](/laravel-query-builder-wherein)
- [Use database transactions when partial writes would hurt](/database-transactions-laravel)
- [Write validation rules with less guesswork](/laravel-validation)
