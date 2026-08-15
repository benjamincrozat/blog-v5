---
id: "01KKEW27D7086V9HV2YSPF4CY7"
title: "Laravel orderBy(): examples for asc, desc, and more"
slug: "laravel-order-by"
author: "benjamincrozat"
description: "Sort Laravel and Eloquent queries with orderBy(), orderByDesc(), latest(), relationship counts, safe user-selected columns, null handling, and reorder()."
categories:
  - "laravel"
published_at: 2023-09-08T22:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/rgz5ybN0xhHeW42.jpg"
sponsored_at: null
---
Use Laravel's `orderBy()` method to sort a query by a column. Ascending order is the default; pass `desc` or use `orderByDesc()` for descending order.

```php
$users = User::query()
    ->orderBy('name')
    ->get();
```

## Common Laravel ordering methods

| Job | Query |
| --- | --- |
| Name A-Z | `->orderBy('name')` |
| Name Z-A | `->orderBy('name', 'desc')` |
| Newest creation first | `->latest()` |
| Oldest creation first | `->oldest()` |
| Newest update first | `->latest('updated_at')` |
| Several tie-breakers | Chain more `orderBy()` calls |
| Replace an existing order | `->reorder('name')` |

## Sort ascending or descending

The second argument accepts `asc` or `desc`:

```php
$users = User::query()
    ->orderBy('name', 'desc')
    ->get();
```

I prefer `orderByDesc()` when the direction is fixed because it is harder to miss while scanning the query:

```php
$users = User::query()
    ->orderByDesc('name')
    ->get();
```

`latest()` and `oldest()` default to `created_at`, but both accept another column:

```php
$posts = Post::query()
    ->latest('published_at')
    ->get();
```

## Add a tie-breaker with several orderBy() calls

SQL does not promise a stable order for rows that compare equal. Add another column when the output must be deterministic:

```php
$users = User::query()
    ->orderByDesc('score')
    ->orderBy('name')
    ->orderBy('id')
    ->get();
```

This sorts by score first, then name, then the unique ID when both values tie.

## Sort by a relationship count

Use `withCount()` to add the aggregate before ordering by it:

```php
$posts = Post::query()
    ->withCount('comments')
    ->orderByDesc('comments_count')
    ->get();
```

The resulting models include a `comments_count` attribute. This avoids loading every comment just to count it in PHP.

Laravel can order by other relationship aggregates too:

```php
$posts = Post::query()
    ->withMax('comments', 'created_at')
    ->orderByDesc('comments_max_created_at')
    ->get();
```

That puts posts with the most recent comment first.

## Allow user-selected sorting safely

Do not pass a request value directly as a column name. SQL parameter bindings protect values, not identifiers such as column names.

Map public options to a fixed allowlist:

```php
$columns = [
    'name' => 'name',
    'email' => 'email',
    'joined' => 'created_at',
];

$column = $columns[$request->string('sort')->toString()] ?? 'created_at';
$direction = $request->string('direction')->toString() === 'asc'
    ? 'asc'
    : 'desc';

$users = User::query()
    ->orderBy($column, $direction)
    ->get();
```

The URL can expose friendly names while the query only receives columns and directions you chose.

## Put null values last

Null ordering differs between database engines. There is no one portable `orderBy()` call that gives every engine the same result.

PostgreSQL supports `NULLS LAST`:

```php
$posts = Post::query()
    ->orderByRaw('published_at DESC NULLS LAST')
    ->get();
```

For MySQL and SQLite, sort by the null check first:

```php
$posts = Post::query()
    ->orderByRaw('published_at IS NULL')
    ->orderByDesc('published_at')
    ->get();
```

Test the query on the same engine used in production, especially when null placement matters to pagination.

## Use orderByRaw() for a real SQL expression

Raw ordering is useful for a fixed expression that the query builder cannot describe cleanly:

```php
$posts = Post::query()
    ->orderByRaw(
        'CASE WHEN status = ? THEN 0 ELSE 1 END',
        ['featured'],
    )
    ->latest()
    ->get();
```

Bind values as shown. Never interpolate request input into a raw ordering string.

## Remove or replace an earlier order

Scopes and relationships can add ordering before your code receives the builder. `reorder()` removes it:

```php
$query = User::query()->orderBy('name');

$users = $query->reorder()->get();
```

You can remove the old order and add a new one in the same call:

```php
$users = User::query()
    ->orderBy('name')
    ->reorder('email', 'desc')
    ->get();
```

The [Laravel 13 query builder documentation](https://laravel.com/docs/13.x/queries#ordering-grouping-limit-and-offset) covers `orderBy()`, `latest()`, `oldest()`, random ordering, and `reorder()`.

Related guides:

- [Write query-builder where clauses without surprises](/laravel-query-builder-where-clauses)
- [Filter results with whereIn()](/laravel-query-builder-wherein)
- [Use database transactions when partial writes would hurt](/database-transactions-laravel)
- [Write validation rules with less guesswork](/laravel-validation)
