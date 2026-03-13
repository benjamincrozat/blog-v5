---
id: "01KKK2YPX50X3HW6HY20ERM4EW"
title: "Laravel MCP feels polished, but most apps still do not need it"
slug: "laravel-mcp-review"
author: "benjamincrozat"
description: "Laravel MCP already feels polished, but it still makes sense only when external AI clients need to interact with your app."
categories:
  - "laravel"
  - "news"
published_at: 2026-03-13T07:53:09Z
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
Laravel's official [MCP documentation](https://laravel.com/docs/12.x/mcp), [Laravel MCP product page](https://laravel.com/ai/mcp), and last week's ["AI SDK, Boost, or MCP: Which Tool Do You Need?"](https://laravel.com/blog/laravel-ai-sdk-boost-or-mcp-which-tool-do-you-need) article all point in the same direction: Laravel MCP is real, polished, and useful, but it is not the first AI package most Laravel teams need.

I think that is the right framing.

The core workflow already feels smooth: install the package, publish the AI routes, generate a server and a tool, register the route, and open the built-in MCP Inspector.

My opinion after doing that: Laravel MCP already feels well designed. But unless you specifically want external AI clients to call into your application, you probably do not need it yet.

## What Laravel MCP is actually for

Laravel's own summary from the [March 4 article](https://laravel.com/blog/laravel-ai-sdk-boost-or-mcp-which-tool-do-you-need) is the cleanest one I have seen:

- the AI SDK helps you build AI features into your app
- Boost helps AI agents write better Laravel code for you
- MCP helps external AI tools interact with your app

That last point is the important one.

If your goal is "I want to add chat, agents, summaries, embeddings, or AI workflows inside my product," Laravel itself is telling you to look at the AI SDK first, not MCP.

If your goal is "I want Claude Code or another coding agent to understand my Laravel project better," that is a Boost problem, not an MCP problem.

Laravel MCP becomes the right tool when your app should expose tools, resources, or prompts to an external AI client through the Model Context Protocol.

## What I tested

Installation was exactly what the docs promise:

```bash
composer require laravel/mcp
php artisan vendor:publish --tag=ai-routes
php artisan make:mcp-server DemoServer
php artisan make:mcp-tool DemoTool
```

That gave me a `routes/ai.php` file plus generated classes under `app/Mcp/Servers` and `app/Mcp/Tools`.

Registering a web-exposed MCP route was also straightforward:

```php
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp/demo', \App\Mcp\Servers\DemoServer::class);
```

Then I wired the generated tool into the generated server:

```php
protected array $tools = [
    DemoTool::class,
];
```

From there, `php artisan route:list --path=mcp` showed the route immediately, and `php artisan mcp:inspector /mcp/demo` launched the built-in inspector flow against that endpoint without any weird setup work.

That matters. New protocol tooling often falls apart in the first ten minutes. Laravel MCP did not.

## What feels good already

The biggest strength is that Laravel MCP feels like Laravel, not like a protocol wrapper awkwardly pasted into a Laravel app.

The generated classes are simple. The route registration is readable. The docs cover servers, tools, resources, prompts, metadata, authentication, authorization, and testing in a way that feels consistent with the rest of the framework.

A few specific things stood out to me:

- the scaffolding commands are clear and useful
- `routes/ai.php` is a sensible place for MCP registration
- the docs already cover both [OAuth 2.1 and Sanctum](https://laravel.com/docs/12.x/mcp#authentication)
- the built-in [MCP Inspector workflow](https://laravel.com/docs/12.x/mcp#testing-servers) makes early testing much easier

I also like that Laravel does not oversell it in the docs. The package is presented as a clean way to expose MCP capabilities, not as a magic AI layer that replaces normal application design.

## Why most Laravel teams still do not need it

This is where the hype around MCP can confuse people.

Because MCP is currently a hot term, it is easy to think every AI-flavored Laravel app should install it. I do not think that is true, and Laravel's own March 4 post basically says the same thing.

Most teams are in one of these buckets:

- they want AI features inside their app
- they want AI help while writing Laravel code
- they are just exploring AI without a clear external-client use case yet

In those cases, MCP is usually not the first thing to reach for.

The moment Laravel MCP becomes compelling is when your app itself should act like a well-structured AI-accessible surface. For example: exposing internal business actions as tools, sharing application data as MCP resources, or letting trusted AI clients trigger workflows in a controlled way.

That is real. It is just narrower than "I am doing AI stuff in Laravel."

## My take

Laravel MCP is better than I expected this early.

Not because it is huge, but because the developer experience already feels coherent. The install path is short, the primitives make sense, and the inspector closes the loop quickly enough that you can tell whether your server is wired correctly without fighting the protocol.

But I would still treat it as a specialist tool for now.

If you have a genuine MCP use case, Laravel has made that path much easier than it would have been six months ago. If you do not, I would not install it just because "MCP" is everywhere right now.

That is probably the best compliment I can give this package: Laravel seems to understand exactly what it is for, and the package mostly stays inside that boundary.

If you are trying to place Laravel MCP in the bigger framework picture, these are the next reads I would keep open:

- [See what Laravel 13 is preparing around AI and developer tooling](/laravel-13)
- [Get the broader context around Laravel 12 right now](/laravel-12)
- [See the new Laravel Cloud changes from this week too](/laravel-cloud-credit-card-git-connections)
- [Use the PHP OpenAI client when you just need direct API access](/openai-php-client)
