---
name: alpine-js
description: Canonical Alpine.js conventions for local reactive behavior in this Laravel blog repo.
metadata:
  short-description: Canonical Alpine.js local-state conventions
---

# Alpine.js

## Scope

Use for local browser-side state and interactivity in Blade templates.

## Required Rules

- Keep Alpine state small, local, and focused on presentation interactivity.
- Use `x-cloak` to prevent flashes of uninitialized UI.
- Keep ARIA attributes synced with interactive state (for example `aria-expanded`, `aria-hidden`).
- Keep business logic out of Alpine expressions; move application logic to Laravel/Livewire layers.
- Prefer simple, readable expressions and avoid large inline state machines in templates.

## Workflow

1. Keep interactivity changes minimal and scoped to the component that needs them.
2. Verify state changes and ARIA attributes stay synchronized across toggle paths.
3. Pair with `livewire` when state transitions coordinate with server-driven updates.
4. Follow `delivery-standards` quality gates and reporting.

## References

- Pair with `laravel-blade` for semantic/accessibility markup rules.
- Pair with `livewire` for server-driven reactive flows.
