---
id: "01KKEW27J0M1WAGATDBXKCE6Y4"
title: "What's new in Pest 3 and how to upgrade"
slug: "pest-3"
author: "benjamincrozat"
description: "Learn what's new in Pest 3: architecture testing presets, mutation testing, and Team Management, plus how to upgrade from Pest 2 to Pest 3 with composer update."
categories:
  - "php"
  - "testing"
published_at: 2024-08-27T00:00:00+02:00
modified_at: 2025-09-30T14:39:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/dAouj0Fvfbxdjns.png"
sponsored_at: null
---
## Introduction to Pest 3

[Pest](https://pestphp.com), my favorite PHP testing framework, has released version 3. It was presented at [Laracon US 2024](https://laracontv.com/laracon-us/2024/introducing-pest-3) by Nuno Maduro, and I could not wait to dive in. In this post, I cover what’s new in Pest 3 and how to upgrade from Pest 2 to Pest 3, with tips for PHPUnit 11 and Laravel testing.

## Is Pest 3 the easiest upgrade ever?

Upgrading to Pest PHP 3 is simple, but use the stable release. Pest 3 requires PHP 8.2 or higher. Update your composer.json, bump any official Pest plugins to ^3.0, and then run a composer update. Full details are in the [Upgrade Guide](https://pestphp.com/docs/upgrade-guide/).

```json
{
  "require-dev": {
    "pestphp/pest": "^3.0"
  }
}
```

Then run:

```bash
composer update
```

Notes:
- Pest 3 is built on PHPUnit 11, which may change how some suites behave. Review the [Upgrade Guide](https://pestphp.com/docs/upgrade-guide/) before updating.
- If you use Laravel, make sure Collision is on v8: `nunomaduro/collision:^8.0`.
- Use `./vendor/bin/pest` to run your tests unless you have a global install.

## What’s new in Pest 3?

### Architecture testing presets

One standout feature is Architecture Testing Presets. These help you enforce rules and best practices without writing everything by hand. Confirmed presets are:

- php
- security
- laravel
- strict
- relaxed

Use them like this:

```php
arch()->preset()->laravel();
```

And here’s how you can use the other presets:

```php
arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->strict();
arch()->preset()->relaxed();
```

Need exceptions? Skip specific files or namespaces with `ignoring()`:

```php
arch()->preset()->laravel()->ignoring('App\\Models\\Scopes');

// or with a class constant
arch()->preset()->laravel()->ignoring(App\Models\Scopes::class);
```

I wrote more about this in my [guide to architecture testing presets in Pest 3](/pest-3-architecture-testing-presets). For an overview of presets and `ignoring()`, see the [Pest 3 announcement](https://pestphp.com/docs/pest3-now-available).

### Mutation testing: how reliable are your tests?

Mutation testing makes small changes (mutations) to your code and checks if your tests catch them. It is a great way to measure test quality.

Install the plugin:

```bash
composer require pestphp/pest-plugin-mutate --dev
```

Run mutation tests:

```bash
./vendor/bin/pest --mutate
```

Tip: add `--parallel` to speed things up on larger suites.

![Pest's mutation tests in action.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/user-attachments/assets/fe303b15-3a35-4f8b-8a6b-f066e566576c)

As you can see, this is a disaster. But in my defense, it is a new project and a work in progress!

Read more in the [Mutation Testing docs](https://pestphp.com/docs/mutation-testing) and the [Pest Mutate plugin repository](https://github.com/pestphp/pest-plugin-mutate).

### Team Management

Team Management lets you track todos, notes, assignees, issues, and pull requests right from your tests and the CLI. To link to GitHub, first configure your project in `tests/Pest.php`:

```php
pest()->project()->github('org/repo');
```

Now you can mark work as todo or done, and attach context:

```php
test('something happens when…', function () {
    // …
})->todo(
    assignee: 'benjamincrozat',
    issue: 42,
    note: 'Focus on optimizing the user lookup query.'
);

test('an event is triggered when…', function () {
    // …
})->done()->pr(101);
```

You can also link on groups:

```php
describe('auth', function () {
    // …
})->issue(13);
```

CLI filters make it easy to focus your run:

```bash
./vendor/bin/pest --todos
./vendor/bin/pest --notes
./vendor/bin/pest --assignee=benjamincrozat
./vendor/bin/pest --issue=11
./vendor/bin/pest --pr=1
```

See the [Team Management docs](https://pestphp.com/docs/team-management) and the [Pest CLI reference](https://pestphp.com/docs).

### Nested describes

Pest 3 lets you nest `describe` groups to organize related tests and share hooks more clearly.

```php
describe('API', function () {
    describe('Auth', function () {
        test('logs in', function () {
            // …
        });
    });
});
```

Learn more in the [Pest 3 announcement](https://pestphp.com/docs/pest3-now-available).

### New configuration API

There is a new, fluent configuration API in `tests/Pest.php` so you can define project-level settings and integrations (like GitHub) in one place using `pest()` helpers. See the [Pest 3 announcement](https://pestphp.com/docs/pest3-now-available) for examples.

## Conclusion

Pest 3 brings five big wins: architecture testing presets, Mutation Testing, Team Management, nested describes, and a new configuration API. Upgrading is straightforward: require `pestphp/pest:^3.0`, ensure PHP 8.2+, update plugins to ^3.0, and run `composer update`. Because Pest 3 is built on PHPUnit 11, review the official [Upgrade Guide](https://pestphp.com/docs/upgrade-guide/) and refer to the docs for [Team Management](https://pestphp.com/docs/team-management), [Mutation Testing](https://pestphp.com/docs/mutation-testing), and the [Pest 3 announcement](https://pestphp.com/docs/pest3-now-available). Now run `./vendor/bin/pest` and enjoy faster, clearer Laravel testing with Pest PHP 3.

If you want a few more PHP rabbit holes after this:

- [What’s new in Pest 4 and how to upgrade](/pest-4)
- [A Guide to architecture testing presets in Pest 3](/pest-3-architecture-testing-presets)
- [9 testing best practices for Laravel in 2025](/laravel-testing-best-practices)

