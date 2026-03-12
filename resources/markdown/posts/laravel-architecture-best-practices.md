---
id: "01KKEW27BKPQW9R4K0SGGYRJB8"
title: "5 Laravel architecture best practices for 2026"
slug: "laravel-architecture-best-practices"
author: "benjamincrozat"
description: "Structure Laravel apps with fewer regrets: keep the defaults, organize by domain, and add abstractions only when the codebase earns them."
categories:
  - "laravel"
published_at: 2023-08-31T22:00:00Z
modified_at: 2026-03-12T21:24:19Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/RzyvYZckjr85tMa.jpg"
sponsored_at: null
---
## Introduction

If you are wondering how to structure a Laravel app, the short answer is simpler than most architecture debates suggest: **start with Laravel's defaults, organize by business domain inside them, and only add extra layers when the codebase has clearly earned them.**

That advice is not just "beginner friendly." Laravel's own [directory structure documentation](https://laravel.com/docs/12.x/structure) says the default application structure is meant to be a great starting point for both small and large applications. The docs also describe the `app/Http` and `app/Console` directories as interfaces into your application, which is a useful reminder that business logic does not belong everywhere.

So instead of chasing a fashionable folder layout, focus on architecture choices that make your project easier to onboard, easier to upgrade, and easier to extend without surprises.

If your team is already feeling the pain in day-to-day code, this companion guide on [Laravel best practices](/laravel-best-practices) covers the conventions that usually break first.

## What good Laravel architecture should optimize for

Before you move folders around or introduce a new pattern, ask what problem you are actually solving.

Good Laravel architecture usually improves at least one of these:

- New developers can find code quickly.
- Framework upgrades and package integrations stay predictable.
- Business rules have obvious homes.
- Refactors are safer because the boundaries are easier to test.

If a structural change does not improve one of those outcomes, it is probably architecture theater.

## 1. Keep Laravel's default structure until you can name the pain

Laravel already gives you clear places for controllers, requests, models, policies, jobs, mailables, notifications, events, and more. That matters because every teammate, package author, and future maintainer can work from the same map.

The default structure is especially helpful when you need to:

- onboard a new hire fast
- install or evaluate a package
- plan a Laravel upgrade
- search for a bug under pressure

This does not mean you should never evolve the structure. It means the burden of proof is on the change.

Good reasons to move beyond the default shape:

- a domain is large enough that related classes are hard to find
- the same workflow is duplicated across multiple controllers or jobs
- a boundary with an external system needs a dedicated abstraction

Weak reasons:

- you saw a "clean architecture" repo on social media
- every project at your company must use the same custom skeleton
- adding more folders simply feels more serious

## 2. Organize by domain inside Laravel's folders

One of the easiest ways to scale a Laravel app without fighting the framework is to group classes by domain *inside* the default folders.

For example, a billing area can look like this:

```text
app/
  Http/
    Controllers/
      Billing/
        InvoiceController.php
    Requests/
      Billing/
        StoreInvoiceRequest.php
  Jobs/
    Billing/
      SyncInvoiceToErp.php
  Models/
    Billing/
      Invoice.php
  Policies/
    Billing/
      InvoicePolicy.php
```

This gives you two benefits at once:

- you keep Laravel's conventions and package compatibility
- you still make the business domain obvious

It is usually a better long-term tradeoff than dumping half the application into vague top-level folders like `Services`, `Helpers`, `Traits`, or `Utilities`.

When you need a next step after the controller layer, my article on [Laravel RESTful API best practices](/laravel-restful-api-best-practices) goes deeper on keeping HTTP boundaries predictable.

## 3. Keep the HTTP layer thin by using Laravel's built-in boundaries

A lot of Laravel architecture problems are really "too much logic in the wrong place" problems.

Laravel already gives you strong boundaries for common responsibilities:

