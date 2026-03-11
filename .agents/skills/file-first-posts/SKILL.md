---
name: file-first-posts
description: Operate the file-first post authoring workflow for this blog.
metadata:
  short-description: Export, edit, publish, and sync Markdown-managed posts
---

# File-first Posts

## Scope

Use this skill when posts are managed from `resources/markdown/posts`.

## Required Rules

- Treat Markdown files as the only write source for post content and SEO fields.
- Use `php artisan blog:export` only for initial export or explicit regeneration.
- Use `php artisan blog:sync` after every Markdown edit.
- Publishing is changing `published_at` in the file, then running `php artisan blog:sync`.
- Fail loudly on invalid front matter, unknown authors/categories, or duplicate IDs/slugs.
- Do not use Filament to create, edit, delete, or restore posts.

## Workflow

1. Bootstrap files when needed:
   - run `php artisan migrate`
   - run `php artisan blog:export`
2. Edit the target file in `resources/markdown/posts`.
3. If publishing state changes, update `published_at`.
4. Run `php artisan blog:sync`.
5. Verify the public page and the Filament posts list.
6. Keep deploy notes in mind:
   - deployment should run `php artisan blog:sync` before sitemap generation

## References

- Pair with `blog` for cross-cutting blog behavior.
- Pair with `markdown-sync` for parser and sync internals.
