---
id: "01KKEW27BSJDEWEBSTHRMRPRM0"
title: "20 Laravel best practices for 2026"
slug: "laravel-best-practices"
author: "benjamincrozat"
description: "A practical Laravel checklist for cleaner apps: updates, structure, validation, Eloquent performance, queues, tests, and habits that keep teams moving faster."
categories:
  - "laravel"
published_at: 2022-10-29T23:00:00Z
modified_at: 2026-03-18T18:07:37Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01JYZ22BGRDPV8C7SCG9FJWR63.webp"
sponsored_at: null
---
## Introduction

When people ask for Laravel best practices, the short answer is usually much less glamorous than they expect:

- stay close to the framework
- keep responsibilities in the right Laravel primitives
- test enough that upgrades and refactors stop feeling like roulette

That is what this guide is about.

It is not a list of trendy patterns you must apply to every project. It is a practical checklist for keeping Laravel codebases easier to upgrade, easier to onboard, and harder to accidentally break.

One quick reminder before we start: if you are still on Laravel 10 or older, upgrade first. Those releases are already out of security support in 2026.

If you want deeper guidance on one slice of this topic, I already broke out dedicated posts for [architecture best practices](/laravel-architecture-best-practices), [API best practices](/laravel-restful-api-best-practices), [testing best practices](/laravel-testing-best-practices), and [security best practices](/laravel-security-best-practices).

## 1. Keep Laravel up to date

Running a supported Laravel version is the baseline best practice. It keeps you inside the security window, makes package compatibility easier, and gives you access to the latest framework improvements.

If upgrades feel scary, that is usually a testing problem or a customization problem, not a reason to stay on an unsupported release.

Laravel's [upgrade guides](https://laravel.com/docs/12.x/upgrade) should be part of your normal maintenance rhythm, not an emergency document you open every two years.

## 2. Keep packages up to date and audited

Laravel makes package-heavy development pleasant, but every dependency is another piece of code you are trusting.

Keep that trust explicit:

- update packages regularly
- remove packages you no longer need
- run `composer audit`
- automate update visibility with Dependabot or Renovate

```bash
composer audit --format=table
```

This is one of the easiest habits to adopt and one of the easiest to postpone until a problem finds you first.

## 3. Prefer Laravel's built-in tools before adding abstractions

A lot of Laravel codebases become harder to maintain because they introduce extra layers before the app has earned them.

Before reaching for a package, repository layer, DTO system, or custom pattern, ask whether Laravel already gives you a clean answer through:

- form requests
- policies
- jobs
- notifications
- API resources
- middleware
- the service container

Extra layers are valuable when they remove real duplication or real coupling. They are not valuable just because the folder tree looks more "enterprise."

## 4. Stay close to Laravel's default structure

Laravel's [directory structure](https://laravel.com/docs/12.x/structure) is a strength, not a beginner compromise.

Following it makes onboarding easier, package integration safer, and upgrades less surprising. It also means most Laravel developers can open your project and find their way around quickly.

If you need the longer argument, I go much deeper in this dedicated guide to [Laravel architecture best practices](/laravel-architecture-best-practices).

## 5. Organize by domain inside the defaults

Sticking to the default structure does not mean throwing every model and controller into one flat directory forever.

One of the best scaling moves in Laravel is grouping code by business domain inside the framework folders.

For example:

```text
app/
  Http/Controllers/Billing/
  Http/Requests/Billing/
  Jobs/Billing/
  Models/Billing/
  Policies/Billing/
```

That gives you the best of both worlds:

- Laravel conventions stay intact
- the business domain stays obvious

## 6. Use route model binding and typed dependencies

Laravel already knows how to resolve many of the things your controllers need.

