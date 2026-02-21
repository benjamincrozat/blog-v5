---
name: delivery-standards
description: Apply delivery standards for this blog codebase, including documentation intent, verification discipline, communication, and commit policy.
metadata:
  short-description: Doc intent, validation, and commit standards
---

# Delivery Standards

## Scope

Use this skill with implementation skills to enforce delivery quality.

## Required Rules

- PHP classes must include a class-level intent docblock.
- Blade view intent-comment rules are owned by `laravel-blade`.
- Avoid comments that restate syntax.
- Keep code DRY by reusing existing classes, helpers, components, and queries before adding new ones.
- For Markdown/content-only changes, skip code quality gates (`pint`, `phpstan`, `pest`, `artisan test`) unless explicitly requested.
- For visual QA, use screenshots for static states and Playwright CLI video capture (`video-start` / `video-stop`) for animated behavior (Chrome default browser).
- Commit coherent, granular slices only.
- Do not push unless requested.
- Use sentence-style commit messages and avoid `feat:` / `fix:` prefixes unless requested.

## Workflow

1. Reuse existing abstractions before creating new implementation paths.
2. Choose validation scope by change type:
   - Markdown/content-only changes: run `php artisan blog:sync`.
   - Code/config/behavior changes: run `php vendor/bin/pint --parallel` and `php artisan test --parallel`.
3. Run other impacted commands when relevant (for example `php artisan route:list`).
4. If any required check cannot run, report it explicitly.
5. Summarize what changed, why, what was validated, and residual risk.

## References

- Pair with `laravel` for architecture rules.
- Pair with `pest-feature-tests` for test-shape rules.
