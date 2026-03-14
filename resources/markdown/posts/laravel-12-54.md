---
id: "01KKK2B0H9HG949NEPS1SQGKCF"
title: "Laravel 12.54 adds tsvector, better download assertions, and more"
slug: "laravel-12-54"
author: "benjamincrozat"
description: "Laravel 12.54.0 adds PostgreSQL tsvector columns, BinaryFileResponse test assertions, Model::withoutRelation(), and several queue and validation fixes. Laravel 12.54.1 is a tiny follow-up release."
categories:
  - "laravel"
  - "news"
published_at: 2026-03-13T07:41:39+00:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/laravel-12-54.png"
sponsored_at: null
---
Laravel shipped [`v12.54.0`](https://github.com/laravel/framework/releases/tag/v12.54.0) on March 10, 2026, then followed it with [`v12.54.1`](https://github.com/laravel/framework/releases/tag/v12.54.1) a few hours later. The second tag is tiny and only makes imports consistent, so the real story is what landed in `12.54.0`.

The highlights are practical ones: PostgreSQL `tsvector` columns, better test assertions for `BinaryFileResponse`, a new `Model::withoutRelation()` helper, and several fixes around queues, URL validation, and test helpers. These APIs are already live in `laravel/framework v12.54.1`, so this is more than a release-note preview.

## The two additions I think most teams will feel first

### PostgreSQL gets first-party `tsvector` columns

Laravel now includes a [`tsvector` column type for PostgreSQL](https://github.com/laravel/framework/pull/59004). If you already use Postgres full-text search, this is one of those changes that quietly removes some friction from migrations.

Instead of dropping down to raw SQL for the column type, you can now express it directly in your schema:

```php
Schema::table('articles', function (Blueprint $table) {
    $table->tsvector('searchable_text')->nullable();
});
```

That is not a flashy feature, but it is a good Laravel feature. It makes a real database capability feel native inside the framework.

### Download tests now handle `BinaryFileResponse` better

Laravel also added [assertion support for `BinaryFileResponse`](https://github.com/laravel/framework/pull/59018). If you test file downloads, that is the kind of improvement that saves a little confusion every time you touch those tests.

The intended shape is straightforward:

```php
$response = $this->get('/export');

$response->assertDownload('report.csv');
```

This matters because download endpoints often look simple until they start returning a real file response instead of a plain response object. Laravel smoothing that edge is a nice quality-of-life win for teams that test exports, invoices, backups, or generated archives.

## Another small addition that feels more useful than it sounds

Laravel 12.54 also adds [`Model::withoutRelation()`](https://github.com/laravel/framework/pull/59137) for selective relation unloading.

If you have ever loaded a model with several relationships and then needed a lighter clone without one of them, this is cleaner than manually poking at the relation array:

```php
$stripped = $post->withoutRelation('comments');
```

That is a narrow helper, but I can see it being handy in jobs, notifications, transformations, and places where you want to reuse a model instance without carrying every loaded relation forward.

## The fixes worth noticing

The rest of the release is mostly fixes, but a few of them are worth calling out because they touch real production pain:

- Laravel fixed [URL validation for punycode subdomains](https://github.com/laravel/framework/pull/58982), which matters if your app accepts internationalized domain names.
- It fixes a [queue deadlock when reserving a job throws an exception](https://github.com/laravel/framework/pull/58978), including cases like attempts overflow.
- It fixes a Redis throttle bug where the [`after` callback could be ignored](https://github.com/laravel/framework/pull/58990).
- It adds the [oldest pending job to `queue:monitor` output](https://github.com/laravel/framework/pull/59073), which is a small but genuinely useful observability improvement.
- It also includes [improved HTML test helpers](https://github.com/laravel/framework/pull/59140), plus a handful of migration, cache, filesystem, and mail-related fixes.

None of that is headline material on its own. Together, though, it makes `12.54.0` feel like the kind of mature Laravel point release most teams appreciate: one or two developer-facing features, then a stack of bug fixes in boring-but-important corners.

## Should you update right away?

If you are already on Laravel 12, I do not see anything scary in this release from the public notes. On the contrary, this looks like a comfortable update for most applications.

The main reason to move quickly is if any of these match your stack:

- you use PostgreSQL full-text search and want first-party `tsvector` migrations
- you test file downloads and want better assertions
- you run busy queues and want the queue fixes
- you validate user-supplied internationalized URLs

If none of those apply, this is still a nice maintenance release, just not an urgent one.

It is also a useful reminder that Laravel 12 is still moving while [Laravel 13](/laravel-13) has not been tagged as stable yet. If you want the bigger framework picture, my [Laravel 12 overview](/laravel-12) and [Laravel versions reference](/laravel-versions) are the better pages for roadmap context.

## My take

This is a good example of what Laravel minor releases do well in 2026. They do not need a dramatic keynote feature to matter. A cleaner Postgres migration API, more reliable queue behavior, and better testing ergonomics are exactly the kind of changes that make a framework feel well cared for.

The part I like most is probably the `tsvector` addition. It is not huge, but it shows Laravel continuing to turn real database features into first-class framework APIs instead of leaving them in raw-SQL territory.

If you are working through framework updates instead of waiting for them to pile up, these are the next reads I would keep open:

- [See what Laravel 13 looks like before it ships](/laravel-13)
- [Get the broader context behind Laravel 12](/laravel-12)
- [Tighten up your test suite before the next upgrade](/laravel-testing-best-practices)
- [Double-check which Laravel version is actually running](/check-laravel-version)