Use [route model binding](https://laravel.com/docs/12.x/routing#route-model-binding) and typed dependencies so your route and controller code stays focused on behavior instead of lookup glue.

```php
Route::get('/posts/{post:slug}', ShowPostController::class);

final class ShowPostController
{
    public function __invoke(Post $post): View
    {
        return view('posts.show', ['post' => $post]);
    }
}
```

This keeps controllers slimmer and makes failure modes like missing records much more consistent.

## 7. Use form requests for serious validation

If a controller action accepts meaningful input, it probably deserves a form request.

Laravel's [form request validation](https://laravel.com/docs/12.x/validation#form-request-validation) gives you a dedicated place for:

- validation rules
- authorization checks
- input normalization

```php
final class StorePostRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ];
    }
}
```

Then your controller can stick to orchestration:

```php
Post::create($request->validated());
```

In a tiny demo app, an empty submission comes back with a clean error bag before the controller has to guess what went wrong:

![Form request validation errors on a draft form in a fresh Laravel demo app.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/01KM122Z3Z7AYH4VWFQ6TTPX5N.png/public)

If validation is a weak point in your current codebase, this more focused [Laravel validation guide](/laravel-validation) is the next stop.

## 8. Use policies for authorization

Authorization rules become fragile when they are scattered across controllers, Livewire components, jobs, and random helper classes.

Laravel's [policies](https://laravel.com/docs/12.x/authorization#creating-policies) give you a central place for those decisions.

```php
public function update(User $user, Post $post): bool
{
    return $user->id === $post->user_id;
}
```

That keeps permission logic easier to test, easier to change, and much harder to forget on one route.

## 9. Keep controllers thin, and use middleware or invokable controllers when it helps

Controllers should coordinate work, not become a storage unit for every rule in the app.

Use middleware for cross-cutting HTTP concerns like locale detection, rate limits, tenant resolution, or precondition checks.

Use [single-action controllers](https://laravel.com/docs/12.x/controllers#single-action-controllers) when an endpoint is clearer as one focused action instead of another method on a bloated resource controller.

The best controller is usually the one that stays boring.

## 10. Write migrations that are small, explicit, and reversible

Schema changes should live in migrations, not in ad hoc database edits on someone's machine.

Good migration habits look like this:

- one intent per migration
- explicit column changes
- realistic `down()` behavior when rollback matters
- no relying on "we changed it manually in production"

Laravel's default stubs already use anonymous migrations, so class-name collisions are mostly a solved problem now. The bigger best practice is keeping migrations clear enough that teammates can understand what changed without reverse-engineering the database.

If you want a deeper migration-focused refresher, [this migration guide](/laravel-migrations) goes further.

## 11. Follow Eloquent conventions and extract repeated queries with scopes

Eloquent works best when you let it be Eloquent.

That means:

- follow naming conventions
- use relationships instead of ad hoc joins everywhere
- create local scopes for repeated query patterns
- reach for raw SQL only when the query genuinely needs it

You can still optimize hard when necessary, but do not start by fighting the ORM on every line.

## 12. Eager load relationships and enable Eloquent strictness

N+1 queries are one of the easiest Laravel performance problems to create accidentally.

Use eager loading where relationships are clearly needed, and consider enabling [Eloquent strictness](https://laravel.com/docs/12.x/eloquent#configuring-eloquent-strictness) outside production so lazy loading and other mistakes fail loudly while you are still developing.

```php
use Illuminate\Database\Eloquent\Model;

public function boot(): void
{
    Model::shouldBeStrict(! app()->isProduction());
}
```

This one change catches a surprising amount of hidden sloppiness before users pay for it.

Here is the same six-row page rendered two ways in a small demo: once with lazy-loaded authors, then again with `with('user')`:

![Comparison of a Laravel page without eager loading and with with('user'), showing 7 queries versus 2.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/01KM122Z3Z9APEHQ8GM393JP8R.png/public)

## 13. Use modern casts, accessors, and mutators deliberately

Laravel's modern [casts](https://laravel.com/docs/12.x/eloquent-mutators#attribute-casting) and `Attribute` objects make it easy to keep data formatting close to the model without scattering transformation logic everywhere.

Use them for things like:

- booleans
- enums
- money or value objects
- date formatting
- normalized derived attributes

Just avoid turning models into a dumping ground for unrelated presentation logic. Keep the transformation close to the data when it genuinely belongs there.

## 14. Use queues for slow work, and `dispatchAfterResponse()` only for tiny follow-ups

If work is slow, network-bound, or non-essential to the immediate response, it probably belongs on a queue.

Laravel's [queues](https://laravel.com/docs/12.x/queues) are the right home for emails, indexing, third-party syncs, media processing, and similar side effects.

`dispatchAfterResponse()` is still useful, but mainly for tiny follow-up work that can happen right after the response without becoming a real background job strategy.

As a rule of thumb:

- use queues for anything meaningfully slow or retryable
- use `dispatchAfterResponse()` only when the task is truly small and non-critical

## 15. Put most testing effort into feature tests

For most Laravel apps, feature tests give you the best return on effort.

They cover the behavior users and teammates actually care about: routes, middleware, validation, policies, views, JSON responses, and the database working together.

Unit tests still matter for pure logic, but feature tests usually catch the regressions that hurt more.

I rewrote the full version of this advice in the dedicated [Laravel testing best practices](/laravel-testing-best-practices) guide.

## 16. Use factories and `RefreshDatabase` for reliable test setup

Tests become flaky fast when they rely on leftover data or giant seeders.

Laravel's [database testing tools](https://laravel.com/docs/12.x/database-testing) and [factories](https://laravel.com/docs/12.x/eloquent-factories) make it easy to keep setup explicit and isolated.

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
```

The smaller the setup each test needs, the easier the suite is to trust and maintain.

## 17. Fake external HTTP and prevent stray requests

When your app talks to third-party APIs, do not let every test hit the network.

Laravel's [HTTP client testing tools](https://laravel.com/docs/12.x/http-client#testing) let you fake those boundaries and block unexpected outbound calls.

```php
use Illuminate\Support\Facades\Http;

Http::preventStrayRequests();

Http::fake([
    'api.example.com/*' => Http::response(['ok' => true]),
]);
```

This keeps tests faster, cheaper, and more deterministic.

## 18. Run important suites in CI and against real infrastructure where it matters

SQLite and array drivers are fast, but they are not perfect stand-ins for MySQL, PostgreSQL, Redis, or your real queue backend.

You do not need every local test run to mirror production exactly, but you do need CI coverage against the infrastructure that can fail differently in the real world.

At minimum:

- run the suite on every pull request or protected branch push
- use parallel testing when the suite gets slow
- keep real database coverage for behavior that can diverge by engine

Fast feedback is great. Trustworthy feedback is better.

## 19. Keep secrets and generated artifacts out of git

Do not commit `.env`. Do not casually commit generated build artifacts. Do not let secrets drift into screenshots, CI logs, or copied config files.

This sounds basic because it is basic. It is still one of the most common ways teams create avoidable pain.

If you are hardening this side of the stack, the companion [Laravel security best practices](/laravel-security-best-practices) article goes much deeper on secrets, HTTPS, cookies, uploads, and auth.

## 20. Turn every production bug into a regression test

When a real bug shows up, you have just learned something valuable about your app.

Do not fix it and move on empty-handed. Add a test that proves the bug existed, then keep that test after the fix lands.

This is how your suite stops being theoretical coverage and starts becoming a record of lessons your codebase already paid for.

## FAQ

### What are the most important Laravel best practices in 2026?

Stay on supported releases, keep packages updated, stick close to the framework's defaults, validate and authorize with Laravel's built-in tools, and maintain a test suite strong enough to make upgrades boring.

### Should I use service classes for everything in Laravel?

No. Use extra abstractions when they remove real duplication or real coupling. Do not force every controller to call a service class just because it feels more architectural.

### How do I prevent N+1 queries in Eloquent?

Eager load the relationships you know you need and enable Eloquent strictness in local and test environments so lazy-loading mistakes fail earlier.

### When should I use `dispatchAfterResponse()` vs. queues?

Use queues for slow or retryable work. Use `dispatchAfterResponse()` only for tiny, non-critical follow-ups that do not justify a real background job.

## Conclusion

The best Laravel projects are usually the ones that stay pleasantly boring.

They use the framework's defaults, put logic in the right places, keep data and infrastructure mistakes visible, and treat tests as part of the maintenance story instead of a side quest.

If you are tightening a Laravel app instead of starting from scratch, these next reads cover the places where teams usually feel the most pain first:

- [Keep your app structure boring, upgradeable, and easy to onboard](/laravel-architecture-best-practices)
- [Build APIs with cleaner versioning, validation, and response contracts](/laravel-restful-api-best-practices)
- [Catch regressions earlier with stronger Laravel tests](/laravel-testing-best-practices)
- [Close the Laravel security gaps that are easiest to miss](/laravel-security-best-practices)
- [Write validation rules with less guesswork and less controller noise](/laravel-validation)
