# SEO editorial plan

Last updated: March 16, 2026
Source brief: `/Users/benjamin/Downloads/PLAN.md`

This is the working SEO backlog for the next article batch. We should move through it from top to bottom unless fresh Search Console or SERP data changes the priority. Check an item only when the article is drafted, reviewed, synced, and ready to publish.

## What is already working

- Your strongest feeder pages are practical PHP and Laravel posts, not news.
- The clearest opportunity is to beat reference-style results with better tutorials, sharper examples, version notes, and comparison guidance.
- For package and framework topics, the winning angle is usually the task behind the tool, not the package name alone.

## Definition of done for every article

- Check the live Google US desktop SERP before drafting and review the top 3 organic results.
- Verify version-sensitive claims with official docs, release notes, specs, or direct testing.
- Draft at least 5 title options before choosing the final title.
- Open with a clear payoff, then teach through practical examples rather than bare syntax.
- Add screenshots, crops, diagrams, or comparison visuals only when they materially improve clarity or prove first-hand use.
- Add strong internal links in the body and refresh the related-posts block.
- Generate or upload the featured image once the copy is stable.
- Run `php artisan app:sync-posts` when the article is ready.

## Write first

- [x] `php implode`
  - Angle: a practical "array to string" guide instead of a syntax reference.
  - Must cover: separators, quoted output, associative-array caveats, empty arrays, and `implode()` vs `explode()`.
  - Visual plan: code and output blocks should be enough unless a comparison table makes the tradeoffs clearer.
  - Why first: the SERP is beatable after the manual because few results feel like the clearest real-world tutorial.

- [x] `php json_decode`
  - Angle: a troubleshooting-first guide for decoding JSON safely in PHP.
  - Must cover: associative arrays vs objects, exceptions, invalid JSON, depth, flags, and safe decoding patterns.
  - Visual plan: no screenshots by default; use before/after outputs and error examples.
  - Why first: the query has high confusion intent and most pages stop at syntax.

- [x] `php string contains`
  - Angle: answer "how do I check if a string contains something in PHP?" with the right function for modern and older versions.
  - Must cover: `str_contains()`, `strpos()` for older PHP, case sensitivity, and common mistakes.
  - Visual plan: code/output examples and a decision table should be enough.
  - Why first: the SERP is split between two functions, which creates room for a cleaner decision guide.

- [ ] `php substr`
  - Angle: a practical substring guide, not a shallow function reference.
  - Must cover: positive offsets, negative offsets, length behavior, falsey-looking outputs, and when to switch to `mb_substr()`.
  - Visual plan: examples plus a compact behavior table.
  - Why first: many competing pages stay thin on multibyte handling and edge cases.

- [ ] `php isset`
  - Angle: make this the clearest `isset()` vs `empty()` vs `??` guide for everyday PHP.
  - Must cover: arrays, forms, object properties, null values, undefined keys, and comparison-driven examples.
  - Visual plan: no screenshots needed; code comparisons should carry the piece.
  - Why first: searchers are usually confused and comparison-style content is more useful than the manual.

- [ ] `php trim`
  - Angle: explain how to clean user input and why whitespace bugs keep slipping through.
  - Must cover: invisible whitespace, custom character masks, line breaks, Unicode gotchas, and why `trim()` sometimes appears not to work.
  - Visual plan: use code plus visible output markers; no screenshots unless needed to show hidden characters more clearly.
  - Why first: the SERP is relatively soft beyond the PHP manual.

- [ ] `php date format`
  - Angle: a practical formatting guide with copy-ready patterns.
  - Must cover: `date()` vs `DateTimeImmutable::format()`, common patterns, escaping, timestamps, time zones, and when `date()` is not enough.
  - Visual plan: a compact cheat sheet table is likely more useful than screenshots.
  - Why first: most searchers want examples, not two separate manual pages.

- [ ] `php array length`
  - Angle: answer the question fast with `count()`, then explain the real edge cases.
  - Must cover: normal arrays, multidimensional arrays, `COUNT_RECURSIVE`, `Countable` objects, and common misunderstandings.
  - Visual plan: no screenshots; examples and small comparison blocks are enough.
  - Why first: the top blog-style result looks beatable.

- [ ] `php array push`
  - Angle: show when `array_push()` is useful and when `$array[] = ...` is the better default.
  - Must cover: single values, multiple values, readability, performance tradeoffs, and team-style guidance.
  - Visual plan: code comparisons only.
  - Why first: ranking pages explain syntax but rarely help readers pick the better pattern.

- [ ] `php array_merge`
  - Angle: a decision guide around `array_merge()` vs `+` vs the spread operator.
  - Must cover: numeric keys, string keys, overwrite behavior, preserving keys, and real-world merge patterns.
  - Visual plan: a behavior matrix will likely help more than screenshots.
  - Why first: the SERP has strong comparison intent, but the current pages do not organize it cleanly.

- [ ] `php string length`
  - Angle: the clearest `strlen()` vs `mb_strlen()` explanation for modern PHP.
  - Must cover: byte length vs character length, Unicode examples, multibyte bugs, and when to choose each function.
  - Visual plan: side-by-side output examples and a quick rule-of-thumb box.
  - Why first: the SERP looks unusually weak beyond the manual.

