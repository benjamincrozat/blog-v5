---
id: "01KKEW27E0BT000MHAZA3QWTQ2"
title: "10 Laravel testing best practices for 2026"
slug: "laravel-testing-best-practices"
author: "benjamincrozat"
description: "Write Laravel tests that catch real regressions: use Pest, favor feature tests, isolate data, fake externals, and run real infrastructure where it matters."
categories:
  - "laravel"
  - "testing"
published_at: 2023-10-26T22:00:00Z
modified_at: 2026-03-12T21:34:19Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/XFUxcY7SQzhmvZI.jpg"
sponsored_at: null
---
## Introduction

Laravel ships with testing in mind, and the official [testing documentation](https://laravel.com/docs/12.x/testing) now treats Pest and PHPUnit as first-class options out of the box.

That does not automatically give you a useful test suite, though.

A useful Laravel test suite catches regressions without turning every refactor into a fight. It stays fast enough to run often, clear enough that teammates trust it, and realistic enough that it does not miss bugs your production stack would have caught.

If you want the short version, this is what matters most:

- use Pest unless your team already has a strong reason not to
- write more feature tests than unit tests in most app code
- keep test data isolated and minimal
- fake external boundaries, not your entire application
- run important suites against the real database or infrastructure when differences matter

If your goal is not just "more tests" but safer changes across the whole app, pair this with my broader [Laravel best practices](/laravel-best-practices) article.

## 1. Use Pest unless your team is already deeply invested in PHPUnit

[Pest](https://pestphp.com) sits on top of PHPUnit, so you still get the same ecosystem while writing tests with less boilerplate.

That makes it a great default for new Laravel projects and for teams that want tests to feel lighter to write and review.

```php
it('shows the login screen', function () {
    $this->get('/login')
        ->assertOk();
});
```

You do not have to rewrite an existing PHPUnit-heavy codebase overnight just to be fashionable. But if you are starting fresh, Pest is usually the better on-ramp.

The main benefit is not syntax for syntax's sake. It is that a lower-friction testing experience makes it more likely the team will keep adding useful tests.

## 2. Put most of your effort into feature tests

For most Laravel applications, feature tests are where the real value is.

They exercise routes, middleware, validation, policies, controllers, views or JSON responses, and the database together. That makes them much better at catching the kind of regressions users actually feel.

Unit tests still matter, especially for pure domain logic or complicated transformations, but most web apps get more safety from a strong feature suite than from dozens of tiny tests around framework glue.

```php
it('publishes a post', function () {
    $author = User::factory()->create();

    $this->actingAs($author)
        ->post('/posts', [
            'title' => 'Testing pays off',
            'body' => 'A short post body.',
        ])
        ->assertRedirect();

    expect(Post::query())->toHaveCount(1);
});
```

That is also why my [Laravel REST API best practices](/laravel-restful-api-best-practices) guide leans so heavily on endpoint tests: the contract matters more than your private implementation details.

## 3. Keep each HTTP test focused on one request

Laravel's [HTTP testing documentation](https://laravel.com/docs/12.x/http-tests) explicitly warns that each test should only make one request to the application.

That is easy to ignore when you are in a hurry, but it is good advice.

A focused HTTP test is easier to reason about and easier to debug. It also makes failures more obvious because one test covers one behavior.

So prefer this:

```php
it('redirects guests to login when they try to create a post', function () {
    $this->post('/posts', [
        'title' => 'Blocked',
        'body' => 'Guests should not create posts.',
    ])->assertRedirect('/login');
});
```

Over one giant test that tries to create a record, follow redirects, load another page, submit a form again, and assert six different outcomes.

## 4. Reset state with `RefreshDatabase` and factories

The fastest way to make a test suite flaky is to let tests leak state into each other.

Laravel's [database testing tools](https://laravel.com/docs/12.x/database-testing) and [Eloquent factories](https://laravel.com/docs/12.x/eloquent-factories) are the simplest way to keep test setup predictable.

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows only published posts', function () {
    Post::factory()->published()->create(['title' => 'Visible']);
    Post::factory()->draft()->create(['title' => 'Hidden']);

    $this->get('/posts')
        ->assertOk()
        ->assertSee('Visible')
        ->assertDontSee('Hidden');
});
```

I strongly prefer creating the minimum records a test needs instead of seeding a giant shared database state.

That keeps tests:

- easier to read
- less brittle
- less coupled to global fixtures

When a test needs five different seeders before it can start, it is usually hiding too much setup.

## 5. Fake external boundaries and block stray requests

Third-party APIs, mail providers, queues, storage backends, and webhooks should not be exercised live in most automated tests.

Laravel gives you fakes for exactly this reason, and the [HTTP client testing tools](https://laravel.com/docs/12.x/http-client#testing) are especially useful for API-heavy apps.

```php
use Illuminate\Support\Facades\Http;

Http::preventStrayRequests();

