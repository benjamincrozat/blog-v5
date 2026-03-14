---
id: "01KKK39V3D0B2F3S4S05BQRSWA"
title: "Inertia.js v3 beta adds async requests, optimistic UI, and no Axios"
slug: "inertia-js-v3-beta"
author: "benjamincrozat"
description: "Inertia.js v3 entered beta on March 5, 2026. The big shifts for Laravel teams are async requests, optimistic updates, infinite scrolling, and dropping the Axios dependency."
categories:
  - "laravel"
  - "news"
published_at: 2026-03-13T07:59:13+00:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/inertia-js-v3-beta.png"
sponsored_at: null
---
Inertia.js v3 entered beta on March 5, 2026, and it looks like a meaningful upgrade for Laravel teams that like the Inertia model. The official [beta announcement](https://laravel.com/blog/inertiajs-v3-is-now-in-beta) and [v3 upgrade guide](https://inertiajs.com/upgrade-guide) point to the same themes: fewer moving parts, more built-in frontend power, and a stronger async story.

The headline changes are easy to summarize:

- no more Axios dependency
- native Wayfinder support
- async requests
- optimistic updates
- infinite scroll
- improved polling, prefetching, and deferred prop handling

That is enough to make v3 worth watching, even if I would not rush a stable production app onto a beta just yet.

## The biggest practical change: no more Axios

For a lot of teams, the most noticeable shift is also the simplest one. Inertia v3 [drops the Axios dependency](https://laravel.com/blog/inertiajs-v3-is-now-in-beta), which means one less layer to think about when debugging requests and one less frontend default to inherit automatically.

I like this change. It makes Inertia feel more self-contained and a little easier to reason about.

The same beta announcement also says v3 brings native [Wayfinder](https://laravel.com/blog/inertiajs-v3-is-now-in-beta) support, plus simpler auth and middleware handling. That continues the same pattern: reduce glue code, reduce incidental complexity, keep the Laravel-to-frontend workflow tighter.

## Async requests and optimistic UI are the real story

The more important shift is Inertia's new async behavior.

The official announcement highlights [async requests](https://laravel.com/blog/inertiajs-v3-is-now-in-beta), [optimistic updates](https://laravel.com/blog/inertiajs-v3-is-now-in-beta), and [infinite scrolling](https://laravel.com/blog/inertiajs-v3-is-now-in-beta) as first-class features in v3. That gives Inertia teams better tools for building interfaces that feel modern without abandoning the server-driven model that made them choose Inertia in the first place.

This is the part that feels strategically important to me.

For years, the trade-off around Inertia has often sounded like this: "You keep Laravel routing and controller flow, but you accept a few frontend compromises compared with a pure SPA stack." Inertia v3 looks like a direct attempt to narrow that gap.

If async requests, optimistic UI, and infinite scroll land well in real apps, that is a bigger win than any single syntax tweak.

## There is also a lot of quality-of-life cleanup

The beta is not only about headline UI features.

The official release post also calls out:

- improved polling
- improved prefetching
- better deferred props support
- merging props into history state
- more powerful forms

That collection matters because it touches the spots where Inertia apps usually start to feel either elegant or awkward. A release like this can make a framework feel much more capable without changing its identity.

## Version requirements are worth checking now

The official beta announcement says Inertia v3 requires:

- PHP `8.2+`
- Laravel `11+`
- React `19+` for the React adapter
- Svelte `5+` for the Svelte adapter

That alone means some teams should treat v3 as a planning item, not an immediate upgrade.

If you are on modern Laravel already, that is probably fine. If you have a Laravel 10 app or older frontend dependencies hanging around, the beta is a signal to start inventorying what will need to move first.

Laravel 12's starter kits already made [Inertia 2 part of the default conversation](/laravel-12), so v3 is worth tracking even if you are not ready to adopt it today.

## Should Laravel teams upgrade now?

For most teams, no. Not yet.

It is a beta, and betas are for trying the new workflow, checking package compatibility, and finding sharp edges before the stable release. That is especially true if your app depends on a stack of frontend libraries around React, Vue, or Svelte.

But I do think Laravel teams should start evaluating it now if:

- your UI increasingly wants async interactions without going full SPA
- you have been fighting Inertia around loading states, infinite lists, or optimistic updates
- you maintain starter kits, templates, or reusable Inertia-heavy app foundations

That is the sweet spot for beta testing: not "ship immediately," but "learn whether this unlocks a cleaner direction for your next few months of work."

## My take

Inertia v3 beta looks like the right kind of release.

It does not try to become a different tool. It tries to make the Inertia approach stronger where teams actually feel the pain: request handling, UI responsiveness, and common app patterns like polling, forms, and long lists.

The Axios removal is nice. The Wayfinder support is nice. But the async and optimistic features are what make this beta matter.

If those hold up through the beta cycle, Inertia v3 could end up being one of the more important Laravel-adjacent frontend releases of the year.

If you are deciding where this fits in your own stack, these are the next reads I would keep open:

- [See why Inertia mattered so much in Laravel 12's starter kits](/laravel-12)
- [Refresh the Laravel + Vue mental model before you evaluate v3](/laravel-vue)
- [Compare this direction with Livewire's SPA-style navigation](/livewire-spa-wire-navigate)
- [Track what Laravel 13 is shaping up to include too](/laravel-13)
