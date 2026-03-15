---
name: seo-content
description: Optimize evergreen and timely blog posts for Google Search, Discover, and Google News in 2026, including search intent, titles, snippets, internal links, AI-search visibility, and top-3 competitor analysis for supplied keywords. Use alongside post-writing for publication-ready posts.
metadata:
  short-description: Search, Discover, and News guidance for posts
---

# SEO Content

## Scope

Use alongside `post-writing` for new or existing publication-ready posts, or on its own when the user gives keywords and wants SERP or competitor analysis.

Read these references as needed:
- `references/google-2026.md` for current Google guidance
- `references/google-news.md` for Google News and Discover guidance
- `references/competitive-reality.md` for field evidence and grey-hat risk
- `references/serp-analysis.md` for the keyword and top-3 competitor workflow

## Required Rules

- Use live search results for any keyword, SERP, or ranking-sensitive decision. Do not rely on memory for current rankings.
- Use Google for competitor checks, not a generic search engine result feed.
- If the user gives one or more keywords, inspect the current Google SERP and analyze the top 3 organic results before drafting.
- Use the browser MCP when Google’s real SERP layout, ranking order, or feature mix matters.
- When revising an existing post, compare the current page promise against the live Google SERP before changing titles, headings, slug, or angle.
- Use competitors to understand coverage, framing, freshness, and content gaps, not as the main factual basis for our article.
- For facts, version details, policies, benchmarks, and product behavior, prefer official documentation, release notes, specifications, direct testing, or other primary sources.
- If a useful claim appears in competitor content, verify it independently before reusing it.
- Identify dominant search intent before writing: fix, definition, comparison, tool choice, list, landing page, or mixed intent.
- Build around intent plus one clear differentiator. Do not copy a competitor's structure unless you are improving it.
- Keep `title`, `description`, `slug`, intro, and first useful section aligned on the same promise.
- Default to the same core promise for visible page copy and search-facing metadata. Use `serp_title` and `serp_description` only for intentional overrides, not as a second editorial angle.
- `serp_title` is an HTML `<title>` override, not a guaranteed Google-only title. Keep it close to the visible title so Google sees one clear main title.
- `serp_description` is a meta description override. Use it only when it improves on `description` as a page summary; Google may ignore or rewrite it by query.
- There is no hard Google character limit for titles or meta descriptions. Both are truncated to fit device width, so use length as a heuristic, not a rule.
- For CTR, prefer a concrete outcome, strong query match, and a clear differentiator over clever phrasing.
- As a soft title heuristic, aim for roughly 40-60 characters or 6-9 words when possible. Break that range when extra specificity meaningfully improves relevance or trust.
- Front-load the primary query match and main payoff early in the title, because the start of the title is the most consistently visible part.
- For custom descriptions, put the main benefit and query match in the first ~120 characters. A one-sentence summary often lands around 140-160 characters, but clarity matters more than hitting a count.
- Questions are not an automatic CTR win. Positive, specific wording can help, but only when it stays natural and accurate.
- Avoid cheap CTR bait: fake urgency, fake freshness, unsupported numbers, emoji gimmicks, or all-caps unless the query and vertical clearly support them.
- Add a year or date only when the topic is genuinely time-sensitive and the page is actually updated to match.
- Use the primary keyword in the title, intro, slug, and only 1-2 lower headings when natural.
- Prefer clear, specific titles and snippets over keyword stuffing. Google may rewrite both.
- When `categories` includes `news`, decide explicitly whether the piece is best treated as a Google News candidate, a Discover candidate, a classic Search page, or some overlap of the three.
- News posts should prioritize primary-source attribution, visible byline/date transparency, and fast usefulness over exhaustive evergreen coverage.
- Do not assume every news post is a Discover candidate or every Discover candidate is a news post.
- On every new or revised post, add or refresh relevant internal links with descriptive anchors. Treat them as navigation and topical context, not filler.
- If the query or angle is visual by nature, such as interfaces, settings, dashboards, workflows, reviews, or before/after comparisons, assume original screenshots or other first-hand visuals are part of the winning content plan unless the live SERP shows they add no value.
- Treat original screenshots, photos, diagrams, and comparison visuals as indirect SEO support: they can improve clarity, authenticity, image-search eligibility, and AI cite-worthiness, but they are not a standalone ranking factor.
- Do not pad conceptual posts with decorative screenshots. Add visuals only when they clarify the step, prove the claim, or strengthen the page's first-hand value.
- For posts intended to target Discover or Google News strongly, the main image should be original when feasible, not a logo, and at least 1200 px wide.
- Keep the post's related-posts list current as part of that internal-linking pass on non-commercial posts. Update stale recommendations or anchors when the article's angle changes instead of leaving an outdated list behind.
- Commercial posts (`is_commercial: true`) must not include a related-posts, read-next, or follow-up reading block. Keep those pages focused on conversion rather than onward reading.
- On non-commercial posts, the lead-in sentence before the related-posts list must be unique to that article. Do not reuse the same stock phrasing, canned curiosity line, or obvious template across the site.
- Do not quote, parrot, or lightly rephrase the title in that lead-in. Write a clear article-specific bridge that nudges the reader toward the next useful clicks.
- The related-posts list should use that custom lead-in line ending with a colon, then a standard Markdown list with entries like `- [Very specific anchor text](/target-slug)`.
- Do not default to the destination post's exact title as the anchor text. Rewrite anchors so they fit the current article's context, stay accurate, and make the reason to click immediately clear.
- Treat each anchor like the reader's next sensible click from this page. It should surface the next tension, decision, or payoff, not read like a pasted catalog title.
- Keep the wording plain enough that the reader instantly understands why that link matters. Curiosity is useful, but not at the cost of clarity.
- Add only the number of recommendations the topic can support on non-commercial posts: usually 3 or more, sometimes more, with a hard cap of 10. Choose them with editorial judgment based on what a reader would genuinely want next, not by category matching alone. You do not need to fully read every recommended post.
- Keep visible content consistent with frontmatter and any structured data.
- Any image used in the post should be hosted on Cloudflare Images rather than hotlinked from third parties.
- For AI visibility, aim to be cite-worthy: direct answers, strong subheads, original details, and useful examples.
- Do not use fake freshness, fake authorship, fabricated stats, doorway pages, parasite SEO, expired-domain abuse, link schemes, or scaled thin AI filler on this site.

