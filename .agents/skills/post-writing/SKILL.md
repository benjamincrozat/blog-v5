---
name: post-writing
description: Draft and revise educational blog posts that are concise, source-backed, and reader-first.
metadata:
  short-description: Write concise, researched, reader-first posts
---

# Post Writing

## Scope

Use for drafting or revising blog posts and publication-ready markdown content.

## Required Rules

- Keep writing reader-first, practical, and concise.
- Use plain language targeting roughly a 5th-grade reading level; explain unavoidable technical terms in simple words at least once when first introduced.
- Write titles for competent beginners first: plain language, clear outcome, and clear reason to care.
- Use sentence case for titles by default; keep standard uppercase for acronyms and official product naming.
- Keep titles at 60 characters or fewer (including spaces and punctuation).
- If a technical term is necessary in the title, pair it with a plain-language payoff in the same line.
- Avoid vague filler in titles (for example `mostly`, `practical`, `now`, `guide`) unless it adds concrete meaning.
- Draft at least five title candidates and pick the strongest one against the title quality checks.
- Use first person sparingly to add real experience, not to center the author.
- Use current, primary sources for version-sensitive claims.
- Place links inline at the claim/action they support; no footnotes.
- Verify every in-post link works and is still relevant to the nearby claim.
- Prefer relevant internal links before equivalent external links.
- Do not include a manual table of contents.
- Do not include an in-body H1 (`# ...`); set title in frontmatter.
- Keep headings unnumbered, descriptive, and useful when skimmed in the auto-generated table of contents.
- Keep SEO intent, but use the exact primary keyword in only 1-2 strategic H2/H3 headings when it reads naturally.
- Use semantic variants or direct topic labels for remaining headings; avoid repeating the same keyword prefix across consecutive headings.
- If the heading list reads repetitive, pushy, or salesy in the table of contents, rewrite for natural language first.
- Include practical code examples when they help the reader apply the guidance.
- Never leave a section as code-only: add context before snippets and explain what they do, why they matter, and what result to expect.
- Verify code snippets/commands when feasible; mark unverified snippets explicitly with reason.
- Do not run browser-based validation (Playwright/MCP/screenshots) for post-writing tasks, including updates to existing posts.
- For post content quality, validate technical and visual claims from primary sources instead of browser verification.
- Keep workflow/compliance notes out of post copy. Do not write meta statements about why a validation method was chosen unless the post topic explicitly requires that explanation.

## Workflow

1. Define the reader promise and decision context before drafting.
2. Build a focused outline from takeaway to implementation.
3. Draft and revise for actionability, brevity, and evidence quality.
4. Validate technical claims, in-post link quality, and source-backed visual claims before finalizing (without browser verification).
5. Apply publishing checks before sync.

## References

- `references/workflow.md`
- `references/checklist.md`
