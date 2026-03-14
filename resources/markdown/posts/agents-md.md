---
id: "01KKPPM7YMGYNYA87XWK553W1P"
title: "How to use AGENTS.md with Codex, Cursor, and Claude Code"
slug: "agents-md"
author: "benjamincrozat"
description: "Learn what AGENTS.md is, why it helps coding agents, what to put in it, and how to share one instruction file across Codex, Cursor, and Claude Code."
categories:
  - "ai"
published_at: null
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
## What AGENTS.md is

`AGENTS.md` is a repo-level instruction file for coding agents.

Think of it as a `README.md` for tools like Codex, Cursor, or Claude Code. A README explains the project to humans. `AGENTS.md` explains how an agent should behave inside the project: which commands to run, what to avoid, how to verify work, and which repo-specific rules matter.

If you already use coding agents regularly, this one file can remove a lot of repeated prompting.

## Is AGENTS.md a real standard?

It is better described as a **de facto standard** than a formal universal one.

The [AGENTS.md project](https://agents.md/) presents the format as an open, shared convention for guiding coding agents. That is the useful framing: not "one vendor invented another proprietary config file," but "teams need one predictable place for agent instructions."

That matters because the problem is real. If every tool wants a different memory file, your instructions drift fast.

## Why AGENTS.md helps so much

A good `AGENTS.md` usually gives you:

- fewer wrong commands
- better repo-specific behavior
- less repeated prompting
- safer edits and reviews
- easier onboarding for any agent touching the repo

This is not magic. It just gives the model better local context.

If you want the plain-English version of why that works, my guide on [how LLMs work](/how-llms-work) is the bigger-picture explanation.

## Which tools actually use AGENTS.md?

Here is the practical state of things in March 2026.

### Codex

OpenAI's [Codex CLI repository](https://github.com/openai/codex) explicitly supports `AGENTS.md`.

The project documentation explains that Codex loads `AGENTS.md` files from multiple locations, including a global file in `~/.codex/AGENTS.md`, a repo-root file, and a working-directory file for more specific instructions. That makes `AGENTS.md` a first-class way to steer Codex.

### Cursor

Cursor's [rules documentation](https://docs.cursor.com/en/context) documents `AGENTS.md` as a simple Markdown option for agent instructions.

Cursor's [CLI documentation](https://docs.cursor.com/en/cli/using) also says the CLI reads both `AGENTS.md` and `CLAUDE.md` at the project root and applies them as rules alongside `.cursor/rules`.

So if your team uses Cursor, `AGENTS.md` is already a sensible shared entry point.

### Claude Code

Anthropic's [Claude Code memory docs](https://docs.anthropic.com/en/docs/claude-code/memory) still center `CLAUDE.md` as the project memory file.

So for Claude Code, the cleanest cross-tool trick is often:

```bash
ln -s AGENTS.md CLAUDE.md
```

That way, `AGENTS.md` stays your canonical shared file, while Claude Code still sees the project memory file it expects.

This is a workflow tip, not "the standard." Anthropic officially documents `CLAUDE.md`, not `AGENTS.md`, so the symlink is about interoperability and less duplication.

## Use a real repo as the example

My blog is open source on GitHub at [benjamincrozat/blog-v5](https://github.com/benjamincrozat/blog-v5), and it already has a real [`AGENTS.md`](https://github.com/benjamincrozat/blog-v5/blob/main/AGENTS.md).

That file tells an agent things a generic model would never reliably guess on its own:

- which verification commands to run
- that the local app should already be available at `https://blog-v5.test`
- how commit messages should be written
- when Markdown-only post edits should use a lighter verification path
- that it must not overwrite user edits or restore deleted code

That is exactly why this pattern is useful. The repo-specific rules are where most agent mistakes happen.

## A compact AGENTS.md example

You do not need a giant wall of text. A short file with the right sections is much better than a long stale one.

Here is a compact example adapted from this repo:

```md
# Project instructions

## Commands
- Dev: `composer dev`
- Format: `php vendor/bin/pint --parallel`
- Static analysis: `php vendor/bin/phpstan analyse`
- Tests: `php vendor/bin/pest --parallel`

## Environment
- Assume the app is available at `https://blog-v5.test`
- Do not use `php artisan serve` unless that URL is unavailable

## Guardrails
- Keep changes small and fix root causes
- Do not overwrite user edits you did not just make
- Never restore code that was deleted without checking why

## Content workflow
- For Markdown-only post edits in `resources/markdown/posts`, run `php artisan app:sync-posts`
- Skip full app checks unless rendering or publishing behavior makes them necessary

## Verification
- Verify version-specific or vendor-specific claims in official docs when needed
```

That is enough to make an agent much more useful immediately.

## What belongs in AGENTS.md

Keep it focused on instructions the agent can act on:

- commands the agent should run
- local environment assumptions
- repo-specific do and do not rules
- verification steps
- framework or content exceptions
- when to browse or verify external facts instead of guessing

If you are building an MCP server or exposing tools to an external client, that is a different problem. My [Laravel MCP review](/laravel-mcp-review) covers that side of the stack. `AGENTS.md` is about how an agent should work **inside** your codebase.

If your end goal is building the AI feature itself rather than steering a coding agent, my [OpenAI PHP client guide](/openai-php-client) is the more practical next step for a Laravel or PHP app.

## What does not belong in AGENTS.md

Avoid turning it into a dumping ground.

I would keep these out:

- generic coding advice that applies everywhere
- long duplicated docs that already live elsewhere
- stale process notes nobody maintains
- contradictory instructions spread across multiple agent files

The more duplicated your setup becomes, the faster it rots.

## My advice

Keep `AGENTS.md` short, specific, and maintained like code.

If an agent keeps making the same mistake, add the missing instruction. If a rule no longer matches the repo, fix it quickly. The file is small, but it has an outsized effect on output quality.

If you want one mental model, use this: `README.md` is for humans, `AGENTS.md` is for coding agents, and symlinks like `CLAUDE.md -> AGENTS.md` are just compatibility helpers when one tool has not caught up yet.

If you want the next layer after shared agent instructions, these are the reads I would keep open:

- [See why MCP and coding-agent instructions solve different problems](/laravel-mcp-review)
- [Get a simpler mental model for why extra context changes model behavior](/how-llms-work)
- [Build the actual PHP-side AI workflow once your repo instructions are sorted](/openai-php-client)
