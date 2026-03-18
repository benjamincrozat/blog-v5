---
name: post-writing
description: Draft and revise evergreen and timely blog posts that are concise, source-backed, and reader-first.
metadata:
  short-description: Write evergreen and timely reader-first posts
---

# Post Writing

## Trigger

Use for publication-ready posts in `resources/markdown/posts`. Pair with `seo-content` for search framing and `file-first-posts` for sync, publish, and image workflow.

## Frontmatter Contract

- Required keys: `id`, `title`, `slug`, `author`, `description`, `categories`.
- Keep `id` stable on revisions.
- `author` uses the author's `github_login`; `categories` use category slugs.
- `published_at`, `modified_at`, and `sponsored_at` are UTC ISO-8601 datetimes with trailing `Z` or `null`.
- `serp_title`, `serp_description`, `canonical_url`, `image_disk`, and `image_path` are nullable strings.
- `is_commercial` must be `true` or `false`.
- The filename must match `slug` and end in `.md`.

## Rules

- Write for competent beginners first: plain language, short sentences, clear payoff.
- Explain unavoidable technical terms the first time they appear.
- Upload every image with `php artisan app:upload-post-image`; no local paths or hotlinks.
- Create original visuals for walkthroughs, dashboards, UI-heavy how-tos, comparisons, and reviews when they materially add proof or clarity. Skip filler.
- Keep `title`, `description`, `slug`, and any `serp_*` override aligned on one promise.
- No in-body H1. No manual table of contents.
- Draft at least five title options, then choose the clearest one.
- Use sentence case by default. A 40-60 character or 6-9 word title is a soft heuristic, not a hard rule.
- Leave `serp_title` and `serp_description` as `null` unless a tighter search/browser variant is clearly better while keeping the same angle.
- Use dates or years only when the page is current enough to support them.
- `news` posts should lead with the update, cite primary sources inline, and stay tight.
- Open fast. Keep headings descriptive. Use the primary keyword naturally, not repetitively.
- Add context before code. Do not leave sections as code-only.
- Use current primary sources for version-sensitive claims. Prefer relevant internal links before equivalent external links.
- Keep the article body reader-facing. Do not mention internal workflow, prompts, temp projects, or skills inside the post.
- Avoid vague vibe words unless quoting or attributing a source.
- Non-commercial posts need one related-posts list with a custom lead-in ending with `:` and contextual anchors. Usually add at least 3 items; never more than 10.
- Commercial posts (`is_commercial: true`) must not include a related-posts, read-next, or follow-up reading block.
- Pick related posts because they genuinely extend this reader journey, not because they share a category.
- Add or improve natural internal links in the body wherever a reader wants the next step.
- Verify links and commands when feasible. If something cannot be verified, tell the user outside the post copy.
- Use UTC timestamps with trailing `Z`.
- If the featured image is missing when the copy is stable, run `php artisan app:generate-post-image <slug>`.
- Skip browser validation for routine post-writing tasks. Use it only for tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, first-hand screenshots that materially help the reader, or explicit requests.

## Common Shapes

- Fix post: state the problem, explain the cause, show the fix, then note edge cases or alternatives.
- Concept post: define the idea simply, explain why it matters, then teach through examples and gotchas.
- News post: lead with the update, explain why it matters now, cite primary sources inline, and close with a few durable evergreen links.

## Flow

1. Define the reader promise, search intent, and post shape.
2. Draft at least five title candidates and choose the strongest one.
3. Decide whether `serp_title` or `serp_description` should stay `null` or get a same-angle override.
4. Pick the simplest outline that fits the post shape.
5. Draft for clarity, usefulness, and brevity.
6. Create original visuals only when they materially clarify the piece or prove first-hand use.
7. If the post still has no featured image after the copy is stable, run `php artisan app:generate-post-image <slug>`. Use `--force` only when you intentionally need a fresh render.
8. Add or refresh contextual internal links and, for non-commercial posts, the related-posts list.
9. Validate version-sensitive claims, inline links, and code examples.
10. Final pass: frontmatter valid, `id` stable, promise aligned, featured image present, all images on Cloudflare, `serp_*` aligned or `null`, related-posts rules respected, and timestamps use `Z`.
