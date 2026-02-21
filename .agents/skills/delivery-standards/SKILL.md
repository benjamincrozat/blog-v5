---
name: delivery-standards
description: Apply delivery standards for this blog codebase, including documentation intent, verification discipline, and implementation hygiene.
metadata:
  short-description: Doc intent, validation, and implementation standards
---

# Delivery Standards

## Scope

Use this skill with implementation skills to enforce delivery quality.

## Required Rules

- PHP classes must include a class-level intent docblock.
- Blade view intent-comment rules are owned by `laravel-blade`.
- Avoid comments that restate syntax.
- Keep code DRY by reusing existing classes, helpers, components, and queries before adding new ones.
- Follow `AGENTS.md` for repo-wide workflow, validation commands, and git policy.
- For Markdown/content-only changes, skip code quality gates unless explicitly requested.
- For visual QA, use screenshots for static states and Playwright CLI video capture (`video-start` / `video-stop`) for animated behavior (Chrome default browser).

## Workflow

1. Reuse existing abstractions before creating new implementation paths.
2. Run other impacted commands when relevant (for example `php artisan route:list`).
3. If any required check cannot run, report it explicitly.
4. Summarize what changed, why, what was validated, and residual risk.

## References

- Pair with `laravel` for architecture rules.
- Pair with `pest-feature-tests` for test-shape rules.