Http::fake([
    'api.example.com/posts' => Http::response([
        'data' => [
            ['id' => 1, 'title' => 'Hello'],
        ],
    ]),
]);
```

This is better than hitting the real API because your tests become:

- faster
- cheaper
- deterministic

The key nuance is that you should fake *boundaries*, not every meaningful piece of your own application.

If your test fakes half your internals, it may stop proving anything important.

## 6. Use the real database where differences actually matter

I would not tell every team to run every test against the full production stack locally all the time.

I *would* say this: if production runs MySQL or PostgreSQL and your test suite only ever runs on SQLite, you should expect blind spots.

Database engines differ on things like:

- JSON behavior
- foreign key enforcement
- transaction behavior
- sorting quirks
- strict SQL modes
- full-text search or generated columns

So the pragmatic move is:

- keep fast local feedback loops when you need them
- run critical suites against the same database engine as production in CI
- use the real cache or queue backend where the behavior can diverge materially

Blanket speed is not the goal. Trustworthy feedback is.

## 7. Turn every bug fix into a regression test

When a bug reaches production, you have just been handed a perfect testing opportunity.

Before or while fixing it, add a test that proves the bug exists. Then make the fix and keep that test forever.

```php
it('requires a valid email address', function () {
    $this->post('/contact', [
        'email' => 'not-an-email',
        'message' => fake()->paragraph(),
    ])->assertInvalid(['email']);
});
```

This practice compounds over time. Your suite stops being theoretical coverage and starts becoming a library of production lessons your codebase has already paid for.

It also pairs nicely with good validation and HTTP error handling, so [the validation guide](/laravel-validation) and [this HTTP client error-handling article](/error-handling-laravel-http-client) are worth reading alongside it.

## 8. Run tests in parallel and in CI

A test suite nobody runs is not protection. It is decoration.

Laravel supports [parallel testing](https://laravel.com/docs/12.x/testing#running-tests-in-parallel), and you should use it when the suite is large enough to feel slow.

```bash
php artisan test --parallel
```

Then run the suite in CI on every pull request or push to a protected branch.

The goal is not a baroque pipeline. The goal is simple:

- fast local feedback for everyday work
- automated checks before merge or deploy
- no relying on memory to decide whether the app still works

If you do deploy automatically, gate that deployment on passing tests instead of optimism.

## 9. Use architecture tests for rules the team keeps forgetting

Some testing rules are not about user flows. They are about keeping the codebase organized.

That is where Pest's [architecture testing](https://pestphp.com/docs/arch-testing) becomes useful.

```php
arch()
    ->expect('App\Models')
    ->toExtend('Illuminate\\Database\\Eloquent\\Model');
```

You can use this style of test to enforce rules such as:

- controllers stay in the HTTP layer
- models extend the right base class
- support code does not depend on controllers
- specific namespaces do not import forbidden dependencies

If you want a shortcut into this area, my post on [Pest architecture testing presets](/pest-3-architecture-testing-presets) gives you a practical starting point.

## 10. Optimize for readable tests, not clever tests

The best tests are boring to read in the best possible way.

You should be able to scan a test and answer these questions immediately:

- what behavior is being exercised?
- what input was provided?
- what output or side effect is expected?

That is why I prefer tests that are:

- named after behavior, not implementation details
- short enough to understand in one pass
- explicit about the important setup
- specific in their assertions

In other words, provide input and assert output.

Do not over-couple tests to private method names, internal helpers, or irrelevant implementation details if the public behavior has not changed. Refactors should be able to move code around without forcing a rewrite of every good test.

## FAQ

### Should I use Pest or PHPUnit for Laravel?

Use Pest by default for new Laravel work unless your team already has strong PHPUnit conventions and no desire to change. Pest gives you the same ecosystem with less friction.

### Feature tests or unit tests first?

In most Laravel applications, feature tests first. They cover more real behavior and catch more expensive regressions. Add unit tests for pure logic or tricky transformations where they genuinely help.

### Is SQLite okay for Laravel tests?

Sometimes, yes. But if production uses MySQL or PostgreSQL, do not assume SQLite is a perfect substitute. Keep at least some CI coverage against the real database engine for risky behavior.

### How many requests should one HTTP test make?

One. That is Laravel's guidance, and it keeps tests easier to understand and debug.

## Conclusion

Strong Laravel testing is less about chasing a percentage and more about building a feedback system you trust.

Use Pest, lean on feature tests, isolate your data, fake the slow outer world, run real infrastructure where differences matter, and turn every bug into a permanent lesson. Do that consistently, and your suite will save you from the regressions that actually hurt.

Once your suite starts saving you from real regressions, these next reads help tighten the boundaries around what you are testing:

- [See what changed if you're moving your suite to Pest 4](/pest-4)
- [Use Pest architecture presets without wiring everything from scratch](/pest-3-architecture-testing-presets)
- [Tighten your API contract so tests guard something stable](/laravel-restful-api-best-practices)
- [Handle HTTP integration failures without a maze of conditionals](/error-handling-laravel-http-client)
- [Pick up the Laravel habits that make tests easier to maintain](/laravel-best-practices)
