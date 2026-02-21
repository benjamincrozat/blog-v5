---
name: markdown-sync
description: Build and maintain Markdown-to-database sync for this blog.
metadata:
  short-description: Markdown contract, parser, and sync pipeline
---

# Markdown Sync

## Scope

Own post ingestion from `resources/markdown/posts` into the read model.

## Required Rules

- Treat Markdown files as the source of truth.
- Enforce strict frontmatter validation with actionable file-level errors.
- Required frontmatter: `title`, `slug`, `description`, `categories` (YAML list), `published_at`.
- Optional frontmatter: `modified_at`, `canonical_url`.
- Keep sync deterministic and idempotent.
- Avoid hidden fallback behavior that masks invalid content.
- Keep command class names aligned with command signatures (for example `blog:watch` -> `BlogWatchCommand`).

## Workflow

1. Maintain sync pipeline behavior:
   - scan markdown files recursively
   - parse and validate frontmatter
   - upsert posts
   - normalize/sync categories
   - maintain slug redirects
   - soft-delete posts missing from source
   - return explicit counters/errors
2. Keep commands stable:
   - `php artisan blog:sync`
   - `php artisan blog:watch --interval=...`
   - Keep command output operator-friendly and unambiguous when behavior changes.
3. Add/adjust tests for behavior changes (`pest-feature-tests`).
4. Verify with local fixtures and follow `delivery-standards` quality gates.

## References

- Pair with `laravel` for app-layer architecture.
