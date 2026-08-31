---
id: "01M1CGSX1QAQHG5BWWKDAJHH74"
title: "The New Debug Bar for Laravel"
slug: "new-debug-bar-for-laravel"
author: "benjamincrozat"
description: "The New Debug Bar is my cleaner take on a familiar Laravel tool, built because I wanted a debugging interface I would enjoy using every day."
categories:
  - "laravel"
  - "packages"
  - "tools"
published_at: null
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: true
image_disk: null
image_path: null
sponsored_at: null
---
**The [New Debug Bar](https://newdebugbar.com) is my cleaner take on a familiar Laravel tool.** I did not build it to reinvent debugging. I built it because I was tired of using the old debug bar's dated interface every day.

## Why I built it

The old debug bar still did useful work, but its interface felt crowded and dated. Most of the feedback I received came back to the same point: developers liked having a debug bar, but wanted one that looked and felt better.

I first wanted to build the New Debug Bar about a year and a half ago. I knew what I wanted it to be, but I did not have enough time to give the project the attention it needed.

When I finally found the time, the goal was simple: build the Laravel debug bar I wanted to use every day. I wanted the useful information to stay, but inside an interface that was cleaner, easier to read, and easier to navigate.

## What it does

The small bar stays out of the way until I need it. It shows the main facts about the current request, then opens into a full inspector.

From there, I can inspect the route, controller, middleware, queries, timeline, logs, exceptions, models, views, Livewire activity, jobs, mail, events, cache, Redis, and more. I also try to keep the source of each event close by, so it is easier to find the code I need to check.

I use a benchmark app called Morrow while building the interface. It gives the New Debug Bar realistic data for every inspector and helps me keep the experience consistent.

<!-- QUERY_SCREENSHOT -->

## Built for developers and coding agents

The New Debug Bar also includes a local, read-only [MCP server](https://newdebugbar.com/docs/mcp). This lets a coding agent inspect the same saved requests as the browser interface.

Instead of guessing from a screenshot or searching through a large log file, the agent can read the exact request data and focus on the parts that matter.

## Still early

The New Debug Bar is not at version 1.0 yet, but it is ready to try. The [website and documentation](https://newdebugbar.com/docs) explain how to install it and cover the inspectors in more detail.

I want the project to stay free and open source. If you find it useful, you can [sponsor my work on GitHub](https://github.com/sponsors/benjamincrozat). You can also support the project by [hiring me as a freelance Laravel developer](mailto:hello@benjamincrozat.com).

If you try the New Debug Bar, I would be glad to hear what you think on [GitHub](https://github.com/newdebugbar/newdebugbar).
