# benjamincrozat.com

Keep this file limited to repository-specific facts and exceptions. Global instructions, personal preferences, and discovered skills still apply; do not copy them here.

## Main is mandatory

- Work in the permanent checkout at `/Users/benjamin/Sites/blog-v5` on `main` at all times unless the user explicitly says otherwise in the current task.
- This overrides generic isolation advice. A dirty checkout, overlapping work, task size, or risk is not permission to leave `main`. If the requested files already contain conflicting changes, stop and report the conflict.
- Before editing, fetch `origin/main` and fast-forward `main` when that will not disturb existing changes. Do not clean, reset, or stash unrelated work just to sync.
- Stage only the files owned by the current task. After verification, commit and push `main`.

## Worktrees are explicit exceptions

Only use a worktree when the user explicitly requires one in the current task. For this repository:

- Start it from the fully synced `main` branch. The permanent checkout remains the source of truth.
- Copy `.env` from the permanent checkout; never symlink it. Set a worktree-specific `APP_URL=http://127.0.0.1:<port>`.
- `https://blog-v5.test` serves the permanent checkout. Run `php artisan serve --host=127.0.0.1 --port=<port>` inside the worktree and use that URL for its browser checks.
- Post image previews use `BLOG_PREVIEW_BASE_URL` when set and otherwise use `APP_URL`. Point either value at the worktree server before generating an image.
- The copied `.env` targets `blog_v5`, and `phpunit.xml.dist` targets `blog_v5_test`. For schema-changing, data-mutating, or concurrent work, use dedicated databases in `.env` and an ignored worktree-local `phpunit.xml` copied from `phpunit.xml.dist`; do not edit `phpunit.xml.dist` just to isolate a worktree.
- Link `vendor` and `node_modules` from the permanent checkout only while their lockfiles are unchanged. Use worktree-local installs when dependencies change.
- Reuse `public/build` only when the task does not change frontend assets. Never run a build through a linked `public/build`; create a worktree-local build instead.
- `composer setup` installs dependencies, runs migrations, installs npm packages, and builds assets. Do not run it in a worktree that is sharing dependencies, assets, or databases with the permanent checkout.
- After wiring the worktree, run `php artisan about --only=environment` before any formatter, static analysis, test, browser, or image-generation check.
- Unless the task explicitly says to keep the work isolated, bring the verified commit back to the permanent `main`, recheck anything affected by different runtime or build files, push `main`, then remove only a clean worktree and branch whose commit is on `main`. Remove `.playwright-cli/` and `output/` unless the task asks to keep them.

## Local runtime

- The permanent checkout normally runs at `https://blog-v5.test`.
- Use `composer setup` only for a fresh local bootstrap. Use `composer dev` when the task needs the combined development processes.

## Verification commands

Choose the checks that match the changed surface:

- Format PHP: `php vendor/bin/pint --parallel`
- Static analysis: `php vendor/bin/phpstan analyse`
- PHP tests: `php vendor/bin/pest --parallel`
- Frontend build: `npm run build`
