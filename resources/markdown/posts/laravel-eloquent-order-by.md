---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/63/Di9ZBYMAkeuG3mF5fhRqTervFNAvu2-metaZ3V5LWNvZGluZy0zX2xwejBxeS1vcHRpbWl6ZWQuanBn-.jpg
Title: "Sort your Laravel Eloquent queries results using orderBy()"
Description: "Master Laravel's Eloquent `orderBy()`. Explore multiple columns sorting, the advanced `orderByRaw()`, and `reorder()`."
Published at: 2023-09-09
Modified at: 2023-12-20
Categories: laravel
---

## The basics of orderBy()

Before we dive deep, let's understand the foundation of the `orderBy()` method:

```php
$users = User::query()
    ->orderBy('name', 'desc')
    ->get();
```

In this snippet, we're using Laravel Eloquent to fetch users from their table and ordering them in descending order by their names thanks to the `orderBy()` method.

Its parameters are:
- **The column's name**.
- **The order direction**: Either `asc` (the default value) for ascending or `desc` for descending.

## The orderByDesc() method

If you want to sort your results in descending order, you can also use the `orderByDesc()` method, which is a shortcut for `orderBy('column', 'desc')`:

```php
$users = User::query()
   {- ->orderBy('name', 'desc')-}
    {+->orderByDesc('name')+}
    ->get();
```

It's all in the details! 👌

## Multi-column sorting using orderBy()

What if you want to sort by multiple columns? Simple. Just chain multiple `orderBy()` methods:

```php
$users = User::query()
    ->orderBy('name', 'desc')
    ->orderBy('email', 'asc')
    ->get();
```

This way, Eloquent sorts users by their names first. If two or more users have the same name, it then sorts those users by their email in ascending order.

I actually learned that only after years of SQL and Laravel experience. 😅

## Getting fancy with orderByRaw()

When you need a more complex sorting mechanism, Laravel's got you covered with `orderByRaw()`:

```php
$orders = User::query()
    ->orderByRaw('updated_at - created_at DESC')
    ->get();
```

This advanced method lets you sort the results based on the difference between the `updated_at` and `created_at` timestamps. Handy, right?

## Use reorder() to unorder what's already been ordered

If you need to undo the ordering of a query you are building based on some condition, you can use the `reorder()` method:

```php
$ordered = User::orderBy('name');

$unordered = $ordered->reorder()->get();
```

And if you wish to reset and apply a completely new ordering without calling `orderBy()` again:

```php
$ordered = User::query()->orderBy('name');

$reorderedByEmail = $query->reorder('email', 'desc')->get();
```

I'll never get bored of Laravel's convenience!
