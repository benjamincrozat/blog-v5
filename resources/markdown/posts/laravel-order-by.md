---
id: "01KKEW27D7086V9HV2YSPF4CY7"
title: "Laravel Eloquent: sort query results using orderBy()"
slug: "laravel-order-by"
author: "benjamincrozat"
description: "Master Laravel's Eloquent `orderBy()`. Explore multiple columns sorting, the advanced `orderByRaw()`, and `reorder()`."
categories:
  - "laravel"
published_at: 2023-09-09T00:00:00+02:00
modified_at: 2025-07-10T15:09:00+02:00
serp_title: "Laravel Eloquent: sort query results using orderBy() (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/rgz5ybN0xhHeW42.jpg"
sponsored_at: null
---
## Laravel orderBy() basics

When you need to sort data in Laravel, the **Laravel order by** methods give you everything you need—no raw SQL, no drama. Here’s the foundation of the `orderBy()` method:

```php
$users = User::query()
    ->orderBy('name', 'desc')
    ->get();
```

In this snippet, we're using Laravel Eloquent to fetch users from their table and ordering them in descending order by their names thanks to the `orderBy()` method.

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

- [Unlock the power of Laravel's query builder where clauses](/laravel-query-builder-where-clauses)
- [Efficient data filtering with whereIn() in Laravel](/laravel-query-builder-wherein)
- [Understanding database transactions with Laravel](/database-transactions-laravel)
- [Validation in Laravel made easy](/laravel-validation)
