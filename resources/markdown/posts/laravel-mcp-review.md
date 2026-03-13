---
id: "01KKK2YPX50X3HW6HY20ERM4EW"
title: "Laravel MCP feels polished, and the SaaS case is getting real"
slug: "laravel-mcp-review"
author: "benjamincrozat"
description: "Laravel MCP is still not for every app, but more SaaS products now expose governed AI actions through MCP."
categories:
  - "laravel"
  - "news"
published_at: 2026-03-13T07:53:09Z
modified_at: 2026-03-13T08:12:28Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
Laravel's official [MCP documentation](https://laravel.com/docs/12.x/mcp), [Laravel MCP product page](https://laravel.com/ai/mcp), and last week's ["AI SDK, Boost, or MCP: Which Tool Do You Need?"](https://laravel.com/blog/laravel-ai-sdk-boost-or-mcp-which-tool-do-you-need) article all point in the same direction: Laravel MCP is real, polished, and narrower than the current hype cycle makes it sound.

I still think that is the right framing. Most Laravel teams do not need MCP just because they are doing "AI stuff."

But I like the package more after looking at the broader market around it.

The strongest case for Laravel MCP is not "agents need APIs." It is that more SaaS products now want to expose a smaller, safer, AI-facing layer to external clients. In that world, Laravel MCP starts to make much more sense.

## What Laravel MCP is actually for

Laravel's own summary from the [March 4 article](https://laravel.com/blog/laravel-ai-sdk-boost-or-mcp-which-tool-do-you-need) is still the cleanest one I have seen:

- the AI SDK helps you build AI features into your app
- Boost helps AI agents write better Laravel code for you
- MCP helps external AI tools interact with your app

That last point is the important one.

If your goal is "I want to add chat, agents, summaries, embeddings, or AI workflows inside my product," Laravel is telling you to look at the AI SDK first, not MCP.

If your goal is "I want Claude Code or another coding agent to understand my Laravel project better," that is a Boost problem, not an MCP problem.

Laravel MCP becomes the right tool when your app should expose tools, resources, or prompts to an external AI client through the Model Context Protocol.

That sounds narrow, but it is becoming easier to picture in real products.

## The enterprise case is not hypothetical anymore

[Anthropic's MCP docs](https://docs.anthropic.com/en/docs/build-with-claude/mcp) position MCP as the standard way Claude connects to external tools and data. [OpenAI's MCP docs](https://platform.openai.com/docs/mcp/) now do the same, and they explicitly tell developers to prefer official servers run by the service providers themselves.

That last part matters.

It suggests the next phase of MCP is not just hobby servers and internal demos. It is SaaS vendors exposing official capabilities that AI clients can trust.

A few examples make the shift obvious:

- [GitHub's remote MCP server](https://github.blog/changelog/2025-09-04-remote-github-mcp-server-is-now-generally-available/) is already generally available with OAuth 2.1 + PKCE, centralized policy controls, and structured access to GitHub workflows.
- [Linear's MCP server](https://linear.app/docs/mcp) is presented as a secure way for compatible AI tools to access Linear data, with support for OAuth and restricted API keys.
- [Atlassian's Rovo MCP server](https://confluence.atlassian.com/cloud/blog/2026/03/atlassian-cloud-changes-mar-2-to-mar-9-2026) is now GA for Jira, Confluence, and Compass, with domain controls, IP allowlist support, audit logs, and API-token authentication for machine-to-machine use cases.

That is why Laravel MCP feels more relevant to me now than it did a week ago. The package is still specialized, but the specialization is easier to justify when serious SaaS products are converging on the same integration shape.

## Why this is bigger than "agents need API access"

I would not frame the enterprise value as "AI agents do not have APIs or CLIs."

They often do. The real issue is that many companies do not want to hand broad API credentials, raw CLI access, or fragile custom integrations to every agent host they experiment with.

MCP gives them another option: expose a governed set of actions and resources through one standard surface.

That is a much better enterprise pitch.

The good version of an MCP server is not "here is our whole REST API again." It is "here are the twelve things an AI client should be allowed to do, with sane auth, narrow scopes, and useful auditability."

That is also why official provider-hosted servers matter. [OpenAI's own guidance](https://platform.openai.com/docs/mcp/) is basically a warning against random third-party proxies. If MCP becomes a real enterprise layer, trust, governance, and identity are the product.

## Laravel's implementation still feels solid

This is where Laravel's implementation earns credit.

The first-run experience is short. The package scaffolds sensible classes. `routes/ai.php` is a good home for the registration layer. The docs already cover [authentication](https://laravel.com/docs/12.x/mcp#authentication), [authorization](https://laravel.com/docs/12.x/mcp#authorization), and the built-in [MCP Inspector workflow](https://laravel.com/docs/12.x/mcp#testing-servers).

Most importantly, Laravel does not sell MCP as a magic AI feature. It treats it as an app integration surface. I think that restraint is one of the best things about the package.

## My take

Laravel MCP feels polished already, and I trust its product framing more after looking at where MCP is heading.

I still would not install it by default.

If you are building internal AI features, the AI SDK is still the more obvious starting point. If you want better coding help, Boost is still the clearer answer.

But if you run a SaaS product and can see a future where customers want ChatGPT, Claude, Codex, or another MCP-capable client to interact with your product safely, Laravel MCP stops looking niche and starts looking timely.

That is my opinion in one line: Laravel MCP is still a specialist tool, but it now looks like a specialist tool for a market that is actually forming.

If you are trying to place Laravel MCP in the bigger framework picture, these are the next reads I would keep open:

- [See what Laravel 13 is preparing around AI and developer tooling](/laravel-13)
- [Get the broader context around Laravel 12 right now](/laravel-12)
- [See the new Laravel Cloud changes from this week too](/laravel-cloud-credit-card-git-connections)
- [Use the PHP OpenAI client when you just need direct API access](/openai-php-client)
