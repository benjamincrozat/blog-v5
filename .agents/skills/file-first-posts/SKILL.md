---
name: file-first-posts
description: Operate the file-first workflow for evergreen and timely Markdown-managed posts on this blog.
metadata:
  short-description: Run the Markdown workflow for evergreen and timely posts
---

# File-first Posts

## Trigger

Use this skill for `resources/markdown/posts`. Pair with `post-writing` for copy and `seo-content` for search framing.

## Rules

- Markdown files are the only write source for post content and SEO fields.
- Use `php artisan app:export-posts` only for initial export or explicit regeneration.
- Run `php artisan app:sync-posts` after every Markdown edit unless the last step was `php artisan app:generate-post-image`, which already syncs.
- Upload every image with `php artisan app:upload-post-image`; no local paths or third-party hotlinks.
- If the featured image is still missing after the draft is stable, run `php artisan app:generate-post-image <slug>`.
- Prefer original screenshots when they materially prove or clarify UI, setup, output, or before/after results. Skip filler.
- Use UTC ISO-8601 timestamps with trailing `Z` only. Normalize any `+00:00` back to `Z`.
- Publishing is `published_at` in the file, then sync.
- `news` posts: publish promptly, sync after substantive edits, and set `modified_at` only for meaningful reporting changes.
- Only first-party, non-commercial, non-sponsored `news` posts are news-sitemap candidates.
- Fail loudly on invalid front matter, unknown authors/categories, or duplicate IDs/slugs.
- Do not use Filament to create, edit, delete, or restore posts.
- If copy or scope changed, refresh internal links and, for non-commercial posts, the related-posts block before syncing.
- Non-commercial posts need one related-posts block with a custom lead-in ending with `:` and contextual anchors chosen as true next reads.
- Commercial posts (`is_commercial: true`) must not include a related-posts or read-next block.
- Skip browser checks for routine Markdown edits. Use them only for tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, publishing-state checks, purposeful screenshots, or explicit requests.

## Flow

1. Export only when the file does not exist yet or the user explicitly wants regeneration.
2. Edit `resources/markdown/posts/<slug>.md`.
3. If copy or scope changed, refresh internal links and the related-posts block when allowed.
4. Capture and upload needed images. For Discover/News-focused posts, the main image should be original when feasible and at least 1200 px wide.
5. Update `published_at` / `modified_at` when needed.
6. Run `php artisan app:sync-posts` or `php artisan app:generate-post-image <slug>`.
7. Only use browser validation when the article needs it.
8. Keep deploy behavior in mind: deployment should sync posts before sitemap generation.
