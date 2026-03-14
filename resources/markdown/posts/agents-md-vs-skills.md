---
id: "01KKPPM7YMGYNYA87XWK553W1P"
title: "When to use AGENTS.md, CLAUDE.md, and skills"
slug: "agents-md-vs-skills"
author: "benjamincrozat"
description: "A simple mental model for AI coding agents: put repo-wide rules in AGENTS.md or CLAUDE.md, and move specialized workflows into reusable skills."
categories:
  - "ai"
published_at: 2026-03-14T18:17:04Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
The confusing part of setting up AI coding agents is that every tool seems to offer a few different places to put instructions.

Should you put everything in `AGENTS.md`? Should you make a pile of rules? Should every repeated workflow become a skill?

The simplest useful model I have found is this:

- `AGENTS.md` or `CLAUDE.md` is the always-on memory for the project
- skills are optional playbooks for specific kinds of work

That split is simple, but it is enough to keep your setup clean.

If you mainly want the dedicated `AGENTS.md` walkthrough first, I already have that in [How to use AGENTS.md with Codex, Cursor, and Claude Code](/agents-md). This article is the broader mental model around where that file stops and skills begin.

## Use AGENTS.md or CLAUDE.md for defaults every task should know

Use the main instruction file for the stuff an agent should know before it does almost anything.

That usually means:

- how to start the app
- which commands to run
- how to verify changes
- what not to touch
- how careful the agent should be with existing edits
- any repo-specific workflow the agent should not have to rediscover

My blog repo is a good example. The public repo is [benjamincrozat/blog-v5](https://github.com/benjamincrozat/blog-v5), and its [`AGENTS.md`](https://github.com/benjamincrozat/blog-v5/blob/main/AGENTS.md) tells an agent:

- to use `composer dev`
- to assume the app already lives at `https://blog-v5.test`
- to keep changes small
- to avoid scope drift
- to fix root causes
- to use a lighter workflow for Markdown-managed posts

That last part matters a lot. A generic agent will not guess that routine post edits in this repo should usually skip browser checks, `pint`, `phpstan`, and `pest`, then just run `php artisan app:sync-posts`.

That kind of instruction belongs in the always-on file because it is a project rule, not a one-off trick.

Here is a tiny excerpt from this repo-level file:

> Keep changes small.
> No scope drift.
> Fix root causes.

That is exactly the kind of thing I want loaded by default on every task.

If you want the bigger reason extra instructions help at all, my [how LLMs work guide](/how-llms-work) is the broader mental model.

## Use skills for specialized work you do sometimes, not always

Skills are for narrower workflows.

They are not the place for "always run tests" or "do not overwrite user edits." They are the place for things like:

- writing a publication-ready post
- doing SEO research for an article
- following Laravel app-layer conventions
- driving a real browser with Playwright

That is why I like skills so much. They let you keep the global file short.

In this repo, I already have local skills like:

- `file-first-posts`
- `post-writing`
- `seo-content`

And I also use global skills like:

- `laravel`
- `playwright`

Those are not universal repo rules. They are reusable playbooks for a specific job.

For example, the local `post-writing` skill includes this line:

> Write for competent beginners first: plain language, short sentences, clear payoff.

That is good advice when I am drafting or revising an article. It does not need to be injected into every coding task in the repo.

The same goes for a `playwright` skill that explains how to automate a real browser, or a `laravel` skill that describes preferred app-layer conventions. Those are useful, but only when the task matches them.

That is the main point: the more task-specific the instruction is, the less it belongs in your always-on file.

If you are trying to understand where MCP fits, that is a separate layer again. My [Laravel MCP review](/laravel-mcp-review) is about app-facing AI integrations, not repo-facing instructions.

## How this maps to Codex, Cursor, and Claude Code

The labels differ a bit, but the mental model is mostly the same.

### Codex

OpenAI's [AGENTS.md guide for Codex](https://developers.openai.com/codex/guides/agents-md) documents `AGENTS.md` as persistent project guidance, and its public examples show Codex combining broader repo instructions with more local overrides.

That makes `AGENTS.md` the right home for defaults.

For the second layer, I like using skills with Codex. OpenAI's [Agent Skills docs](https://developers.openai.com/codex/skills) describe skills as task-specific capabilities that package instructions, resources, and optional scripts.

### Cursor

Cursor's [Rules docs](https://cursor.com/docs/rules) frame `.cursor/rules` as version-controlled project instructions and describe `AGENTS.md` as a simple Markdown alternative for straightforward cases.

Cursor's [CLI docs](https://cursor.com/docs/cli/using) also say the CLI reads `AGENTS.md` and `CLAUDE.md` at the project root and applies them as rules alongside `.cursor/rules`.

So in Cursor, the same split still works: one place for broad defaults, another place for narrower reusable instructions.

### Claude Code

Anthropic's [Claude Code memory docs](https://docs.anthropic.com/en/docs/claude-code/memory) center `./CLAUDE.md` as project memory. Anthropic's [subagents docs](https://docs.anthropic.com/en/docs/claude-code/sub-agents) describe subagents as specialized assistants with their own context windows for task-specific workflows.

So for Claude Code, `CLAUDE.md` fills the "always-on memory" role. Subagents fill a similar "specialized helper" role. I still think "skills" is the clearest generic word for that second layer, even when a tool uses different product language.

## Why this split works

- Less context bloat. Your always-on file stays short because you are not stuffing every niche workflow into it.
- Better reuse. A good skill can be reused across repos without copying a giant project file everywhere.
- Fewer conflicting instructions. Repo-wide rules stay stable, while task-specific guidance only appears when it is relevant.

That is why I would not try to choose between `AGENTS.md`, `CLAUDE.md`, rules, subagents, or skills as if one of them should win.

They solve different problems.

If every task should know it, put it in `AGENTS.md` or `CLAUDE.md`; if only some tasks need it, make it a skill.

If you want to move from repo guidance to the actual AI integration next, my [OpenAI PHP client guide](/openai-php-client) is the practical follow-up for a PHP app.

Once you have the repo-level layer sorted, these are the next reads I would keep open:

- [See why MCP and coding-agent instructions solve different problems](/laravel-mcp-review)
- [Get a simpler mental model for why extra context changes model behavior](/how-llms-work)
- [Build the actual PHP-side AI workflow once your repo instructions are sorted](/openai-php-client)
