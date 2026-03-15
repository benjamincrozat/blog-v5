# benjamincrozat.com

This repo is my personal blog about web development.

## Development workflow

- Before starting any task, pull the latest changes from `main` into the active branch or worktree.
- `composer setup`
- `composer dev`
- Serve the project with `php artisan serve` on a non-default port.
- Commit every change you make, as granularly as practical.
- Open a PR once the branch is ready to publish, and keep it updated as more changes land.
- Commit messages should start with a short summary of 10 words or fewer, followed by a detailed list of changes.

## Worktrees

- Create feature worktrees from a real branch, not a detached HEAD. Preferred pattern: `git worktree add -b codex/<short-name> <path> main`.
- If a worktree is detached or behind, switch to a real `codex/...` branch from the latest `origin/main` before making changes.
- Fresh worktrees may not contain the local runtime files needed to run the app or checks. Reuse the primary checkout's local install by linking these into the new worktree when needed:
  - `.env` (copy it instead of linking if the worktree needs its own `APP_URL` or other local-only overrides)
  - `vendor`
  - `node_modules`
  - `public/build`
- The primary local checkout is usually `/Users/benjamin/Sites/blog-v5`. Link missing runtime files from there instead of reinstalling by default.
- After wiring a fresh worktree, confirm it boots with `php artisan about --only=environment` before running `pint`, `phpstan`, or `pest`.
- `https://blog-v5.test` may still serve the primary checkout instead of the new worktree. When you need branch-specific browser checks, serve the worktree with `php artisan serve --host=127.0.0.1 --port=<port>` and test against that port.
- Post image generation uses `BLOG_PREVIEW_BASE_URL` when set, otherwise it falls back to `APP_URL`. For a worktree served through `php artisan serve`, use a worktree-specific `.env` copy and set `APP_URL` to the matching `http://127.0.0.1:<port>` before generating images.
- Keep worktree-only browser artifacts out of git. Clean up folders like `.playwright-cli/` and `output/` before finishing unless the user explicitly wants those files.

## Guardrails to keep in mind

- **Do not overwrite user edits between reads.** If something changed since your last read, understand why and build on it. Ask for clarification only when needed.
- **Never restore deleted code.** If something was removed, assume it was intentional until you confirm otherwise.
- **Keep changes small.** Implement the smallest change that solves the problem.
- **No scope drift.** Do not refactor, restyle, or add “nice-to-haves” unless explicitly requested.
- **Fix root causes.** Don’t band-aid symptoms.
- **Use web search when needed.** If version-specific behavior, third-party APIs, or unclear edge cases could change the implementation, verify in official docs/release notes and cite the source in your summary.
- **State assumptions when needed.** If a requirement is underspecified, proceed with clearly labeled assumptions; only ask questions when blocked.
- **Be concise and structured.** Prefer short, skimmable answers and concrete next actions over long explanations.
- **Narrate tool usage briefly.** Before multi-step work or tool calls, give a 1–2 sentence “what I’m doing and why” update.
- When executing a plan or todo list, continue until it is complete. Don’t ask for permission between tasks.

## How to verify your work

- **Use your web browser when the task affects visuals or behavior.**
  - Set desktop checks to `1512x982`.
  - Confirm the behavior matches the spec.
  - Take a screenshot and critique the result for visual quality.
- If you need credentials to log in, use what's in ./database/seeders/UserSeeder.php. The password is always `password`.
- **Format**: `php vendor/bin/pint --parallel`
- **Static analysis**: `php vendor/bin/phpstan analyse`
- **Test**: `php vendor/bin/pest --parallel` (you can use `--filter` to run specific tests)
  - **Check coverage**: `php vendor/bin/pest --coverage --parallel` (you can also use `--filter` if necessary too)
- For routine Markdown-only post edits in `resources/markdown/posts`, use lighter verification by default:
  - required: `php artisan app:sync-posts`
  - optional only when warranted: browser checks, `pint`, `phpstan`, `pest`, or coverage
  - warranted means tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, a publishing-state change that needs confirmation, first-hand screenshots that materially help the reader, or an explicit user request

## Local skills

- For Markdown-managed post work, follow the lighter verification rule above so these skills stay aligned with this file.
- `file-first-posts`: Export, edit, publish, or sync Markdown-managed posts. File: `.agents/skills/file-first-posts/SKILL.md`
- `framework-news-analysis`: Choose and frame the strongest weekly news angle for a framework, library, tool, platform, or developer product. File: `.agents/skills/framework-news-analysis/SKILL.md`
- `post-writing`: Draft or revise publication-ready Markdown posts for the blog. File: `.agents/skills/post-writing/SKILL.md`
- `seo-content`: Handle search intent, titles, snippets, internal links, AI-search visibility, and top-3 competitor analysis for blog posts and keywords. File: `.agents/skills/seo-content/SKILL.md`
