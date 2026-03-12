---
id: "01KKEW27CBJMRVG51PKHZA9VC5"
title: "Laravel firstOrCreate, firstOrNew, createOrFirst and friends"
slug: "laravel-firstorcreate-firstornew-createorfirst-updateorcreate-updateorinsert"
author: "benjamincrozat"
description: "Stop using firstOrCreate blindly. Learn when to use firstOrNew, createOrFirst, updateOrCreate, updateOrInsert and upsert in real Laravel apps."
categories:
  - "laravel"
published_at: 2025-12-01T16:58:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBDE7ARNB5TRB23WCA77129J.png"
sponsored_at: null
---
## Introduction

If you Google `firstOrCreate` or `firstOrNew`, you mostly get the same snippet:

```php
User::firstOrCreate(['email' => $email]);
```

Nice demo. Useless in practice.

What those articles rarely explain is:

* When `firstOrCreate` is **dangerous** under load.
* When you should use `createOrFirst` instead.
* When `firstOrNew` is the better tool.
* How `updateOrCreate` differs from query builder upserts.
* Where helpers like `firstOr`, `firstOrFail`, and `updateOrInsert` fit.

## `firstOrNew`: prepare a model without writing to the database

**What it does:**

1. Runs a `SELECT` using `$attributes`.
2. If a record exists → returns a hydrated model.
3. If nothing is found → returns a **new, unsaved** model with `$attributes + $values`.

No insert, no update, no events. The database isn’t written to until you call `save()` (it may still run a `SELECT`).

**Example:**

```php
$user = User::firstOrNew(
    ['email' => $email],
    ['name' => $name],
);

if (! $user->exists) {
    // Do whatever you need before persisting.
    $user->password = bcrypt($plainPassword);
    $user->save();
}
```

**Use `firstOrNew` when:**

* You want to *prepare* a record, not automatically create it.
* You want to let a user review or edit data before saving.
* You care about branching logic:

  ```php
  $model = Model::firstOrNew([...]);

  if ($model->exists) {
      // Existing row path.
  } else {
      // New row path.
  }
  ```

**Don’t use `firstOrNew` when:**

* You simply want “get or create, no questions asked.” In that case, `laravel firstorcreate` or `createOrFirst` is more appropriate.

## `firstOrCreate`: the classic “get or create” helper

**What it does:**

1. Runs a `SELECT` using `$attributes`.
2. If a row exists → returns it.
3. If not → `INSERT` with `$attributes + $values`, then returns the new model.

**Example:**

```php
$tag = Tag::firstOrCreate(
    ['name' => $name],
    ['slug' => Str::slug($name)],
);
```

This is your standard “find or create” pattern:

* You always get a model instance back.
* It fires the usual Eloquent events (`creating`, `created`).
* Casting, mutators, observers, etc. all run.

So `firstOrCreate` in Laravel is an **idempotent-ish** helper for low to normal traffic.

### The catch: race conditions

`firstOrCreate` is **select-then-insert**:

```text
Request A: SELECT ... WHERE name = 'php'
Request B: SELECT ... WHERE name = 'php'   (at the same time)

→ both see “no rows”
→ both call INSERT
→ duplicates, or a unique constraint error
```

If you have a unique index on `name`, one of them will blow up with an exception.

So in high-concurrency contexts (auth, logins, webhook handlers, background workers), naive use of `firstOrCreate` can:

* Create duplicate rows, or
* Throw unique constraint violations under load.

That’s exactly why Laravel added a new method.

## `createOrFirst`: the safer cousin under concurrency

**What it does:**

1. Tries to **insert first**, using `$attributes + $values`.
2. If insert succeeds → returns the new model.
3. If insert fails with a *unique constraint* error:

   * It catches the exception.
   * Runs a `SELECT` using `$attributes`.
   * Returns the existing row instead of exploding.

Same intent as `firstOrCreate`: “Get or create.”
Different implementation: **insert-then-select** instead of **select-then-insert**.

**Example:**