- [form requests](https://laravel.com/docs/12.x/validation#form-request-validation) for validation and input normalization
- [policies](https://laravel.com/docs/12.x/authorization#creating-policies) for authorization
- [API resources](https://laravel.com/docs/12.x/eloquent-resources) for JSON output
- [jobs](https://laravel.com/docs/12.x/queues) for slow or asynchronous work
- events and listeners for side effects that should stay decoupled

That means your controllers can stay focused on orchestration instead of becoming a storage unit for every rule in the system.

```php
use App\Actions\Billing\CreateInvoice;
use App\Http\Requests\Billing\StoreInvoiceRequest;
use App\Http\Resources\Billing\InvoiceResource;

final class StoreInvoiceController
{
    public function __invoke(
        StoreInvoiceRequest $request,
        CreateInvoice $createInvoice,
    ): InvoiceResource {
        $invoice = $createInvoice->handle($request->validated());

        return new InvoiceResource($invoice);
    }
}
```

The point is not "you must have action classes." The point is that controllers should not validate input, check permissions, persist records, call third-party APIs, format JSON, and dispatch side effects all inline.

If you want the testing angle for these boundaries, the dedicated guide on [Laravel testing best practices](/laravel-testing-best-practices) is the next read.

## 4. Introduce abstractions only when the behavior actually varies

Laravel's [service container](https://laravel.com/docs/12.x/container) already resolves many concrete classes automatically, so you do not need to wrap everything in an interface on day one.

That is why I would not default to repositories, DTO layers, or service abstractions just because they sound architectural.

Reach for an abstraction when you have a real reason, for example:

- one workflow must support multiple providers
- an external API deserves its own boundary and retry strategy
- a complex use case is reused in several entry points
- you need a clean seam for testing unstable infrastructure

That often leads to code like this:

```php
use App\Contracts\Billing\InvoiceExporter;
use App\Services\Billing\S3InvoiceExporter;

public function register(): void
{
    $this->app->bind(InvoiceExporter::class, S3InvoiceExporter::class);
}
```

This is valuable when the implementation may change.

It is not valuable when the "abstraction" is a one-method wrapper around `Invoice::query()`.

As a rule of thumb, prefer Laravel's native primitives first, then add your own layer only when it removes real duplication, real coupling, or real risk.

## 5. Protect the structure with tests before you refactor it

Architecture gets expensive when the team agrees on a structure in theory but nothing enforces it in practice.

That is why the best architectural changes are small, test-backed, and reversible.

At minimum, keep strong feature tests around important user flows. If you also want to lock in structural rules, architecture tests are a practical fit. Pest supports [architecture testing](https://pestphp.com/docs/arch-testing), which can help you catch boundary leaks early.

```php
arch()
    ->expect('App\Models')
    ->toExtend('Illuminate\\Database\\Eloquent\\Model');
```

You can add rules that match your project, such as:

- controllers should stay inside `App\Http\Controllers`
- models should extend Eloquent's base model
- requests should live in `App\Http\Requests`
- support code should not depend on the HTTP layer

This matters most when the app grows. Without tests, a "small" folder reorganization can quietly break routes, imports, policies, and assumptions across the team.

## When should you move beyond Laravel's defaults?

Some projects really do need more than the stock application layout.

That is more likely when:

- several teams work on distinct bounded contexts
- deployment units are intentionally separate
- the domain is complex enough that explicit modules reduce confusion
- external integrations dominate the architecture

Even then, do not throw away Laravel's conventions casually. Keep the move incremental, document the why, and make sure the new structure is easier to understand than the one it replaced.

## FAQ

### Should every Laravel app have a service layer?

No. Many Laravel apps are easier to maintain when they use the framework's own boundaries first. Add a service or action class when it clarifies orchestration or removes duplication, not because every controller must call one.

### Should I use repositories in Laravel?

Only when they hide real variation, such as multiple data sources or a boundary you genuinely need to swap. A repository that just forwards calls to Eloquent usually adds indirection without adding value.

### How do I organize a large Laravel app without inventing a custom architecture?

Group code by business domain inside the default Laravel folders, keep HTTP concerns thin, and enforce the boundaries with tests. That scales surprisingly far before you need heavier patterns.

### When is DDD or modular Laravel worth it?

When the domain is complex enough that the default structure no longer makes related concepts easy to find or reason about. If the team cannot explain the benefit clearly, it is probably too early.

## Conclusion

Most Laravel architecture wins come from restraint, not cleverness.

Start with the framework's defaults, group code by domain inside them, keep responsibilities in the right Laravel primitives, and add custom abstractions only when the codebase gives you a concrete reason. That path is usually the fastest way to a codebase that survives both growth and handoffs.

If you're trying to keep a growing codebase from turning into a maze, these next reads cover the boundaries that usually crack first:

- [See which everyday Laravel habits pay off across the whole app](/laravel-best-practices)
- [Tighten the API layer before endpoints start leaking business rules](/laravel-restful-api-best-practices)
- [Use tests to lock in structure before a refactor gets risky](/laravel-testing-best-practices)
- [Close the security gaps that architecture alone will not save you from](/laravel-security-best-practices)
