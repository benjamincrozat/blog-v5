---
name: frontend-conventions
description: UI conventions for Blade/HTML, Tailwind v4, and Alpine.
---

## When writing Blade views and markup (`resources/views/**`)

- Views are **presentation only**: do not put business/app logic in Blade (no queries, no policy decisions, no transformations/aggregations beyond trivial formatting).
- Prefer the controller to prepare the view data. If it doesn’t exist yet, **create it** and move the logic there, then pass the computed data into the view.
- If a Blade view grows non-trivial `@php` blocks (queries, aggregation, chart prep), move that work into the controller and pass the computed values explicitly.
- Keep HTML **tidy, valid, semantic, and accessible**.
- Avoid extra `div` or `span` wrappers unless strictly necessary.
- Prefer landmarks (`header`, `nav`, `main`, `footer`) over generic wrappers.
- Icons: decorative → `aria-hidden="true"`; informative → accessible name.
- Blade file comments: top-of-file block is `{{--` and `--}}` on their own lines, with a single capitalized sentence ending in a period.
- Blade components (`resources/views/components/**`) start with a short comment: purpose + contract.
- Avoid inline `@php(...)`; for non-trivial PHP use `@php ... @endphp`.
- Multi-attribute elements: one attribute per line. Control directives have a space before `(` (e.g. `@if (`).

### When working on the user interface (`resources/views/<namespace>/**`)

- Prefer existing `x-<namespace>.*` components over inlining equivalent markup (layout, buttons, cards, badges, tables, alerts, etc.).
- If a UI pattern repeats, **extract it** into `resources/views/components/<namespace>/**` (don’t copy/paste).
- Avoid placeholder/demo content (e.g. `fake()`) in shipped core components; remove it or keep it strictly local to demo-only views.

## When styling the user interface with Tailwind CSS v4

- Prefer Tailwind utilities over bespoke CSS.
- Use `size-*` over `w-*` + `h-*` for squares.
- Avoid copy/pasting “mega” class strings; extract visually significant sections into a `x-<namespace>.*` component.
- If custom CSS is required, keep it minimal and document why.
- Prefer Tailwind v4 CSS-first features: `@theme` (tokens), `@utility` (small reusable utilities), `@custom-variant` / `@variant` only when necessary.
- Always fix Tailwind lint issues.

## When creating dynamic and reactive interfaces

- Keep Alpine.js state **small and local**.
- Use `x-cloak` to prevent flashes.
- Keep ARIA synced with state (e.g. `aria-expanded`).