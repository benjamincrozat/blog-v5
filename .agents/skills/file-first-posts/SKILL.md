---
name: file-first-posts
description: Operate the file-first workflow for evergreen and timely Markdown-managed posts on this blog.
metadata:
  short-description: Run the Markdown workflow for evergreen and timely posts
---

# File-first Posts

## Trigger

Use this skill for `resources/markdown/posts`. Pair with `post-writing` for copy.

## Rules

- Markdown files are the only write source for post content and SEO fields.
- Run `php artisan app:sync-posts` after every Markdown edit unless the last step was `php artisan app:generate-post-image`, which already syncs.
- Upload every image with `php artisan app:upload-post-image`; no local paths or third-party hotlinks.
- If the featured image is still missing after the draft is stable, run `php artisan app:generate-post-image <slug>`.
- For a substantive refresh, open every existing inline screenshot and inspect the actual image before deciding to keep, crop, replace, or remove it. Never judge screenshot quality from Markdown, alt text, filenames, dimensions, or counts.
- Prefer original screenshots when they materially prove or clarify UI, setup, output, or before/after results. Inspect the uploaded image and final rendered size; skip filler.
- Use UTC ISO-8601 timestamps with trailing `Z` only. Normalize any `+00:00` back to `Z`.
- Publishing is `published_at` in the file, then sync.
- `news` posts: publish promptly, sync after substantive edits, and set `modified_at` only for meaningful reporting changes.
- Only first-party, non-commercial, non-sponsored `news` posts are news-sitemap candidates.
- Fail loudly on invalid front matter, unknown authors/categories, or duplicate IDs/slugs.
- If copy or scope changed, apply `post-writing`'s internal-authority plan. Check contextual links from relevant existing posts to the target, links from the target to its real topic cluster, and any related-posts block before syncing.
- Add one related-posts block to a non-commercial post only when it offers genuine next steps. Use a custom lead-in ending with `:` and contextual anchors; do not force a minimum count.
- Commercial posts (`is_commercial: true`) must not include a related-posts or read-next block.
- Skip browser checks for small copy-only edits. Use them for substantive screenshot review, tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, publishing-state checks, and purposeful screenshots.

## Flow

1. Edit `resources/markdown/posts/<slug>.md`.
2. If copy or scope changed, strengthen useful incoming and outgoing internal links and review the related-posts block when allowed.
3. For a substantive refresh, open every existing screenshot at full size and in the rendered article before planning image changes.
4. Capture and upload needed images. Inspect the uploaded result and final rendered size. For Discover/News-focused posts, the main image should be original when feasible and at least 1200 px wide.
5. Update `published_at` / `modified_at` only when needed.
6. Run `php artisan app:sync-posts` or `php artisan app:generate-post-image <slug>`.
7. Use browser validation whenever screenshots or rendering matter; skip it for small copy-only edits.
8. Keep deploy behavior in mind: deployment should sync posts before sitemap generation.
