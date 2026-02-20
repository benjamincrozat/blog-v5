---
name: laravel-blade
description: Canonical Blade template and component guidance for this blog repo.
metadata:
  short-description: Canonical Blade templates and components for this blog
---

# Laravel Blade

## Scope

Use for Blade views, Blade components, internal template navigation, and semantic accessibility structure.

## Required Rules

- Blade views are presentation-only: keep business logic, policy decisions, and data transformations out of templates.
- Prefer controllers/actions to prepare view data; if non-trivial view data does not exist yet, create it and pass computed values explicitly.
- If a view grows non-trivial `@php` blocks (queries, aggregation, chart prep), move that logic out of Blade.
- Keep Blade markup tidy, valid, semantic, and accessible.
- Use semantic tags (`header`, `nav`, `main`, `article`, `section`, `footer`) with clear heading hierarchy.
- Avoid unnecessary wrapper `div`/`span` elements.
- Use `wire:navigate` for internal links whenever possible.
- Decorative icons should be `aria-hidden="true"`; informative icons must expose an accessible name.
- Include a top-level intent comment in Blade views.
- Blade file comments should use `{{--` and `--}}` on their own lines and contain one capitalized sentence ending with a period.
- Blade components should start with a short comment that states purpose and contract.
- Use Blade components broadly and prefer existing `x-<namespace>.*` components over inlining repeated markup.
- If a UI pattern repeats, extract it into `resources/views/components/<namespace>/`.
- Avoid placeholder/demo content (for example `fake()`) in shipped core components.
- Group related components by theme under `resources/views/components/<theme>/`.
- Do not use `resources/views/components/ui/`.
- If a subfolder contains only one component, move it to `resources/views/components/` and rename to avoid conflicts.
- Keep standalone components in `resources/views/components/`.
- Use class-based components for logic-bearing components and add tests.
- Do not use component properties just to pass class strings.
- Use `$attributes->class(...)` for base classes, and use class arrays for conditional class application.
- Prefer named slots for styling specific internal elements instead of `*Class` / `*Classes` props.
- Avoid inline `@php(...)`; for non-trivial PHP use `@php ... @endphp`.
- For multi-attribute HTML elements, prefer one attribute per line.
- Keep a space before control-directive parentheses (for example `@if (`).
- Delegate Livewire-specific rules to `livewire` and Alpine state rules to `alpine-js`.

## Workflow

1. Preserve current rendered behavior unless explicitly requested.
2. Keep accessibility semantics intact when extracting or reorganizing components.
3. Ensure updated internal links use `wire:navigate` where possible.
4. Follow `delivery-standards` quality gates and reporting.

## References

- Pair with `laravel` for app-layer flow.
- Pair with `tailwind-css` for styling implementation.
- Pair with `livewire` and `alpine-js` for reactive UI behavior.
