---
id: "01KKEW27J7X8WM7Q9SFDW9YEQH"
title: "What’s new in Pest 4 and how to upgrade"
slug: "pest-4"
author: "benjamincrozat"
description: "Pest 4 lands with first-class browser testing (Playwright-powered), smoke & visual regression checks, test sharding, faster type coverage, and small but handy CLI/arch tweaks. Here’s what’s new and how to upgrade from Pest 3."
categories:
  - "php"
  - "testing"
published_at: 2025-09-30T14:48:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6DHMBV3J8MQ1BFMV4DG1T2R.png"
sponsored_at: null
---
## Introduction to Pest 4

Pest 4 is the biggest leap the framework has made since its birth: it brings **built-in browser testing** that actually feels like writing unit tests, plus useful quality gates and CI scalability features. In this post I cover the highlights and give you a dead-simple upgrade path from Pest 3.

## Is Pest 4 as easy to upgrade as version 3?

Pretty much**as long as you’re on PHP 8.3+** and willing to bump your Pest plugins. Pest 4 sits on **PHPUnit 12**, so you inherit PHPUnit’s behavior changes as well. Start by updating your dev deps, then run a composer update.

```json
{
  "require-dev": {
    "pestphp/pest": "^4.0"
  }
}
```

Then:

```bash
composer update
```

Notes that matter:

* Pest **requires PHP 8.3+** and runs **on top of PHPUnit 12**. Read the Upgrade Guide and PHPUnit 12 notes if you maintain a big suite.
* **Update official Pest plugins to `^4.0`** (browser, type-coverage, mutate, etc.).
* If you use Laravel, Collision **8.x** remains the right line for Laravel 11/12 and works fine alongside Pest 4.
* Using snapshot tests? Pest 4 **changes snapshot naming**, so regenerate them with `--update-snapshots`.

## What’s new in Pest 4?

### Browser testing that doesn’t fight you

Pest 4 adds first-class, **Playwright-based** browser testing with **Laravel testing API support**, parallel runs, device emulation, and light/dark mode toggles. It’s the first browser runner that feels ergonomic inside PHP.

Install once:

```bash
composer require pestphp/pest-plugin-browser --dev
npm install playwright@latest
npx playwright install
```

A tiny example:

```php
it('lets users sign in', function () {
    $page = visit('/')->on()->mobile()->inDarkMode();
    $page->click('Sign In')
         ->fill('email', 'user@example.com')
         ->fill('password', 'secret')
         ->press('Submit')
         ->assertSee('Dashboard')
         ->assertNoJavascriptErrors();
});
```

You can also pick browsers (`--browser firefox|safari`), emulate devices (`->on()->iPhone14Pro()`), set geolocation/timezone/locale, and even **pause or `->tinker()`** for debugging. There’s built-in **`assertNoAccessibilityIssues()`** and a **visual diff** assertion (see below).

### One-liner smoke tests (JS/console clean)

Want to sanity-check your whole app? This assertion crawls routes and fails on JS errors or console logs:

```php
visit(['/', '/about', '/contact'])->assertNoSmoke();
```

It’s a practical “break-glass” test before deploys.

### Visual regression testing

Snapshot pixels, not just strings:

```php
visit(['/', '/about'])->assertScreenshotMatches();
```

Great for UI-heavy apps and CSS refactors.

### Test sharding for real CI speed

Split the suite across machines with `--shard=1/4`, `--shard=2/4`, etc., and combine with `--parallel`. CI finally scales horizontally without hacks.

```bash
./vendor/bin/pest --parallel --shard=1/4
```

### Type coverage, now actually fast

The type-coverage engine is **~2× faster on first run and near-instant afterwards**, and it supports sharding. If you care about strict typing, this removes the pain.

### Profanity checker (yes, really)

Keep code comments and constants professional with `--profanity`, with language filters and include/exclude lists. It’s opt-in via a plugin:

```bash
composer require pestphp/pest-plugin-profanity --dev
./vendor/bin/pest --profanity
```

Silly? Maybe. Useful for teams and OSS? Definitely.

### Small but handy refinements

* **`skipLocally()` / `skipOnCi()`** for environment-conditional tests.
* New arch/expectations like **`not->toHaveSuspiciousCharacters()`** (enabled on the `php` preset) and **`toBeSlug`**.
* Still all the good stuff from v3: architecture presets, mutation testing, team management, etc. (unchanged concepts, new base).

## Upgrade guide: Pest 3 → Pest 4 (copy/paste)

1. Bump PHP and packages:

```json
{
  "require": {
    "php": "^8.3"
  },
  "require-dev": {
    "pestphp/pest": "^4.0",
    "pestphp/pest-plugin-browser": "^4.0",        // if you’ll use browser tests
    "pestphp/pest-plugin-type-coverage": "^4.0"   // optional but recommended
  }
}
```

2. Update & install Playwright if using browser tests:

```bash
composer update
npm install playwright@latest
npx playwright install
```

3. Snapshots? Regenerate names:

```bash
./vendor/bin/pest --update-snapshots
```

4. Remove archived plugins (`pest-plugin-watch`, `pest-plugin-faker`) if present.

5. On Laravel, keep **Collision 8.x** for Laravel 11/12; PHPUnit 12 compatibility is fine.

## Practical tips (from running real suites)

* **Start with smoke + a11y + visuals** on a small route list. Expand once CI is green.
* **Shard by test type** (browser shards separate from unit/feature) to keep feedback loops tight.
* For flaky UI, prefer **text selectors or `data-test` hooks** (`@login`) over brittle CSS. It’s all in the Browser Testing API.
* If you’re coming from Dusk/Selenium, the **Playwright engine** plus Laravel helpers is the best of both worlds. Less ceremony, more signal.

## Conclusion

Pest 4 is a serious upgrade: **Playwright-powered browser tests**, **smoke & visual checks**, **sharding**, **faster type coverage**, and a few thoughtful niceties. Upgrade is straightforward: PHP 8.3+, bump to `pest:^4.0` (and plugins), regenerate snapshots if needed, and start adding browser coverage where it makes you money. If you test Laravel for a living, this is worth the jump.

If you want a few more PHP rabbit holes after this:

- [What's new in Pest 3 and how to upgrade](/pest-3)
- [A Guide to architecture testing presets in Pest 3](/pest-3-architecture-testing-presets)
- [9 testing best practices for Laravel in 2025](/laravel-testing-best-practices)