```php
// Safe under concurrency if you have a unique index on email.
$user = User::createOrFirst(
    ['email' => $email],
    ['name' => $name],
);
```

### When `createOrFirst` beats `firstOrCreate`

Use `createOrFirst` when:

* You have a **unique index** on the identifying columns (you should anyway).
* The code may run from multiple processes/workers at once.
* Duplicates would hurt: user accounts, API tokens, configuration rows, anything keyed by a unique attribute.

Rough rule:

* For low-traffic / admin-only flows: `firstOrCreate` is fine.
* For public entry points and jobs that run in parallel: prefer `createOrFirst` with a proper unique constraint.

If you want one strong opinion out of this article, it’s this:

> If you care about data integrity under load, stop relying on `laravel firstorcreate` alone. Put a unique index in place and use `createOrFirst`.

## `updateOrCreate`: upsert with Eloquent events

**What it does:**

1. Runs a `SELECT` using `$attributes`.
2. If a row exists → calls `$model->update($values)` on it.
3. If not → `INSERT` with `$attributes + $values`.

**Example:**

```php
// Upserting user settings
$settings = UserSettings::updateOrCreate(
    ['user_id' => $user->id],  // identity
    [
        'locale'   => $locale,
        'timezone' => $timezone,
    ],
);
```

**Key points:**

* Returns the model (created or updated).
* Fires `creating`/`created` or `updating`/`updated` plus the generic `saving`/`saved`.
* Everything Eloquent-aware still works: casts, accessors/mutators, observers, etc.

### Easy way to shoot yourself in the foot

A very common bug with `updateOrCreate` in Laravel:

```php
// Buggy usage:
Order::updateOrCreate(
    [
        'user_id'   => $userId,
        'reference' => $reference,
        'status'    => 'paid',  // ❌ doesn't belong here
    ],
    [
        'status'    => 'paid',
        'total'     => $total,
    ],
);
```

Here you’re saying:

> “Find an order with this `user_id`, `reference` **and** `status = paid`.”

If the existing row is still `pending`, it won’t be found.
Laravel will happily create another row instead of updating the existing one.

**Rule of thumb:**

* `$attributes` → things that identify the record (the “where” that must stay stable, often what you’d put in a unique index).
* `$values` → things that change over time.

If the value can change, it usually belongs in the second array.

## Other `*Or*` helpers you’ll actually use

These aren’t directly about creating rows, but they sit in the same mental family as `firstOrNew` and `firstOrCreate`.

### `firstOr`: custom fallback instead of auto-creating

```php
$user = User::where('email', $email)->firstOr(function () use ($email) {
    // You decide what the fallback is
    return User::create([
        'email' => $email,
        'name'  => 'Guest',
    ]);
});
```

* Tries `first()`.
* If a model is found → returns it.
* If not → executes your callback and returns whatever the callback returns.

Nice when your fallback is more complex than “just create a row”.

### `firstOrFail()` and `findOrFail()`: fail fast

```php
// Throws ModelNotFoundException (typically becomes a 404)
$post = Post::whereSlug($slug)->firstOrFail();

// Same for primary key
$user = User::findOrFail($id);
```

Use them when “missing” is exceptional and should result in an error (web 404, API 404, etc.), not a `null` check sprinkled everywhere.

### `findOrNew($id)`: primary key version of `firstOrNew`

```php
$user = User::findOrNew($id);

if (! $user->exists) {
    // New, unsaved model
    $user->email = $fallbackEmail;
}
```

Same idea as `firstOrNew`, just keyed on the primary key.

## Query builder equivalents: `updateOrInsert` and `upsert`

Sometimes you don’t want Eloquent models at all. You just want rows updated or inserted, no events, no casting, no observers.

That’s where the **query builder** helpers come in.

### `updateOrInsert`: table-based upsert

```php
DB::table('settings')->updateOrInsert(
    [
        'user_id' => $userId,
        'key'     => 'theme',
    ],
    [
        'value'   => 'dark',
    ],
);
```

* If a row exists matching the first array → update it.
* If not → insert a new one.
* Returns a boolean, **not** a model.