- [ ] `laravel pivot table`
  - Angle: an end-to-end many-to-many guide with realistic examples.
  - Must cover: migrations, models, `belongsToMany`, extra pivot fields, `attach()`, `sync()`, `syncWithoutDetaching()`, and `updateExistingPivot()`.
  - Visual plan: add a simple relationship diagram and consider screenshots only if the article includes a UI workflow.
  - Why first: tutorial-style content already proves it can compete here if the example is concrete enough.

## Write next

- [ ] `php error_log`
  - Angle: explain where PHP logs go and how to log useful custom messages without guessing.
  - Must cover: Apache, Nginx, local dev, Docker gotchas, `php.ini`, custom paths, and practical debugging patterns.
  - Visual plan: screenshots may help if we show log locations in a real environment; otherwise code and config examples are enough.

- [ ] `php parse_url`
  - Angle: a safe URL parsing guide built around real broken inputs.
  - Must cover: missing schemes, relative URLs, query strings, `parse_str()`, validation, and extraction pitfalls.
  - Visual plan: no screenshots needed.

- [ ] `php round`
  - Angle: explain rounding without leaving finance and precision traps unexplained.
  - Must cover: precision, halves, rounding modes, float surprises, and money-related caveats.
  - Visual plan: no screenshots; examples and edge-case tables should carry it.

- [ ] `php fopen`
  - Angle: a practical file-handling guide rather than a mode list.
  - Must cover: modes, file creation behavior, relative vs absolute paths, read/write patterns, locking, and safer alternatives when relevant.
  - Visual plan: no screenshots by default.

- [ ] `php include`
  - Angle: clarify `include`, `require`, `include_once`, and `require_once` with consequences that matter in production.
  - Must cover: warnings vs fatal errors, duplicate loads, return values, and modern project guidance.
  - Visual plan: no screenshots needed.

- [ ] `laravel redis`
  - Angle: use Redis in Laravel for concrete jobs instead of explaining Redis in the abstract.
  - Must cover: cache, queues, sessions, rate limiting, local setup, config, and common production pitfalls.
  - Visual plan: screenshots may help for Horizon, logs, or local tooling if those examples add proof.

- [ ] `laravel subquery`
  - Angle: show how to write readable subqueries with Laravel's query builder and Eloquent.
  - Must cover: `selectSub()`, `joinSub()`, correlated subqueries, SQL equivalents, and refactoring examples.
  - Visual plan: a before/after query comparison is more useful than screenshots.

- [ ] `laravel seeder`
  - Angle: explain when to use seeders, factories, or both in a real Laravel workflow.
  - Must cover: realistic sample data, local setup, test data, idempotent seeding, and common mistakes.
  - Visual plan: screenshots only if they improve a demo workflow materially.

- [ ] `laravel dompdf`
  - Angle: teach PDF generation through a concrete invoice or receipt build.
  - Must cover: package install, Blade views, CSS limitations, images, downloads, streaming, and rendering gotchas.
  - Visual plan: screenshots or output samples are likely worth it because the final artifact is visual.

- [ ] `laravel hasmanythrough`
  - Angle: teach one confusing relationship through one concrete example readers can map to their own app.
  - Must cover: relationship setup, example schema, query usage, mental model, and common mistakes.
  - Visual plan: include a relationship diagram.

## Reframe before writing

- [ ] `laravel blade`
  - Better target: "How to use Blade templates in Laravel" or "Blade components, layouts, props, and slots."
  - Execution note: do not chase the bare head term with a generic overview.

- [ ] `laravel debugbar`
  - Better target: "How to install Laravel Debugbar and keep it out of production."
  - Execution note: treat the package name as partly navigational and win on the setup workflow.

- [ ] `laravel octane`
  - Better target: "When Laravel Octane helps, when it hurts, and how to set it up."
  - Execution note: make this a decision guide, not a docs rewrite.

- [ ] `laravel scout`
  - Better target: "Laravel Scout with Meilisearch or Algolia, locally and in production."
  - Execution note: focus on the task, not the ecosystem overview.

## Later expansion

- [ ] `502 bad gateway nginx`
  - Angle: a diagnosis-first troubleshooting guide with logs, PHP-FPM, upstreams, timeouts, and a fix order.

- [ ] `401 error`
  - Angle: a practical troubleshooting guide only after the PHP and Laravel backlog is moving well.

- [ ] `503 error`
  - Angle: same broad web-ops play as above; lower priority than the PHP and Laravel core topics.

- [ ] `error establishing a database connection`
  - Angle: pursue later as a broad troubleshooting term once the current topical cluster is stronger.

- [ ] `ssl handshake failed`
  - Angle: tackle later with a clear environment-by-environment troubleshooting flow.

- [ ] `nginx reverse proxy`
  - Angle: expansion topic for later because it pulls the site slightly away from the current PHP and Laravel core.

- [ ] `install docker ubuntu`
  - Angle: later infrastructure play, not the next best move.

- [ ] `certbot nginx`
  - Angle: later systems tutorial once the main backlog is in better shape.

- [ ] `docker compose volumes`
  - Angle: later expansion topic if you decide to widen the site's systems coverage.

## Operating assumptions

- This backlog is for net-new editorial opportunities rather than the release and version pages already maintained on the site.
- For Laravel package topics, we should target the task behind the package instead of the package name alone.
- For PHP helper terms, the way to win is practical examples, edge cases, comparisons, and clearer decision-making than the reference pages offer.
