# Post Writing Checklist

## Final Quality Check

- The post teaches something actionable.
- At least five title candidates were drafted before final title selection.
- The final title is immediately understandable to a novice on first read.
- The final title promises one concrete reader outcome.
- The final title uses sentence case except for acronyms, proper nouns, and official product casing.
- The final title is 60 characters or fewer (including spaces and punctuation).
- If the title includes a technical term, it also includes a plain-language payoff.
- The title avoids vague filler words (`mostly`, `practical`, `now`, `guide`) unless they add real specificity.
- Each section answers a concrete reader need.
- Language is simple and clear, targeting about a 5th-grade reading level.
- Jargon is defined in plain language at least once where it first appears.
- Code examples are included when they improve implementation clarity.
- No section is code-only; each snippet includes context and a short explanation of expected behavior/outcome.
- First-person usage is controlled and purposeful.
- Claims are source-backed and current.
- Links are inline, relevant, and official where possible.
- Every in-post link was checked to confirm it works and still supports the nearby claim.
- Existing on-site coverage is linked before equivalent external references.
- Runnable examples were executed, or explicitly marked unverified with reason.
- Headings are descriptive, unnumbered, and read naturally in the auto-generated table of contents.
- The exact primary keyword appears in only 1-2 strategic headings; other headings use natural variants or direct topic labels.
- No manual table of contents appears in the body.
- No in-body H1 appears in markdown content.
- Visual claims are backed by source material without browser-based validation for post-writing tasks (new posts and edits).
- Content is concise and free of filler.
- No meta/process narration appears in the post body unless the post is explicitly about that process.

## Blog Publishing Checks

1. Set `published_at` in the past relative to app timezone to avoid hidden posts.
2. Run `php artisan blog:sync` and verify index/show visibility.
3. Store images under `public/img/...` and use absolute site paths (for example `/img/openai/codex-app-announcement.png`).
4. If visibility is wrong, check publication timestamps before debugging routes/templates.
