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
- `is_commercial` must be `true` or `false`.
- The filename must match `slug` and end in `.md`.

## Required Rules

- Write for competent beginners first: plain language, short sentences, clear payoff.
- Explain unavoidable technical terms in simple words the first time they appear.
- Keep the promise tight across frontmatter: `title`, `description`, and `slug` should point to the same outcome.
- Do not add an in-body H1 or a manual table of contents.
- Draft at least five title options, then pick the clearest one.
- Use sentence case for titles by default. Aim for 60 characters or fewer when possible.
- Prefer titles with a plain-language outcome over clever phrasing or vague filler.
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
3. Pick the simplest outline that matches the post shape.
4. Draft for clarity, usefulness, and brevity.
5. Validate version-sensitive claims, inline links, and code examples.
6. Do a final publishing pass:
   - required frontmatter is present and valid
   - existing post IDs stayed unchanged
   - frontmatter promise is aligned
   - headings read naturally in the generated table of contents
   - code and links support the nearby claim
