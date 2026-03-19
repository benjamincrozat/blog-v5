---
id: "01KKEW27KKSV7D18ZBY0ZH2WJ0"
title: "PHP enums: backed vs unit enums with examples"
slug: "php-enums"
author: "benjamincrozat"
description: "Learn when to use PHP enums, how unit and backed enums differ, and how to model real application states safely."
categories:
  - "php"
published_at: 2023-07-05T00:00:00+02:00
modified_at: 2026-03-19T22:39:10Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/VFWE7N2VVtCYk2D.png"
sponsored_at: null
---
## Introduction

**PHP enums let you model a fixed list of allowed values as a real type.**

If you only need the quick answer:

- Use a **unit enum** when the case itself is enough.
- Use a **backed enum** when you also need a stable string or integer value for a database, API, queue payload, or config file.

Native enums were added in **PHP 8.1**, and the PHP manual describes them as a special kind of object. That makes them much safer than passing raw strings like `"draft"` or `"published"` around your app.

## When PHP enums are worth using

Enums are a good fit when a value should only ever be one of a small number of valid choices.

Typical examples:

- order status
- user role
- payment state
- app environment
- HTTP method

Without enums, those values often end up as loose strings, which makes typos and invalid states easier to miss.

```php
$status = 'publshed'; // typo, but still a string
```

With an enum, PHP restricts the value to known cases:

```php
enum PostStatus
{
    case Draft;
    case Published;
    case Archived;
}
```

Now a property, argument, or return type can only be one of those three cases.

## Unit enums vs backed enums

This is the part most people are actually trying to sort out.

| Type | Best for | Stores scalar value? | Common example |
| --- | --- | --- | --- |
| Unit enum | In-memory app logic | No | workflow states |
| Backed enum | Database or API values | Yes | `draft`, `published`, `archived` |

### Use a unit enum when the case name is enough

Unit enums only define cases:

```php
enum ServerEnvironment
{
    case Local;
    case Staging;
    case Production;
}
```

This is great when you just need a constrained value inside your application.

### Use a backed enum when you need a stable stored value

Backed enums attach each case to a `string` or `int`:

```php
enum PostStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
```

That is usually the better choice when you need to:

- save the value in a database
- send it in JSON
- receive it from an API
- keep values stable even if you rename a case later

## A practical backed-enum example

Here is the common Laravel or plain PHP scenario: you read a string from storage and want a typed enum again.

```php
enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Refunded = 'refunded';
}

$status = OrderStatus::from('paid');

var_dump($status === OrderStatus::Paid); // true
```

If the value may be invalid, use `tryFrom()` instead of `from()`:

```php
$status = OrderStatus::tryFrom($request['status']) ?? OrderStatus::Pending;
```

- `from()` throws a `ValueError` for an invalid value
- `tryFrom()` returns `null`

That small difference matters a lot when the data comes from a request, CSV import, or third-party API.

## Listing enum cases

All enums can list their cases with `cases()`:

```php
enum UserRole
{
    case Admin;
    case Editor;
    case Viewer;
}

$roles = UserRole::cases();
```

That is useful for:

- select options
- validation rules
- docs or debug output
- random test data

If you are using a backed enum and want just the scalar values, map over the cases:

```php
$values = array_map(
    fn (OrderStatus $status) => $status->value,
    OrderStatus::cases(),
);
```

## Enum methods are where they start paying off

Enums are not just constants with nicer syntax. You can add methods and keep related logic close to the cases.

```php
enum OrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Refunded = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending payment',
            self::Paid => 'Paid',
            self::Refunded => 'Refunded',
        };
    }

    public function isFinal(): bool
    {
        return match ($this) {
            self::Pending => false,
            self::Paid, self::Refunded => true,
        };
    }
}
```

That keeps your code easier to read than scattering `match` blocks throughout controllers, jobs, and views.

## Real use cases for PHP enums

Here are the patterns I see most often in real projects.

### Database-backed state

Use a backed enum for values that live in a table:

```php
enum SubscriptionStatus: string
{
    case Trialing = 'trialing';
    case Active = 'active';
    case Canceled = 'canceled';
}
```

### API-friendly values

If an external API expects `"GET"` or `"POST"`, a backed enum gives you safer code:

```php
enum HttpMethod: string
{
    case Get = 'GET';
    case Post = 'POST';
    case Put = 'PUT';
    case Delete = 'DELETE';
}
```

### Domain rules with methods

Enums become especially useful when the value has behavior:

```php
enum InvoiceStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Paid = 'paid';

    public function canBeEdited(): bool
    {
        return $this !== self::Paid;
    }
}
```

## Traits and interfaces also work

Enums can implement interfaces and use traits, which is handy when several enums should share a contract.

```php
interface HasLabel
{
    public function label(): string;
}

enum Visibility: string implements HasLabel
{
    case Public = 'public';
    case Private = 'private';

    public function label(): string
    {
        return match ($this) {
            self::Public => 'Public',
            self::Private => 'Private',
        };
    }
}
```

That said, enums are still more restricted than normal classes. They are not general-purpose objects.

## Common enum gotchas

These are the mistakes that usually trip people up:

- Enums require **PHP 8.1 or newer**.
- A backed enum can only use `string` or `int`.
- Use `from()` only when you trust the input.
- Store **backed enum values** in databases, not case names, unless you have a very specific reason not to.
- A unit enum has `name`, but not `value`.

Another practical rule: if the value must cross a storage or network boundary, a backed enum is usually the safer default.

## What if you are stuck on PHP 7 or older?

If you cannot use PHP 8.1 yet, your fallback is usually class constants or a small value object. That works, but you lose the native enum ergonomics and type safety.

```php
final class LegacyPostStatus
{
    public const DRAFT = 'draft';
    public const PUBLISHED = 'published';
    public const ARCHIVED = 'archived';
}
```

That is serviceable, but native enums are cleaner, harder to misuse, and easier to extend with methods.

If you are deciding whether to use enums or older PHP patterns, these are the next reads I would open:

- [Use `match` when `switch` starts feeling clumsy](/a-quick-look-at-the-php-match-expression)
- [Make PHP serialization finally click without the usual confusion](/a-friendly-guide-to-php-serialization-that-finally-clicked)
- [See where the state machine pattern helps in PHP](/a-friendly-intro-to-the-state-machine-pattern-in-php)
