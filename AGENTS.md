# benjamincrozat.com

This repo is my personal blog about web developent.

## Check and tidy your work with these commands

- Use your web browser to ensure what's been prompted: visuals and behavior.
- **Format**: `php vendor/bin/pint --parallel`
- **Static analysis**: `php vendor/bin/phpstan analyse`
- **Test**: `php vendor/bin/pest --parallel` (you can use `--filter` to run specific tests)

## Development workflow

- `composer setup`
- `composer dev`
  - This runs multiple processes concurrently.
- Assume the project is always accessible locally at `https://blog-v5.test`. Never use `php artisan serve` unless `https://blog-v5.test` is not accessible.
- Commit every change you make to the codebase. Be as granular as possible.
- When you have to commit, start the message with a short summary (10 words, tops). Then, add a detailed description of the changes (use lists to make it easier to read).
- **Don't push code unless you have my approval.**

## Guardrails to keep in mind

- **Do not overwrite user edits between reads.** If something changed since your last read, understand why and build on it. Or at least, ask the user for clarification.
- **Never restore code that was deleted.** Like said above, if something was deleted, it was for a reason. Ask the user for clarification if necessary.
- **Keep changes small.** Implement the smallest change that solves the problem.
- **No scope drift.** Do not refactor, restyle, or add “nice-to-haves” unless explicitly requested.
- **Fix root causes.** Don’t band-aid symptoms.
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

## Local skills

- `file-first-posts`: Use when the task is about exporting, editing, publishing, or syncing Markdown-managed posts. File: `.agents/skills/file-first-posts/SKILL.md`
- `post-writing`: Use when the task is about drafting or revising publication-ready Markdown posts for the blog. File: `.agents/skills/post-writing/SKILL.md`
- `seo-content`: Use when the task is about search intent, titles, snippets, internal links, AI-search visibility, or top-3 competitor analysis for blog posts and keywords. File: `.agents/skills/seo-content/SKILL.md`
