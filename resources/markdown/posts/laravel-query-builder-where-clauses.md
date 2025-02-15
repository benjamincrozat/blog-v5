---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/71/NjPIPYfFErJWOhUIgb6vbjhiAibOPR-metacHJvZ3JhbW1pbmdfbGZlcnRzLmpwZw%3D%3D-.jpg
Title: "Unlock the power of Laravel's query builder where clauses"
Description: "Unleash Laravel's query builder with my deep dive into the power of \"where\" clauses—triggering conditions, exclusions, JSON queries, and more."
Published at: 2023-09-12
Categories: laravel
---

## Introduction

One of the most basic yet powerful features of Laravel's query builder, that I and other developers use all the time, is the ability to utilize "where" clauses.

If you're eager to optimize your Laravel apps, understanding their nuances is essential. So, let's dive deep into the realm of the "where" and unlock its full potential!

## The essentials of where clauses

### Basic where clauses

The foundation of any query is its conditions. In Laravel Laravel's query builder, the basic structure of where clauses is intuitive and expressive. Simply put, you mention the column, the operator, and the value you want to compare.

For instance, imagine fetching comments with 100 votes:

```php
$comments = Comment::where('votes', '=', 100)->get();
```

I think it's nice. What about you? But that's not it. Laravel lets you simplify where equals clauses:

```php
$comments = Comment::where('votes', 100)->get();
```

If you're just checking for equality, Laravel assumes you mean the '=' operator. 

And what if you want to combine where clauses?

```php
Foo::query()
    ->where('foo', 'bar')
    ->where('bar', 'baz')
    ->get();
```

There is the beauty of Laravel's query builder!

[Learn more about basic where clauses.](https://laravel.com/docs/10.x/queries#basic-where-clauses)

### Or where clauses

Life isn't always about "and". Sometimes, it's about "or" (pardon my philosophical side). And Laravel's query builder gracefully understands that. While chaining multiple where methods will join them using "and", there's an elegant way to use the "or" condition: the `orWhere` method.

Here’s a quick example:

```php
$users = User::query()
    ->where('votes', '>', 100)
    ->orWhere('name', 'John')
    ->get();
```

This fetches users who either have votes more than 100 or are named John. Handy, right?

[Learn more about orWhere clauses.](https://laravel.com/docs/10.x/queries#or-where-clauses)

### Where not clauses

Sometimes, it's not about what something is, but what it's not (I did it again…). That’s where the `whereNot` clauses come into play. They negate a set of conditions, making exclusions a breeze.

For instance, if you wish to exclude products on clearance or priced below ten, it's as straightforward as:

```php
$products = Product::query()
    ->whereNot(function (Builder $query) {
        $query->where('clearance', true)
            ->orWhere('price', '<', 10);
    })
    ->get();
```

[Learn more about whereNot clauses.](https://laravel.com/docs/10.x/queries#where-not-clauses)

### JSON where clauses

With the digital age's demands, databases have evolved, and so has Laravel. Modern databases often use JSON column types, and Laravel's query builder supports querying these like a champ! Be it MySQL, PostgreSQL, or even SQLite, you can fetch data with ease.

Looking for users who prefer a salad meal? There you go:

```php
$users = User::query()
    ->where('preferences->dining->meal', 'salad')
    ->get();
```

[Learn more about JSON where clauses.](https://laravel.com/docs/10.x/queries#json-where-clauses)

### Additional where clauses

Laravel's query builder's where capabilities don't just stop at the basics. It offers a plethora of options to cater to different scenarios:

- **Between values**: The `whereBetween` method checks if a column's value lies between two given values. Similarly, `whereNotBetween` ensures the column's value is outside those two values.
- **In or not in**: The `whereIn` method is perfect when you want to check if a column's value exists in a given array. Its counterpart, `whereNotIn`, does the exact opposite.
- **Date & time specific**: Methods like `whereDate`, `whereMonth`, and `whereTime` make it easy to fetch records based on specific dates, months, or times.
- **Comparing columns**: With the `whereColumn` method, you can effortlessly compare two columns in the same table. Be it checking for equality or any other relation.

[Learn more about additional where clauses.](https://laravel.com/docs/10.x/queries#additional-where-clauses)