## Competitive Reality

- Google’s public guidance matters, but outside data still shows that rankings, backlinks, title decisions, and brand signals affect clicks and AI citations.
- Use that reality to choose better topics, angles, and titles.
- Do not turn that into spam tactics. This skill is for durable, defensible SEO on this blog.

## Workflow

1. If keywords are provided, run the top-3 competitor brief from `references/serp-analysis.md`.
2. Decide whether the piece should primarily target Search, Discover, Google News, or an overlap of them.
3. If revising an existing post, compare its current promise, freshness, and gaps against the live SERP before changing structure.
4. Summarize dominant intent, recurring subtopics, title and snippet patterns, freshness, weak spots, whether the angle has broad feed appeal, and whether the SERP shows meaningful visual intent.
5. Pick the best angle for us to win on clarity, usefulness, originality, or first-hand value, including a simple decision on whether original screenshots or visuals should be part of the draft.
6. Draft or revise with `post-writing`.
7. Do a final SEO pass:
   - title, description, and slug align
   - `serp_title` and `serp_description` are either `null` or justified, accurate, and aligned with the visible page
   - title starts strong, uses concrete value, and is not relying on gimmicks for clicks
   - description leads with the main benefit and still works if Google rewrites part of the snippet
   - headings match the SERP without sounding repetitive
   - original visuals were created when the SERP or article angle made them a real advantage, and skipped when they would only add filler
   - posts aimed at Discover or Google News have a strong lead image that is original when feasible, not a logo, and at least 1200 px wide
   - news posts are genuinely timely, sourced inline from primary sources, and better suited to a news workflow than an evergreen search page
   - internal links are relevant, descriptive, and updated for the article's current angle
   - non-commercial posts have a related-posts list that is present or refreshed, uses a custom article-specific lead-in that does not echo the title, uses contextual anchor text instead of pasted destination titles, and points to genuinely useful next reads without padding
   - commercial posts do not include a related-posts, read-next, or follow-up reading block
   - claims are backed by official or primary sources, not just competitors
   - the page is strong for both classic search and AI search features
