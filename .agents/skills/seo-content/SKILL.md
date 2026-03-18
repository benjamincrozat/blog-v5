---
name: seo-content
description: Optimize evergreen and timely blog posts for Google Search, Discover, and Google News in 2026, including search intent, titles, snippets, internal links, AI-search visibility, and top-3 competitor analysis for supplied keywords. Use alongside post-writing for publication-ready posts.
metadata:
  short-description: Search, Discover, and News guidance for posts
---

# SEO Content

## Trigger

Use with `post-writing` for publication-ready posts, or alone when the user wants keyword, SERP, or competitor analysis.

Read references only when needed:
- `references/google-2026.md`
- `references/google-news.md`
- `references/competitive-reality.md`
- `references/serp-analysis.md`

## Rules

- Use live Google results for any keyword, SERP, or ranking-sensitive decision.
- If keywords are supplied, inspect the live SERP and analyze the top 3 organic results before drafting.
- Use the browser MCP when the real SERP layout, ordering, or feature mix matters.
- On revisions, compare the current page promise against the live SERP before changing title, headings, slug, or angle.
- Use competitors for framing, gaps, and freshness, not as the main factual basis. Verify reusable claims with primary sources.
- Identify dominant intent before writing: fix, definition, comparison, tool choice, list, landing page, or mixed intent.
- Build around intent plus one clear differentiator. Do not copy a competitor structure unless you are improving it.
- Keep `title`, `description`, `slug`, intro, and first useful section aligned on one promise.
- Use `serp_title` and `serp_description` only for intentional same-angle overrides, not a second editorial angle.
- Prefer concrete outcomes, strong query match, and front-loaded payoff over gimmicks. Use dates only when the page is genuinely current.
- Use the primary keyword naturally in the title, intro, slug, and only 1-2 lower headings.
- Decide explicitly whether the piece is best for Search, Discover, Google News, or some overlap.
- `news` posts should prioritize primary-source attribution, visible freshness, and fast usefulness.
- Refresh internal links on every new or revised post.
- If the query or angle is visual, assume original visuals are part of the winning plan unless the live SERP says otherwise.
- Use visuals as indirect SEO support only when they improve clarity, authenticity, or cite-worthiness. Skip decorative filler.
- For Discover or News targets, the main image should be original when feasible, not a logo, and at least 1200 px wide.
- Keep related-posts current on non-commercial posts. Commercial posts (`is_commercial: true`) must not include related-posts or read-next blocks.
- Host all post images on Cloudflare Images.
- Aim to be cite-worthy for AI and classic search: direct answers, strong subheads, original details, useful examples.
- No fake freshness, fake authorship, fabricated stats, doorway pages, parasite SEO, expired-domain abuse, link schemes, or scaled thin AI filler.

## Competitive Reality

- Public Google guidance matters, but rankings, backlinks, titles, and brand still affect clicks and citations.
- Use that reality to choose better topics and framing, not spam tactics.

## Flow

1. If keywords are provided, run the top-3 competitor brief from `references/serp-analysis.md`.
2. Decide whether the piece primarily targets Search, Discover, Google News, or an overlap.
3. If revising an existing post, compare its current promise and freshness against the live SERP first.
4. Summarize dominant intent, recurring subtopics, title/snippet patterns, freshness, weak spots, and visual intent.
5. Pick the angle we can win on through clarity, usefulness, originality, or first-hand value, including whether original visuals are needed.
6. Draft or revise with `post-writing`.
7. Final SEO pass: title/description/slug align, `serp_*` are aligned or `null`, title/snippet lead with concrete value, headings fit intent without stuffing, visuals are added only when useful, Discover/News images are strong, news claims are timely and primary-source-backed, internal links are current, related-posts rules are respected, and claims rely on primary sources rather than competitors.