Use it when you care about data being there, but don’t care about any Eloquent layer.

### `upsert`: bulk version

```php
DB::table('products')->upsert(
    [
        ['sku' => 'A123', 'price' => 1000],
        ['sku' => 'B456', 'price' => 2000],
    ],
    ['sku'],      // unique key(s)
    ['price'],    // columns to update on conflict
);
```

* Uses native database upsert (`ON CONFLICT`, `ON DUPLICATE KEY UPDATE`, etc.).
* Returns an integer: the number of affected rows.
* Perfect for imports, sync jobs, and batch processing.

You can also call `upsert` on an Eloquent model:

```php
Product::upsert(
    [...],
    ['sku'],
    ['price'],
);
```

Under the hood it still goes straight to the database, doesn’t return models, and doesn’t fire Eloquent events.

### How this differs from `updateOrCreate`

* `updateOrCreate` is **Eloquent-first**:

  * You get model instances back.
  * Model events are triggered.
  * Casts, mutators, observers, and mass-assignment rules are in play.
* `updateOrInsert` / `upsert` are **table-first**:

  * `updateOrInsert` returns a boolean.
  * `upsert` returns an integer (affected rows).
  * No events, no casts, no model-level logic.
  * Great when you just want raw speed and predictable SQL.

## Choosing between `firstOrNew`, `firstOrCreate`, `createOrFirst`, and `updateOrCreate`

Let’s condense this into a decision guide.

### 1. “I might create later, but not always.”

> “Give me the record if it exists, or a new unsaved model if not. I’ll decide what to do.”

Use **`firstOrNew`** (or `findOrNew` for primary keys).

* Keeps you in control.
* No automatic writes or events.

### 2. “Low traffic, simple get-or-create.”

> “I just want a record. Create it if it doesn’t exist. This path isn’t hammered.”

Use **`firstOrCreate`** (`laravel firstorcreate` / `firstorcreate laravel` in search terms).

* Great default for admin panels, CLI commands, or rare code paths.
* Just be aware it’s select-then-insert.

### 3. “High traffic, no duplicates, ever.”

> “This might be hit in parallel. Duplicates or exceptions would be painful.”

Use **`createOrFirst`** with a unique index on your key columns.

```php
// Migration
Schema::table('users', function (Blueprint $table) {
    $table->string('email')->unique();
});

// Code
$user = User::createOrFirst(['email' => $email]);
```

This should be your default for “get or create” in public or concurrent code paths.

### 4. “Update existing or create new, with model logic.”

> “If it exists, update it. If not, create it. I want events, casts, and observers.”

Use **`updateOrCreate`**.

* Remember: `$attributes` are *identifying* fields, `$values` are *mutable* fields.
* If you don’t care about model logic and just want the row, drop to `updateOrInsert` / `upsert`.

### 5. “If nothingis found, I want custom behavior.”

> “Sometimes I’ll create, sometimes I’ll throw, sometimes I’ll log. It’s not always the same.”

Use:

* **`firstOr`** when you want to run a callback as a fallback.
* **`firstOrFail` / `findOrFail`** when “not found” should be an error, not `null`.

## Final thoughts

* **Do I care about concurrency and duplicates?**

  * If yes, design around `createOrFirst` with unique indexes.
* **Do I want Eloquent behavior (events, casts, observers)?**

  * If yes, stick to Eloquent helpers: `firstOrNew`, `firstOrCreate`, `createOrFirst`, `updateOrCreate`.
  * If no, use builder helpers: `updateOrInsert`, `upsert`.
* **Do I want an unsaved model or an immediate write?**

  * Unsaved: `firstOrNew`, `findOrNew`.
  * Immediate: `firstOrCreate`, `createOrFirst`, `updateOrCreate`.

If you want a few more Laravel rabbit holes after this:

- [Unlock the power of Laravel's query builder where clauses](/laravel-query-builder-where-clauses)
- [Laravel retrospective: what changed since version 5.8?](/laravel-retrospective)
- [8 Laravel RESTful APIs best practices for 2025](/laravel-restful-api-best-practices)

