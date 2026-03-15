---
name: post-writing
description: Draft and revise evergreen and timely blog posts that are concise, source-backed, and reader-first.
metadata:
  short-description: Write evergreen and timely reader-first posts
---

# Post Writing

## Scope

Use for drafting or revising publication-ready posts in `resources/markdown/posts`.
Pair with `seo-content` for Search intent, Discover/News judgment, titles, snippets, and internal linking.

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
- Upload every image used in the post to Cloudflare Images with `php artisan blog:upload-image`, including featured images, inline screenshots, badges, logos, and comparison cards.
- Do not leave local file paths or third-party hotlinked images in post Markdown.
- For walkthroughs, dashboards, UI-heavy how-tos, comparisons, and reviews, actively look for places where an original screenshot, crop, or simple visual would make the article clearer or more credible.
- Create those visuals yourself when feasible instead of leaving a TODO or only describing the interface in prose.
- Use visuals as proof and clarification, not decoration. Skip them when the topic is conceptual, code-first, or the image would only repeat the nearby text.
- Give every kept visual a descriptive filename, place it near the relevant text, and write useful alt text before uploading it to Cloudflare Images.
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
- For new posts, set `published_at` in UTC as part of the initial frontmatter unless the user explicitly asks to keep the post unpublished; the open PR is the draft/review gate until approval.
- If `categories` includes `news`, treat the draft as a news post: lead with the new information, verify claims with primary sources, attribute those sources inline, keep the structure tight, and avoid padding it into an evergreen explainer.
- For posts intended to target Google News or Discover strongly, the primary article image should be original when feasible, not a logo, and at least 1200 px wide.
- Once the post copy and title are stable, make sure the post has a featured image. If `image_disk` / `image_path` are still empty, generate one with `php artisan app:generate-post-image your-post-slug` before finishing.
- For evergreen posts meant to compete in Discover, sharpen the opening payoff and framing, but do not force a news structure onto the draft.
- Avoid clickbait phrasing, vague curiosity hooks, fake urgency, unsupported superlatives, emojis, or decorative all-caps.
- Open fast: explain the problem, answer, or reason to care in the first few paragraphs.
- Keep headings descriptive and skimmable. Use the exact primary keyword in only 1-2 headings if it reads naturally.
- Prefer practical examples and code when they help, but never leave a section as code-only.
- Add context before snippets and explain what they do, why they matter, and what result to expect.
- Use current primary sources for version-sensitive claims. Link inline at the claim, and prefer relevant internal links before equivalent external links.
- Keep the article body reader-facing. Do not mention your drafting workflow, prompts, temp projects, verification steps, or that you followed internal instructions or skills inside the post copy. If first-hand use materially matters, report the result in plain reader terms rather than narrating your behind-the-scenes process.
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
- Skip browser-based validation for routine post-writing tasks. Only use it when the post has tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, the article needs first-hand screenshots that materially help the reader, or the user explicitly asks for a visual check.

## Common Shapes

- Fix post:
  state the problem quickly, explain the likely cause, show the fix, and close with edge cases or alternatives.
- Concept post:
  define the idea in simple words, show why it matters, then teach through examples and gotchas.
- News post:
  lead with the update, explain why it matters now, attribute the primary sources inline, add only the context needed to understand the change, and close with 2-4 durable links into relevant evergreen posts.

## Workflow

1. Define the reader promise, search intent, and whether this is an evergreen post, a news post, or a broader Discover candidate.
2. Draft five or more title candidates and choose the strongest one.
3. Decide whether `serp_title` or `serp_description` need an override; otherwise leave them `null`.
4. Check that the chosen title and description earn the click with a clear outcome, not a gimmick.
5. Pick the simplest outline that matches the post shape, and keep it tighter when the post is news.
6. Draft for clarity, usefulness, and brevity.
7. Decide whether original screenshots or simple visuals would materially clarify the piece or prove first-hand use; create them when the answer is yes.
8. If the post still has no featured image after the copy is stable, run `php artisan app:generate-post-image your-post-slug`. If you intentionally need a fresh render for an existing generated image, use `--force`.
9. Add or refresh contextual internal links and, for non-commercial posts, the related-posts list.
10. Validate version-sensitive claims, inline links, and code examples.
11. Do a final publishing pass:
   - required frontmatter is present and valid
   - existing post IDs stayed unchanged
   - the post has a featured image, whether custom-uploaded or generated through `php artisan app:generate-post-image`
   - every image used in the post, including featured images and inline assets, uses Cloudflare Images URLs or paths
   - screenshots or simple visuals were added when they materially improved clarity, credibility, or first-hand evidence, and skipped when they would have been filler
   - frontmatter promise is aligned
   - new posts have a non-null `published_at` unless the user explicitly asked to keep them unpublished
   - `serp_title` and `serp_description` are either `null` or clearly justified and aligned with the visible page copy
   - title and description make a strong, accurate click promise
   - headings read naturally in the generated table of contents
   - contextual internal links were added or improved where helpful
   - non-commercial posts have one up-to-date related-posts list with a custom, natural lead-in that does not echo the title and anchor text that is contextual rather than a pasted destination title
   - commercial posts do not include a related-posts, read-next, or follow-up reading block
   - code and links support the nearby claim
