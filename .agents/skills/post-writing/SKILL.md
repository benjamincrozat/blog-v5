---
name: post-writing
description: Draft and revise publication-ready Markdown blog posts with reader-first writing, current primary sources, tested examples, reproducible benchmarks for quantitative claims, visually inspected screenshots, evidence-led SEO experiments, and clear editorial judgment. Use for new posts, search-focused refreshes, tutorials, troubleshooting guides, comparisons, reviews, performance claims, new libraries, major framework features, version-sensitive pages, and timely news in resources/markdown/posts.
---

# Post Writing

## Required Pairing

- Pair with `file-first-posts` for Markdown, front matter, images, syncing, and publishing.
- Read [references/evidence-standard.md](references/evidence-standard.md) and make a proof plan before researching or drafting a substantive post. Skip it only for a small copy-only edit that adds no claims.
- For an existing post whose traffic, ranking, click-through rate, or search intent matters, pair with `seo-growth-strategy` and read [references/ranking-reality.md](references/ranking-reality.md) and [references/refresh-standard.md](references/refresh-standard.md) before changing its promise, title, headings, internal links, or slug.
- Pair with `framework-news-analysis` when choosing or framing timely framework, library, tool, platform, or developer-product news.

## Frontmatter Contract

- Keep the required keys: `id`, `title`, `slug`, `author`, `description`, and `categories`.
- Keep `id` stable on revisions.
- Use the author's `github_login` for `author` and category slugs for `categories`.
- Use UTC ISO-8601 datetimes with trailing `Z`, or `null`, for `published_at`, `modified_at`, and `sponsored_at`.
- Use strings or `null` for `serp_title`, `serp_description`, `canonical_url`, `image_disk`, and `image_path`.
- Set `is_commercial` to `true` or `false`.
- Match the `.md` filename to `slug`.

## Publication Standard

- Prove the central promise before calling a substantive post publication-ready.
- Build proof before polishing prose. Run the example, reproduce the failure, collect the measurements, or inspect and capture the important visual state first.
- Test a central behavioral claim when it is technically possible. If it cannot be tested, narrow the claim and treat the piece as reported analysis rather than first-hand validation.
- Match evidence to the claim. Require first-hand proof, but do not force every artifact type. Use benchmarks only for quantitative comparisons, screenshots only for visual states, and version matrices only when compatibility or support differs.
- Add something readers cannot get from a summary: a tested example, observed failure, measured result, first-hand comparison, useful artifact, real implementation detail, or clear judgment grounded in evidence.
- Open and inspect every existing screenshot before judging whether it is useful, stale, clear, or worth replacing. Never infer screenshot quality from its filename, dimensions, alt text, or presence in Markdown.
- Never invent output, results, screenshots, experience, source support, or a version combination that was not tested.
- If promised proof cannot be obtained, narrow the claim or report the gap outside the article instead of filling it with generic prose.
- Separate sourced facts, direct observations, and editorial judgment.

## Editorial Rules

- Write for competent beginners first. Use plain language, short sentences, and a clear payoff.
- Explain an unavoidable technical term the first time it appears.
- Give the answer or decision early. Avoid generic `Introduction` and `Conclusion` headings.
- Use descriptive headings. Add an FAQ only when it answers distinct questions that the body does not already answer.
- Keep `title`, `description`, `slug`, the opening, and any `serp_*` override on one promise.
- Choose a concise, descriptive title without a fixed character or word target. Put the clearest form of the main query near the start when it matches the article's real promise. Draft alternatives only when they help resolve a real tradeoff.
- Leave `serp_title` and `serp_description` as `null` unless a same-angle override is clearly better for search or browser display.
- Make the title, opening answer, headings, body terms, and internal anchor text clearly relevant to the target query group. Use close variants where they improve clarity; do not repeat phrases only to hit a density or placement checklist.
- Use dates or years when freshness affects the query and the page is current enough to support them. Keep visible, structured, sitemap, and front matter dates consistent. Change `modified_at` only for a meaningful factual or editorial update.
- Add context before code. Copy final examples from the tested fixture instead of retyping them, and rerun the exact published form after editing.
- State the versions and assumptions that change a result. Show output, a test, or changed state when it proves the behavior.
- Use current primary sources for version-sensitive facts. Link to those sources even when an internal article covers the same topic. Use internal links for useful next steps, not as a substitute for evidence.
- Keep the article body reader-facing. Do not mention prompts, temp projects, skills, proof plans, or internal workflow.
- Remove filler, vague claims, copied release-note prose, and unsupported first-person claims.
- Plan internal links as ranking signals and reader paths. Add descriptive contextual links from relevant, strong existing pages to a priority post, and link the post to its real parent, supporting pages, and next steps. Do not rely only on a footer or force an arbitrary count.
- Add a related-posts block to a non-commercial post only when it strengthens that plan. Use an article-specific lead-in and descriptive anchors; do not force a minimum count.
- Keep commercial posts (`is_commercial: true`) free of related-posts, read-next, or follow-up reading blocks.
- Define the post's business role, useful next action, authority target, and distribution plan outside the draft. For a priority post, state what could earn a citation, mention, link, or branded search. Add a restrained service or contact path only when it helps this reader.
- Keep body content free of an H1 and a manual table of contents.

## Common Shapes

- Refresh post: preserve proven intent and useful material, replace stale or weak parts, add new proof, and measure against a saved baseline.
- Fix post: reproduce the problem, preserve the exact error, explain the cause, prove the fix, and state when it does not apply.
- Concept post: define the idea simply, then teach it through a runnable example, counterexample, decision boundary, and simpler alternative.
- Library or framework release: verify the release state and exact versions, test the central feature or upgrade path, publish a tested support matrix when it helps, and tell a named reader whether to adopt, wait, or skip.
- Comparison or review: perform the same real task with each option, document the setup, show observed differences, and make a bounded recommendation.
- Performance post: define the question and baseline, isolate the variable, publish the environment and method, run repeated measurements, and report limitations.
- News post: lead with the update, explain why it matters now, cite primary sources inline, and separate what shipped from what was announced or inferred.

## Flow

1. Decide whether the task is a new post, a refresh, or a small copy edit. Define the reader outcome, search intent, decision, business role, and useful next action.
2. For ranking work, read the ranking reality and refresh standards. Inspect current demand and live search results, read the whole article, map incoming and outgoing internal links, and open every existing screenshot before proposing changes.
3. Read the evidence standard and map every important claim to a source, test, measurement, inspected visual, or explicit judgment.
4. Gather current primary sources and build the runnable fixture, reproduction, benchmark, comparison, or visual proof before drafting around it.
5. Choose the simplest accurate title and outline. Make the main query and promise clear early. Keep the existing slug and winning angle unless evidence supports a change.
6. Draft for clarity and usefulness. Put each source or observed result next to the claim it supports.
7. Add or replace visuals only after inspecting the actual assets. Make the final image readable in the rendered article, not only at full resolution.
8. Strengthen useful incoming and outgoing internal links, then add direct links to external primary evidence. Remove repeated FAQs, filler endings, and weak mechanical link blocks.
9. Rerun the exact published examples, verify factual links, review benchmark limits, inspect every final image, and check the rendered article when layout or visuals matter.
10. Hand the stable article to `file-first-posts` for image upload, timestamps, sync, publishing checks, and final repository validation.

Final gate: prove the central promise, add a clear original contribution, state important limits, preserve valid front matter and stable IDs, and leave no untested claim disguised as first-hand experience.
