# benjamincrozat.com

This repo is my personal blog about web developent.

## Development workflow

- `composer setup`
- `composer dev`
- Serve the project using `php artisan serve`, but on another port.
- Commit every change you make. Be as granular as possible.
- Create a PR for the active branch as soon as it is ready to publish, and keep that PR up to date as more changes land on the branch.
- When you have to commit, start the message with a short summary (10 words, tops). Then, add a detailed description of the changes (use lists to make it easier to read).

## Worktrees

- Create feature worktrees from a real branch, not a detached HEAD. Preferred pattern: `git worktree add -b codex/<short-name> <path> main`.
- Fresh worktrees may not contain the local runtime files needed to run the app or checks. Reuse the primary checkout's local install by linking these into the new worktree when needed:
  - `.env`
  - `vendor`
  - `node_modules`
  - `public/build`
- In this repo, the primary local checkout is typically `/Users/benjamin/Sites/blog-v5`. If a fresh worktree is missing the files above, link them from there instead of reinstalling everything by default.
- After wiring a fresh worktree, confirm it boots with `php artisan about --only=environment` before running `pint`, `phpstan`, or `pest`.
- `https://blog-v5.test` may still serve the primary checkout rather than the new worktree. If you need to verify branch-specific changes and `blog-v5.test` is not serving the worktree, use `php artisan serve --host=127.0.0.1 --port=<port>` from that worktree and run the browser check against that port.
- Keep worktree-only browser artifacts out of git. Clean up folders like `.playwright-cli/` and `output/` before finishing unless the user explicitly wants those files.

## Guardrails to keep in mind

- **Do not overwrite user edits between reads.** If something changed since your last read, understand why and build on it. Or at least, ask the user for clarification.
- **Never restore code that was deleted.** Like said above, if something was deleted, it was for a reason. Ask the user for clarification if necessary.
- **Keep changes small.** Implement the smallest change that solves the problem.
- **No scope drift.** Do not refactor, restyle, or add “nice-to-haves” unless explicitly requested.
- **Fix root causes.** Don’t band-aid symptoms.
- **Publish new posts in file frontmatter right away.** For new Markdown-managed posts, set `published_at` immediately in UTC unless the user explicitly asks to keep the post unpublished; the open PR is the real draft/review gate until approval.
- **Use web search when needed.** If version-specific behavior, third-party APIs, or unclear edge cases could change the implementation, verify in official docs/release notes and cite the source in your summary.
- **State assumptions when needed.** If a requirement is underspecified, proceed with clearly labeled assumptions; only ask questions when blocked.
- **Be concise and structured.** Prefer short, skimmable answers and concrete next actions over long explanations.
- **Narrate tool usage briefly.** Before multi-step work or tool calls, give a 1–2 sentence “what I’m doing and why” update.
- When executing a plan or a todo list, continue until it's complete. Don't ask for permission between tasks.

## How to verify your work

- **Use your web browser to ensure quality of what's been prompted**: visuals and behavior.
    - Set the resolution to 1512x982 when viewing the page in desktop mode
    - Check that behavior goes according to the specs
    - Take a screenshot and critique it to make sure it's visually correct according to the specs and your taste as a designer
- If you need credentials to log in, use what's in ./database/seeders/UserSeeder.php. The password is always `password`.
- **Format**: `php vendor/bin/pint --parallel`
- **Static analysis**: `php vendor/bin/phpstan analyse`
- **Test**: `php vendor/bin/pest --parallel` (you can use `--filter` to run specific tests)
  - **Check coverage**: `php vendor/bin/pest --coverage --parallel` (you can also use `--filter` if necessary too)
- For routine Markdown-only post edits in `resources/markdown/posts`, verification is lighter by default:
  - required: `php artisan app:sync-posts`
  - optional only when warranted: browser checks, `pint`, `phpstan`, `pest`, or coverage
  - warranted means tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, a publishing-state change that needs confirmation, first-hand screenshots that materially help the reader, or an explicit user request

## Local skills

- For Markdown-managed post work, follow the lighter verification rule above so `file-first-posts`, `post-writing`, and `seo-content` stay aligned with this file.
- `file-first-posts`: Use when the task is about exporting, editing, publishing, or syncing Markdown-managed posts. File: `.agents/skills/file-first-posts/SKILL.md`
- `framework-news-analysis`: Use when the task is about choosing and framing the strongest weekly news angle for a framework, library, tool, platform, or developer product. File: `.agents/skills/framework-news-analysis/SKILL.md`
- `post-writing`: Use when the task is about drafting or revising publication-ready Markdown posts for the blog. File: `.agents/skills/post-writing/SKILL.md`
- `seo-content`: Use when the task is about search intent, titles, snippets, internal links, AI-search visibility, or top-3 competitor analysis for blog posts and keywords. File: `.agents/skills/seo-content/SKILL.md`
