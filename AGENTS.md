# Nobinge

This repo is benjamlincrozat.com, a personal blog about web developent.

## Check and tidy your work with these commands

- Use your web browser to ensure what's been prompted: visuals and behavior.
- **Format**: `php vendor/bin/pint --parallel`
- **Test**: `php vendor/bin/pest --parallel` (you can use `--filter` to run specific tests)

## Development workflow

- `composer setup`
- `composer dev`
  - This runs multiple processes concurrently.
- Assume the project is always accessible at `https://benjamincrozat.com`. Never use `php artisan serve`.
- Don't commit or push code unless you have my approval.
- When you have to commit, start the message with a short summary (10 words, tops). Then, add a detailed description of the changes.

## Non-negotiables

- **Do not overwrite user edits between reads.** If something changed since your last read, understand why and build on it. Or at least, ask the user for clarification.
- **Keep changes small.** Implement the smallest change that solves the problem.
- **No scope drift.** Do not refactor, restyle, or add “nice-to-haves” unless explicitly requested.
- **Fix root causes.** Don’t band-aid symptoms.
- **Use web search only when needed.** If version-specific behavior, third-party APIs, or unclear edge cases could change the implementation, verify in official docs/release notes and cite the source in your summary. Otherwise, don’t search.
- **State assumptions when needed.** If a requirement is underspecified, proceed with clearly labeled assumptions; only ask questions when blocked.
- **Be concise and structured.** Prefer short, skimmable answers and concrete next actions over long explanations.
- **Narrate tool usage briefly.** Before multi-step work or tool calls, give a 1–2 sentence “what I’m doing and why” update.

## Read these when you’re working in the area

- **Alpine.js**: `./agents/alpine-js.md`
- **Blade**: `./agents/blade.md`
- **Browser (to confirm UI and behavior changes/fixes)**: `./agents/browser.md`
- **Dependencies**: `./agents/dependencies.md`
- **Laravel**: `./agents/laravel.md`
- **Livewire**: `./agents/livewire.md`
- **PHP**: `./agents/php.md`
- **Project structure**: `./agents/project-structure.md`
- **Tailwind CSS**: `./agents/tailwind-css.md`
- **Testing**: `./agents/testing.md`
