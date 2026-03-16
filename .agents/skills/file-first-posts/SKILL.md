---
name: file-first-posts
description: Operate the file-first workflow for evergreen and timely Markdown-managed posts on this blog.
metadata:
  short-description: Run the Markdown workflow for evergreen and timely posts
---

# File-first Posts

## Scope

Use this skill when posts are managed from `resources/markdown/posts`.
Pair with `post-writing` when revising copy, and with `seo-content` when search framing matters.

## Required Rules

- Treat Markdown files as the only write source for post content and SEO fields.
- Use `php artisan app:export-posts` only for initial export or explicit regeneration.
- Use `php artisan app:sync-posts` after every Markdown edit.
- Upload every image used in a post to Cloudflare Images, including featured images, inline screenshots, badges, logos, and comparison cards.
- Use `php artisan app:upload-post-image` for post images instead of local paths or third-party hotlinks.
- If a post still has no featured image once the draft is written, run `php artisan app:generate-post-image your-post-slug` so `image_disk` / `image_path` are filled before handoff or publishing.
- When a post explains an interface, setup flow, settings page, terminal output, or before/after result, prefer creating original screenshots instead of leaving the visual outcome implied.
- Skip screenshots only when they add no proof or clarity.
- Give screenshot files descriptive names and useful alt text before upload.
- Use UTC ISO-8601 timestamps with a trailing `Z` for frontmatter dates such as `published_at` and `modified_at`. Treat `Z` as the only canonical UTC format in this repo; do not use `+00:00`.
- Publishing is changing `published_at` in the file, then running `php artisan app:sync-posts`.
- If `categories` includes `news`, publish promptly, sync immediately after substantive edits, and only set `modified_at` when the article changed in a meaningful reporting way.
- Only first-party, non-commercial, non-sponsored `news` posts should be treated as news-sitemap candidates.
- Fail loudly on invalid front matter, unknown authors/categories, or duplicate IDs/slugs.
- Do not use Filament to create, edit, delete, or restore posts.
- If an edit changes article copy or scope, refresh internal links and, for non-commercial posts, the related-posts list before syncing.
- Commercial posts (`is_commercial: true`) must not include a related-posts or read-next block.
- Non-commercial related-posts blocks must use a custom article-specific lead-in sentence ending with a colon, followed by clear, contextual anchors rather than pasted destination titles.
- Pick related posts because they are the best next reads for this article, not because they share a category.
- Do not open the public page or Filament just for a routine post edit. Use browser checks only when the post includes tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, a publishing-state change that needs confirmation, the article needs purposeful screenshots that materially help the reader, or the user explicitly asks for a visual check.

## Workflow

1. Bootstrap files when needed:
   - run `php artisan app:export-posts`
2. Edit the target file in `resources/markdown/posts`.
   - if article copy changed, update internal links and, for non-commercial posts, add or refresh the related-posts list with a natural lead-in that does not quote or rephrase the title
3. Capture and upload images before syncing:
   - if the article would be clearer or more trustworthy with original screenshots, capture them yourself when feasible instead of leaving a note for later
   - every image referenced by the post should end up on Cloudflare Images, not just the hero image
   - for posts aiming at Discover or Google News strongly, the main image should be original when feasible, not a logo, and at least 1200 px wide
   - hero image: `php artisan app:upload-post-image /absolute/path/to/cover.png --markdown=your-post.md`
   - inline image: `php artisan app:upload-post-image /absolute/path/to/step.png --alt="Describe the screenshot"` and paste the returned URL into the article body
   - if the post still has no featured image after writing it, generate one with `php artisan app:generate-post-image your-post-slug`
4. If publishing state changes, update `published_at` in UTC with a trailing `Z`.
   - if this is a news post and you made a substantive reporting update, set `modified_at` in UTC with a trailing `Z` before syncing
5. Run `php artisan app:sync-posts`.
   - skip this extra sync only when the last action was `php artisan app:generate-post-image`, because that command already updates the Markdown file and runs `php artisan app:sync-posts` for you
   - if `php artisan app:sync-posts` or `php artisan app:generate-post-image` rewrites timestamps to `+00:00`, normalize them back to the repo standard `Z` format before finishing
6. Decide whether a browser check is needed:
   - skip it for routine Markdown-only edits when `php artisan app:sync-posts` succeeds
   - open the public page or Filament only for tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, or publishing changes that need confirmation
7. Keep deploy notes in mind:
   - deployment should run `php artisan app:sync-posts` before sitemap generation
