---
id: "01M1CGSX1QAQHG5BWWKDAJHH74"
title: "The New Debug Bar for Laravel"
slug: "new-debug-bar-for-laravel"
author: "benjamincrozat"
description: "The New Debug Bar is my cleaner Laravel debugger, with a built-in MCP server that lets coding agents inspect the same saved requests as the browser interface."
categories:
  - "laravel"
  - "packages"
  - "tools"
published_at: 2026-08-31T19:31:50Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: true
image_disk: "cloudflare-images"
image_path: "images/posts/01M1CNE735HWA78KZM5GMWZFCD.png"
sponsored_at: null
---
**The [New Debug Bar](https://newdebugbar.com) is my cleaner take on a familiar Laravel tool.** I did not build it to reinvent debugging. I built it because I was tired of using the old debug bar's dated interface every day.

It is also built for the way I work now. I can inspect a request in the browser, while a coding agent can read the same saved request through the built-in, local, read-only [Model Context Protocol (MCP) server](https://newdebugbar.com/docs/mcp). The agent gets the exact context instead of guessing from a screenshot or searching through a large log file.

## Why I built it

The old debug bar still did useful work, but its interface felt crowded and dated. Most of the feedback I received came back to the same point: developers liked having a debug bar, but wanted one that looked and felt better.

I first wanted to build the New Debug Bar about a year and a half ago. I knew what I wanted it to be, but I did not have enough time to give the project the attention it needed.

When I finally found the time, the goal was simple: build the Laravel debug bar I wanted to use every day. I wanted the useful information to stay, but inside an interface that was cleaner, easier to read, and easier to navigate.

## What it does

The interface is not an afterthought. The compact bar feels sleek, shows the key facts, and opens into a full inspector. I can drag it to the top, bottom, or any corner of the screen, and switch between light and dark modes.

Inside, I have put real thought into what appears first, what stays out of the way, and how each detail leads back to the code behind it.

![The New Debug Bar query inspector showing a slow query and its source](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/01M1CMV135HCKAVJW0C9KFR6E5.png/public)

## Still early

The New Debug Bar is not at version 1.0 yet, but it is ready to try. The [website and documentation](https://newdebugbar.com/docs) explain how to install it and cover the inspectors in more detail.

I want the project to stay free and open source. If you find it useful, you can [sponsor my work on GitHub](https://github.com/sponsors/benjamincrozat). You can also support the project by [hiring me as a freelance Laravel developer](mailto:hello@benjamincrozat.com).

If you try the New Debug Bar, I would be glad to hear what you think on [GitHub](https://github.com/newdebugbar/newdebugbar).
