---
name: file-first-posts
description: Operate the file-first post authoring workflow for this blog.
metadata:
  short-description: Export, edit, publish, and sync Markdown-managed posts
---

# File-first Posts

## Scope

Use this skill when posts are managed from `resources/markdown/posts`.
Pair with `post-writing` when creating or revising article copy so internal links and, for non-commercial posts, the related-posts Markdown list stay current.

## Required Rules

- Treat Markdown files as the only write source for post content and SEO fields.
- Use `php artisan blog:export` only for initial export or explicit regeneration.
- Use `php artisan blog:sync` after every Markdown edit.
- Upload every image used in a post to Cloudflare Images, including featured images, inline screenshots, badges, logos, and comparison cards.
- Use `php artisan blog:upload-image` for post images instead of local paths or third-party hotlinks.
- When a post explains an interface, setup flow, settings page, terminal output, or before/after result, prefer creating original screenshots instead of leaving the visual outcome implied.
- Skip screenshots only when they add no proof or clarity.
- Give screenshot files descriptive names and useful alt text before upload.
- Use UTC ISO-8601 timestamps for frontmatter dates such as `published_at` and `modified_at`.
- Publishing is changing `published_at` in the file, then running `php artisan blog:sync`.
- Fail loudly on invalid front matter, unknown authors/categories, or duplicate IDs/slugs.
- Do not use Filament to create, edit, delete, or restore posts.
- If an edit changes article copy or scope, refresh the post's internal links and, for non-commercial posts, the related-posts Markdown list before syncing.
- Commercial posts (`is_commercial: true`) must not add or keep a related-posts or read-next block. Keep those pages focused on the conversion path already in the article.
- On non-commercial posts, that related-posts block must use a smooth article-specific lead-in sentence, not a stock phrase, canned curiosity hook, or title echo.
- Choose related posts because they feel like smart next reads for the current article, not because they happen to share a category.
- Rewrite the related-post anchor text contextually instead of pasting destination post titles into the list.
- Make those anchors sound like the reader's next useful click from this page, not like a shelf label copied from somewhere else.
- Keep them plain and clear enough that the reader immediately gets why the next post is worth opening.
- Do not open the public page or Filament just for a routine post edit. Use browser checks only when the post includes tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, a publishing-state change that needs confirmation, the article needs purposeful screenshots that materially help the reader, or the user explicitly asks for a visual check.

## Workflow

1. Bootstrap files when needed:
   - run `php artisan blog:export`
2. Edit the target file in `resources/markdown/posts`.
   - if article copy changed, make sure internal links were updated and, for non-commercial posts, the related-posts Markdown list was added or refreshed with a natural lead-in that does not quote or rephrase the title
3. Capture and upload images before syncing:
   - if the article would be clearer or more trustworthy with original screenshots, capture them yourself when feasible instead of leaving a note for later
   - every image referenced by the post should end up on Cloudflare Images, not just the hero image
   - hero image: `php artisan blog:upload-image /absolute/path/to/cover.png --markdown=your-post.md`
   - inline image: `php artisan blog:upload-image /absolute/path/to/step.png --alt="Describe the screenshot"` and paste the returned URL into the article body
4. If publishing state changes, update `published_at` in UTC.
5. Run `php artisan blog:sync`.
6. Decide whether a browser check is needed:
   - skip it for routine Markdown-only edits when `php artisan blog:sync` succeeds
   - open the public page or Filament only for tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, or publishing changes that need confirmation
7. Keep deploy notes in mind:
   - deployment should run `php artisan blog:sync` before sitemap generation
