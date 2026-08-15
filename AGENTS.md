# benjamincrozat.com

Keep this file limited to repository-specific facts and exceptions. Global instructions, personal preferences, and discovered skills still apply; do not copy them here.

## Purpose

- Build Benjamin Crozat's long-term authority and name recognition among developers who work on similar problems.
- Publish useful, original work that earns visibility wherever developers look for answers, including search engines and AI assistants.
- Keep the blog broad enough for Benjamin to share his own views on any topic he chooses.

## Main is mandatory

- Work in the primary checkout on `main` at all times unless the user explicitly says otherwise in the current task.
- This overrides generic isolation advice. A dirty checkout, overlapping work, task size, or risk is not permission to leave `main`. If the requested files already contain conflicting changes, stop and report the conflict.
- Before editing, fetch `origin/main` and fast-forward `main` when that will not disturb existing changes. Do not clean, reset, or stash unrelated work just to sync.
- Stage only the files owned by the current task. After verification, commit and push `main`.

## Worktrees are explicit exceptions

Only use a worktree when the user explicitly requires one in the current task. For this repository:

- Create it outside the repository root from fully synced `main`. Keep browser state, screenshots, and scratch output outside both checkouts, preferably in a directory created with `mktemp -d`.
- Copy `.env` from the primary checkout; never symlink it. Set a worktree-specific `APP_URL=http://127.0.0.1:<port>`.
- `https://blog-v5.test` serves the primary checkout. Serve the worktree with `php artisan serve --host=127.0.0.1 --port=<port>` and use that URL for browser checks and `APP_URL`; `BLOG_PREVIEW_BASE_URL` overrides it for post image previews.
- The default app and test databases are `blog_v5` and `blog_v5_test`. Isolate both for schema-changing, data-mutating, or concurrent work by using `.env` and an ignored worktree-local `phpunit.xml` copied from `phpunit.xml.dist`.
- Share `vendor`, `node_modules`, or `public/build` only while dependencies and assets are unchanged. Otherwise use worktree-local copies; never run `composer setup`, an install, or a build through shared paths or databases.
- After wiring the worktree, run `php artisan about --only=environment` before any formatter, static analysis, test, browser, or image-generation check.
- Unless the task explicitly says to keep the work isolated, bring the verified commit back to the primary `main`, recheck anything affected by different runtime or build files, and push `main`.

## Local runtime

- The primary checkout normally runs at `https://blog-v5.test`.
- Use `composer setup` only for a fresh local bootstrap. Use `composer dev` when the task needs the combined development processes.

## Verification

Use the least expensive check that can catch the likely failure:

- Plain documentation or instruction-only changes: inspect the diff and run `git diff --check`; skip application checks unless a matched skill requires one.
- Small UI or copy changes: check the affected page or component. Run `npm run build` only when CSS, JavaScript, or Tailwind class usage changed; do not run the full PHP checks unless PHP behavior changed.
- Focused PHP changes: format the touched PHP files and run the nearest relevant Pest tests.
- Use the full checks only for broad or shared PHP behavior, dependencies, migrations, or when explicitly requested: `php vendor/bin/pint --parallel`, `php vendor/bin/phpstan analyse`, and `php vendor/bin/pest --parallel`.
