---
id: "01KKEW278NCC1FR14FSS422CAE"
title: "Understanding database transactions with Laravel"
slug: "database-transactions-laravel"
author: "benjamincrozat"
description: "Discover how Laravel simplifies database transactions, ensuring all-or-nothing operations and maintaining consistent database state."
categories:
  - "databases"
  - "laravel"
published_at: 2023-08-27T00:00:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/57O5sYzzhqZftvd.png"
sponsored_at: null
---
## What are database transactions?

When developing web applications, we frequently perform multiple operations on our database. As developers, we need to ensure that either all of these operations succeed, or in case of error, none of them do. This all-or-nothing principle is encapsulated in the concept of a database transaction (lots of databases support this concept, such as [MySQL](https://dev.mysql.com/doc/refman/8.0/en/commit.html) and [PostgreSQL](https://www.postgresql.org/docs/current/tutorial-transactions.html)).

A database transaction is a sequence of one or more database operations executed as a unit of work. If any operation within the transaction fails (mostly in a context of high traffic), the entire transaction gets rolled back – in other words, none of the changes are applied. On the other hand, if all operations are successful, the transaction commits and all changes are saved to the database.

## How Laravel simplifies database transactions

If you've worked with database transactions in raw SQL, you know handling them manually using "BEGIN TRANSACTION", "COMMIT", and "ROLLBACK" can be a bit repetitive. Luckily, Laravel simplifies transactions with its convenient DB::transaction() method.

With Laravel, you just pass a closure into `DB::transaction()`. The operations within the closure will be wrapped up in a database transaction. It couldn't get easier!

Here's some example code:

```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    $room = Room::find(1);

    // Create a booking for the room. Nothing fancy there.
    Booking::create([
        'user_id' => auth()->id(),
        'room_id' => $room->id,
    ]);

    // Create a payment for the room. If, for whatever reason, the
    // payment can't be created, the booking will be rolled back!
    Payment::create([
        'user_id' => auth()->id(),
        'room_id' => $room->rate,
    ]);
});
```

In this example, we're performing two operations within a single transaction. We're creating a new booking and recording a payment. If any of these operations fail, none of the changes will be saved to the database. This ensures our database remains in a consistent state under all circumstances.

Before we end, know that the `DB::transaction()` method accepts a second parameter, which is the number of times the process must be retried in case of failure.

```php
use Illuminate\Support\Facades\DB;

DB::transaction(function () {
    …
}, 3);
```

Learn more about [transactions in Laravel](https://laravel.com/docs/10.x/database#database-transactions) on the official documentation.

If you are staying in the part of Laravel where data consistency matters most, these follow-up reads are the ones I would keep nearby:

- [Fix "1305 SAVEPOINT trans2 does not exist" in Laravel](/1305-savepoint-trans2-does-not-exist)
- [Laravel migrations: a simplified and step by step guide](/laravel-migrations)
- [Unlock the power of Laravel's query builder where clauses](/laravel-query-builder-where-clauses)
- [Efficient data filtering with whereIn() in Laravel](/laravel-query-builder-wherein)
- [9 testing best practices for Laravel in 2025](/laravel-testing-best-practices)
