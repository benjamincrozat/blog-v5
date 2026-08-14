---
name: post-writing
description: Draft and revise publication-ready Markdown blog posts with reader-first writing, current primary sources, verified runnable examples, reproducible benchmarks, original screenshots, and clear editorial judgment. Use for evergreen tutorials, troubleshooting guides, comparisons, reviews, performance claims, version-sensitive pages, and timely news in resources/markdown/posts.
---

# Post Writing

## Required Pairing

Pair with `file-first-posts` for sync, publishing, and image upload. Before researching or drafting a substantive post, read [references/evidence-standard.md](references/evidence-standard.md) and make a proof plan. Skip the reference only for a small copy-only revision that adds no new claims.

## Frontmatter Contract

- Required keys: `id`, `title`, `slug`, `author`, `description`, `categories`.
- Keep `id` stable on revisions.
- `author` uses the author's `github_login`; `categories` use category slugs.
- `published_at`, `modified_at`, and `sponsored_at` are UTC ISO-8601 datetimes with trailing `Z` or `null`.
- `serp_title`, `serp_description`, `canonical_url`, `image_disk`, and `image_path` are nullable strings.
- `is_commercial` must be `true` or `false`.
- The filename must match `slug` and end in `.md`.

## Publication Standard

- Do not call a substantive post publication-ready until its central promise is supported by appropriate evidence.
- Build the proof before polishing the prose. Run the example, reproduce the failure, collect the measurements, or capture the important visual state first.
- Match evidence to the claim. Use benchmarks only for quantitative comparisons and screenshots only when a visual state proves or clarifies something.
- Add an original contribution: a tested example, observed failure mode, measured result, first-hand comparison, useful artifact, or clear expert judgment grounded in evidence.
- Never invent command output, benchmark results, screenshots, first-hand experience, or source support.
- If the promised proof cannot be obtained, narrow the claim or report the gap to the user outside the post instead of filling it with generic prose.
- Separate sourced fact, direct observation, and editorial judgment so the reader can tell which is which.

## Rules

- Write for competent beginners first: plain language, short sentences, clear payoff.
- Explain unavoidable technical terms the first time they appear.
- Upload every image with `php artisan app:upload-post-image`; no local paths or hotlinks.
- Create original visuals for walkthroughs, dashboards, UI-heavy how-tos, comparisons, and reviews when they materially add proof or clarity. Capture the state that supports the nearby claim. Skip filler.
- Keep `title`, `description`, `slug`, and any `serp_*` override aligned on one promise.
- No in-body H1. No manual table of contents.
- Draft at least five title options, then choose the clearest one.
- Use sentence case by default. A 40-60 character or 6-9 word title is a soft heuristic, not a hard rule.
- Leave `serp_title` and `serp_description` as `null` unless a tighter search/browser variant is clearly better while keeping the same angle.
- Use dates or years only when the page is current enough to support them.
- `news` posts should lead with the update, cite primary sources inline, and stay tight.
- Open fast. Keep headings descriptive. Use the primary keyword naturally, not repetitively.
- Add context before code. Make examples minimal, runnable, and explicit about versions or assumptions that change the result. Show the relevant output when it proves the behavior.
- Use current primary sources for version-sensitive claims. Prefer relevant internal links before equivalent external links.
- Keep the article body reader-facing. Do not mention internal workflow, prompts, temp projects, or skills inside the post.
- Avoid vague vibe words unless quoting or attributing a source.
- Non-commercial posts need one related-posts list with a custom lead-in ending with `:` and contextual anchors. Usually add at least 3 items; never more than 10.
- Commercial posts (`is_commercial: true`) must not include a related-posts, read-next, or follow-up reading block.
- Pick related posts because they genuinely extend this reader journey, not because they share a category.
- Add or improve natural internal links in the body wherever a reader wants the next step.
- Verify links and every material command or code path when feasible. Record the exact environment for version-sensitive or quantitative results. If something cannot be verified, tell the user outside the post copy.
- Use UTC timestamps with trailing `Z`.
- If the featured image is missing when the copy is stable, run `php artisan app:generate-post-image <slug>`.
- Skip browser validation for copy-only revisions. Use it for source research, tricky rendering, embeds, custom HTML, interactive behavior, and first-hand screenshots that materially prove or clarify the article.

## Common Shapes

- Fix post: reproduce the problem, preserve the exact error, explain the cause, prove the fix, then note edge cases or alternatives.
- Concept post: define the idea simply, explain why it matters, then teach through a runnable example, counterexample, and tradeoffs.
- Comparison or review: perform the same real task with each option, document the setup, show observed differences, and make a bounded recommendation.
- Performance post: define the question and baseline, isolate the variable, publish the environment and method, run repeated measurements, and report limitations.
- News post: lead with the update, explain why it matters now, cite primary sources inline, verify behavioral claims when practical, and close with a few durable evergreen links.

## Flow

1. Define the reader promise, search intent, decision, and post shape.
2. Read the evidence standard and write a proof plan that maps each important claim to a source, test, measurement, screenshot, or explicit judgment.
3. Gather current primary sources and produce the original evidence before drafting around it.
4. Draft at least five title candidates and choose the clearest accurate promise.
5. Decide whether `serp_title` or `serp_description` should stay `null` or get a same-angle override.
6. Pick the simplest outline that helps the reader reach the answer and inspect the proof.
7. Draft for clarity, usefulness, and brevity. Put evidence next to the claim it supports.
8. Capture and upload original visuals when they prove first-hand use or materially clarify the workflow.
9. If the post still has no featured image after the copy is stable, run `php artisan app:generate-post-image <slug>`. Use `--force` only when you intentionally need a fresh render.
10. Add or refresh contextual internal links and, for non-commercial posts, the related-posts list.
11. Re-run material examples and links, review benchmark methodology, and confirm screenshots match the final instructions.
12. Final gate: central promise proven, original contribution clear, limitations stated, frontmatter valid, `id` stable, featured image present, all images on Cloudflare, `serp_*` aligned or `null`, related-posts rules respected, and timestamps use `Z`.
