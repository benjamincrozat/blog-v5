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
- `published_at`, `modified_at`, and `sponsored_at` use UTC ISO-8601 datetimes with a `Z` suffix or `null`.
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
- Every created or revised non-commercial post must include a short list of interesting posts to read next. If the post already has one, refresh it instead of duplicating it.
- Commercial posts (`is_commercial: true`) must not include a related-posts, read-next, or follow-up reading block. Keep the ending focused on the conversion path already present in the article.
- On non-commercial posts, the sentence before that list must be custom to the article. Do not reuse stock lead-ins, canned curiosity hooks, or repeatable templates across posts. Tie it to the page's specific topic, promise, friction point, or next step.
- Do not quote, paraphrase, or simply restate the article title in that lead-in. It should feel like a smooth, natural transition that makes the reader want to keep going.
- Format the non-commercial block as a custom lead-in sentence ending with a colon, then a standard Markdown list with one linked item per bullet, like this:
  Custom lead-in text for this specific article:

  - [Highly specific anchor text](/target-slug)
  - [Another highly specific anchor text](/another-target)
- Do not copy and paste the destination post title as the anchor text by default. Rewrite the anchor so it fits the current article's context, stays accurate to the destination, and sparks enough curiosity to earn the click.
- Write each anchor as the reader's most likely next question, tension, or payoff from this specific article. It should explain why that next post matters now, not just name the destination.
- Favor clarity over cleverness. If the anchor sounds vague, ambiguous, or too cute, rewrite it in plainer language while keeping the curiosity.
- Add as many recommendations as the topic honestly supports on non-commercial posts: usually at least 3, sometimes more, with a hard cap of 10. Do not pad the list with weak matches.
- Pick recommended posts that genuinely extend the topic because they feel like strong next reads for this exact reader journey. Use editorial judgment, not category matching or obvious heuristics. You do not need to fully read every recommended post before linking it, but you should believe the recommendation makes sense.
- When creating or revising a post, add or improve natural internal links in the body wherever a reader would want the next step, not only in the closing list.
- Verify links and commands when feasible. If something cannot be verified, tell the user outside the post copy.
- When setting frontmatter timestamps, use UTC rather than the machine's local timezone.
- Skip browser-based validation for routine post-writing tasks. Only use it when the post has tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, or the user explicitly asks for a visual check.

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
7. Add or refresh contextual internal links and, for non-commercial posts, the related-posts list.
8. Validate version-sensitive claims, inline links, and code examples.
9. Do a final publishing pass:
   - required frontmatter is present and valid
   - existing post IDs stayed unchanged
   - featured image and inline images use Cloudflare Images URLs or paths
   - frontmatter promise is aligned
   - `serp_title` and `serp_description` are either `null` or clearly justified and aligned with the visible page copy
   - title and description make a strong, accurate click promise
   - headings read naturally in the generated table of contents
   - contextual internal links were added or improved where helpful
   - non-commercial posts have one up-to-date related-posts list with a custom, natural lead-in that does not echo the title and anchor text that is contextual rather than a pasted destination title
   - commercial posts do not include a related-posts, read-next, or follow-up reading block
   - code and links support the nearby claim
