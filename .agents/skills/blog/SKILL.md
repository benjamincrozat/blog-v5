---
name: blog
description: Coordinate production-grade Laravel blog work across routing, content sync, rendering, testing, and delivery standards.
metadata:
  short-description: Orchestrator for Laravel blog implementation standards
---

# Blog

## Scope

Use this skill as the entrypoint for multi-subsystem blog changes.

## Required Rules

- Keep content files as the source of truth for posts.
- Preserve SEO-safe status semantics (`404`, `410`, `301`).
- Delegate Blade template/component rules to `laravel-blade`.
- Delegate Livewire behavior to `livewire` and Alpine state behavior to `alpine-js`.
- Follow `AGENTS.md` for repo-wide workflow, validation commands, and git policy.

## Workflow

1. Inspect existing behavior and pick the smallest coherent change slice.
2. Select only the needed focused skills:
   - `laravel`
   - `laravel-blade`
   - `livewire`
   - `alpine-js`
   - `seo-routing`
   - `markdown-sync`
   - `rendering-accessibility`
   - `pest-feature-tests`
   - `delivery-standards`
3. Implement by subsystem, preserving public behavior unless explicitly requested.
4. Validate with `delivery-standards` and relevant `pest-feature-tests` coverage.

## References

- `references/checklists.md`
- `references/standards.md`
