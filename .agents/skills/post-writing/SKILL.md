---
name: post-writing
description: Draft and revise educational blog posts that are concise, source-backed, and reader-first.
metadata:
  short-description: Write concise, researched, reader-first posts
---

# Post Writing

## Scope

Use for drafting or revising publication-ready posts in `resources/markdown/posts`.
Pair with `seo-content` for search intent, SERP review, titles, snippets, and internal linking.

## Frontmatter Contract

- Required keys: `id`, `title`, `slug`, `author`, `description`, `categories`.
- Keep `id` stable when revising an existing post.
- `author` must use the author's `github_login`.
- `categories` must use category slugs.
- `published_at`, `modified_at`, and `sponsored_at` use ISO-8601 datetimes or `null`.
- `serp_title`, `serp_description`, `canonical_url`, `image_disk`, and `image_path` are nullable strings.
- `serp_title` overrides the HTML `<title>` tag and `serp_description` overrides the meta description tag.
- `is_commercial` must be `true` or `false`.
- The filename must match `slug` and end in `.md`.

## Required Rules

- Write for competent beginners first: plain language, short sentences, clear payoff.
- Explain unavoidable technical terms in simple words the first time they appear.
- Upload every featured image and inline article image to Cloudflare Images with `php artisan blog:upload-image`.
- Do not leave local file paths or third-party hotlinked images in post Markdown.
- Keep the promise tight across frontmatter: `title`, `description`, `slug`, and any `serp_*` overrides should point to the same outcome.
- Do not add an in-body H1 or a manual table of contents.
- Draft at least five title options, then pick the clearest one.
- Use sentence case for titles by default. For search-facing titles, roughly 40-60 characters or 6-9 words is a useful soft target, not a hard rule.
- Prefer titles with a plain-language outcome over clever phrasing or vague filler.
- Treat `title` as the visible article title and `description` as the default summary used across the site.
- Default `serp_title` and `serp_description` to `null`. Set them only when a tighter search/browser variant is clearly better than the default.
- If `serp_title` is set, keep it very close to the visible `title` in meaning. It can be shorter or slightly more descriptive, but it must not target a different angle, query, or claim.
- If `serp_description` is set, make it a concise, accurate summary of the same page. Do not use keyword lists or add claims that the page does not support.
- When choosing among title options, favor the one with the clearest benefit, strongest specificity, and best query match.
- Descriptions should read like a useful pitch for the page: one short sentence or two short clauses, with the main benefit near the start.
- If the topic is time-sensitive, you may use a year or date, but only if the page itself is current enough to support it.
- Avoid clickbait phrasing, vague curiosity hooks, fake urgency, unsupported superlatives, emojis, or decorative all-caps.
- Open fast: explain the problem, answer, or reason to care in the first few paragraphs.
- Keep headings descriptive and skimmable. Use the exact primary keyword in only 1-2 headings if it reads naturally.
- Prefer practical examples and code when they help, but never leave a section as code-only.
- Add context before snippets and explain what they do, why they matter, and what result to expect.
- Use current primary sources for version-sensitive claims. Link inline at the claim, and prefer relevant internal links before equivalent external links.
- Verify links and commands when feasible. If something cannot be verified, tell the user outside the post copy.
- Do not use browser-based validation for post-writing tasks.

## Common Shapes

- Fix post:
  state the problem quickly, explain the likely cause, show the fix, and close with edge cases or alternatives.
- Concept post:
  define the idea in simple words, show why it matters, then teach through examples and gotchas.

## Workflow

1. Define the reader promise and search intent.
2. Draft five or more title candidates and choose the strongest one.
3. Decide whether `serp_title` or `serp_description` need an override; otherwise leave them `null`.
4. Check that the chosen title and description earn the click with a clear outcome, not a gimmick.
5. Pick the simplest outline that matches the post shape.
6. Draft for clarity, usefulness, and brevity.
7. Validate version-sensitive claims, inline links, and code examples.
8. Do a final publishing pass:
   - required frontmatter is present and valid
   - existing post IDs stayed unchanged
   - featured image and inline images use Cloudflare Images URLs or paths
   - frontmatter promise is aligned
   - `serp_title` and `serp_description` are either `null` or clearly justified and aligned with the visible page copy
   - title and description make a strong, accurate click promise
   - headings read naturally in the generated table of contents
   - code and links support the nearby claim
