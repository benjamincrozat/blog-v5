---
name: file-first-posts
description: Operate the file-first post authoring workflow for this blog.
metadata:
  short-description: Export, edit, publish, and sync Markdown-managed posts
---

# File-first Posts

## Scope

Use this skill when posts are managed from `resources/markdown/posts`.
Pair with `post-writing` when creating or revising article copy so internal links and the related-posts list stay current.

## Required Rules

- Treat Markdown files as the only write source for post content and SEO fields.
- Use `php artisan blog:export` only for initial export or explicit regeneration.
- Use `php artisan blog:sync` after every Markdown edit.
- Upload every featured image and inline article image to Cloudflare Images.
- Use `php artisan blog:upload-image` for post images instead of local paths or third-party hotlinks.
- Use UTC ISO-8601 timestamps for frontmatter dates such as `published_at` and `modified_at`.
- Publishing is changing `published_at` in the file, then running `php artisan blog:sync`.
- Fail loudly on invalid front matter, unknown authors/categories, or duplicate IDs/slugs.
- Do not use Filament to create, edit, delete, or restore posts.
- If an edit changes article copy or scope, refresh the post's internal links and related-posts list before syncing.

## Workflow

1. Bootstrap files when needed:
   - run `php artisan blog:export`
2. Edit the target file in `resources/markdown/posts`.
   - if article copy changed, make sure internal links and the related-posts list were added or refreshed
3. Upload images before syncing:
   - hero image: `php artisan blog:upload-image /absolute/path/to/cover.png --markdown=your-post.md`
   - inline image: `php artisan blog:upload-image /absolute/path/to/step.png --alt="Describe the screenshot"` and paste the returned URL into the article body
4. If publishing state changes, update `published_at` in UTC.
5. Run `php artisan blog:sync`.
6. Verify the public page and the Filament posts list.
7. Keep deploy notes in mind:
   - deployment should run `php artisan blog:sync` before sitemap generation
