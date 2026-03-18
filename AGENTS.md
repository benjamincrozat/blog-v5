# benjamincrozat.com

Agent rules for this repo.

## Start

- Sync the active branch/worktree with latest `main` before work.
- Boot with `composer setup` and `composer dev`.
- Use `php artisan serve --host=127.0.0.1 --port=<port>` for branch-specific checks.
- Commit every change; keep commits small.
- Commit subject: 10 words or fewer, then a detailed list.
- Open/update the PR when the branch is publishable.

## Worktrees

- Use real branches, not detached HEADs: `git worktree add -b codex/<name> <path> main`.
- If detached or behind, switch to a fresh `codex/...` branch from `origin/main` before editing.
- Reuse runtime files from `/Users/benjamin/Sites/blog-v5`: `.env` (copy if local overrides are needed), `vendor`, `node_modules`, `public/build`.
- After wiring a worktree, run `php artisan about --only=environment` before `pint`, `phpstan`, or `pest`.
- `https://blog-v5.test` may hit another checkout; use the local `php artisan serve` URL for browser checks.
- For post image generation in a worktree, set `APP_URL=http://127.0.0.1:<port>` in that worktree's `.env`. `BLOG_PREVIEW_BASE_URL` overrides `APP_URL`.
- Remove worktree-only artifacts like `.playwright-cli/` and `output/` before finishing unless asked to keep them.

## Guardrails

- Never overwrite user edits between reads.
- Never restore deleted code without confirmation.
- Make the smallest fix that solves the problem.
- No scope drift: no refactors, restyles, or extras unless asked.
- Fix root causes, not symptoms.
- Use web search for unstable or version-specific behavior; cite sources.
- State assumptions; ask only when blocked.
- Briefly narrate multi-step tool usage.
- Finish the full plan once started.

## Verify

- Visual/behavior changes: use a browser, set desktop to `1512x982`, confirm against spec, take a screenshot, critique the result.
- Login seed: `database/seeders/UserSeeder.php`, password `password`.
- Format: `php vendor/bin/pint --parallel`
- Static analysis: `php vendor/bin/phpstan analyse`
- Tests: `php vendor/bin/pest --parallel`
- Coverage when needed: `php vendor/bin/pest --coverage --parallel`
- Routine Markdown-only edits in `resources/markdown/posts`: run `php artisan app:sync-posts`. Add browser checks, `pint`, `phpstan`, `pest`, or coverage only for tricky rendering, embeds, custom HTML, unusual formatting, interactive behavior, publishing-state checks, useful first-hand screenshots, or explicit requests.

## Local skills

- `file-first-posts`: Markdown post export/edit/publish/sync. File: `.agents/skills/file-first-posts/SKILL.md`
- `framework-news-analysis`: Weekly framework/tool news angle and sourcing. File: `.agents/skills/framework-news-analysis/SKILL.md`
- `post-writing`: Publication-ready blog drafting/revision. File: `.agents/skills/post-writing/SKILL.md`
- `seo-content`: Search, Discover, News, and competitor framing. File: `.agents/skills/seo-content/SKILL.md`
