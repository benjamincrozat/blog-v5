---
id: "01KQ9Z4JH8V3N6Q2M5T7R1P4C8"
title: "GPT-5.4 nano API quick start with a real workflow"
slug: "gpt-54-nano-api"
author: "benjamincrozat"
description: "Learn GPT-5.4 nano step by step by sending your first API request and building a fast routing workflow for high-volume tasks."
categories:
  - "ai"
  - "gpt"
published_at: 2026-03-18T00:00:00Z
modified_at: 2026-03-18T00:00:00Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/gpt-54-nano-api.png"
sponsored_at: null
---
## What this GPT-5.4 nano guide covers

OpenAI introduced the GPT-5.4 family on March 5, 2026 in its [GPT-5.4 launch post](https://openai.com/index/introducing-gpt-5-4/). The current [GPT-5.4 nano model page](https://developers.openai.com/api/docs/models/gpt-5.4-nano) places nano in that family, and the current [pricing page](https://openai.com/api/pricing/) puts it at the low end of that lineup.

That makes GPT-5.4 nano the model I would reach for when the job is small, repetitive, and high-volume: classification, extraction, ranking, and lightweight routing to sub-agents.

This guide uses the pinned snapshot `gpt-5.4-nano-2026-03-17`.

By the end, you will have:

- a first successful GPT-5.4 nano Responses API call
- a strict JSON routing workflow that can send work to the right sub-agent

If you want the fuller GPT-5.4 picture first, start with my [GPT-5.4 API guide](/gpt-54-api). If you are coming from the older family, compare this with [GPT-5 nano](/gpt-5-nano-api).

## Get your API key ready

You need an OpenAI account, a funded API project, and an API key from the [API keys page](https://platform.openai.com/api-keys). Keys are shown once, so save yours right away.

Then export it in your terminal.

macOS and Linux:

```bash
export OPENAI_API_KEY="sk-..."
```

Windows Command Prompt:

```cmd
setx OPENAI_API_KEY "sk-..."
```

If you use `setx`, open a new terminal before testing the key.

## Send your first GPT-5.4 nano request

GPT-5.4 nano uses the same Responses API shape as the rest of the GPT-5.4 family, so the first request stays simple:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.4-nano-2026-03-17",
    "input": [
      {
        "role": "user",
        "content": [
          { "type": "input_text", "text": "Say hello in one short sentence." }
        ]
      }
    ],
    "text": {
      "verbosity": "low"
    },
    "max_output_tokens": 80
  }'
```

If your key and billing are set up correctly, you should get a short greeting back.

## Build something useful: route work to sub-agents

Nano is not where I would start for a long, nuanced response draft. It is where I would start for short, repeatable jobs that need a clean handoff.

Imagine your app receives this instruction:

```text
Check the pricing copy, fix the headline if needed, and flag anything risky.
```

You want GPT-5.4 nano to:

1. classify the request
2. choose the right sub-agent
3. set a priority
4. return a short handoff note

That is a very good fit for nano because the output is narrow, structured, and easy to validate.

## Return strict JSON with a schema

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.4-nano-2026-03-17",
    "instructions": "You route small tasks to the right sub-agent for a SaaS app. Be cautious. Do not promise changes that were not requested. Keep handoff_note to 2 short sentences.",
    "input": [
      {
        "role": "user",
        "content": [
          {
            "type": "input_text",
            "text": "Check the pricing copy, fix the headline if needed, and flag anything risky."
          }
        ]
      }
    ],
    "text": {
      "verbosity": "low",
      "format": {
        "type": "json_schema",
        "name": "sub_agent_routing",
        "schema": {
          "type": "object",
          "properties": {
            "destination_agent": {
              "type": "string",
              "enum": ["copy_agent", "seo_agent", "legal_agent", "general_agent"]
            },
            "priority": {
              "type": "string",
              "enum": ["low", "medium", "high"]
            },
            "needs_human": {
              "type": "boolean"
            },
            "handoff_note": {
              "type": "string"
            }
          },
          "required": ["destination_agent", "priority", "needs_human", "handoff_note"],
          "additionalProperties": false
        },
        "strict": true
      }
    },
    "max_output_tokens": 200
  }'
```

That kind of output is easy to plug into a dispatcher:

- `destination_agent` picks the next sub-agent
- `priority` changes queue order
- `needs_human` can stop unsafe automation
- `handoff_note` gives the next model a clean starting point

If you want to move the same pattern into PHP afterward, my guide on [using OpenAI's API in PHP with openai-php/client](/openai-php-client) picks up right there.

## Why GPT-5.4 nano is different

GPT-5.4 nano is not just a smaller model. It is the one I would choose when cost and throughput matter more than broad reasoning.

OpenAI's pricing page puts it at the low end of the GPT-5.4 family, which is why it makes sense for:

- classification
- data extraction
- ranking
- routing to sub-agents
- preprocessing before a stronger model sees the hard cases

That also makes it a natural step up from the older [GPT-5 nano guide](/gpt-5-nano-api) when you want the newer GPT-5.4 family without jumping all the way to full GPT-5.4.

## When GPT-5.4 nano is the right model

Pick GPT-5.4 nano when you care about:

- very high request volume
- low latency
- low cost
- strict structured output
- lightweight routing before a bigger model or a human steps in

If the task starts needing broader judgment, [GPT-5.4](/gpt-54-api) is the more capable next stop. If the task stays simple but you want the older family, [GPT-5 nano](/gpt-5-nano-api) is still a useful comparison point.

## Common mistakes with GPT-5.4 nano

### 1. Expecting it to behave like the flagship model

Nano is great at small, repeatable tasks. It is not where I would start for open-ended analysis.

### 2. Using long reply-drafting workflows by default

Nano works best when the output is short and predictable.

### 3. Forgetting to route hard cases onward

Nano is strongest when it filters, labels, or dispatches. It does not need to do everything itself.

If GPT-5.4 nano looks close to what you need, these are the next reads I would keep open:

- [See the full GPT-5.4 API quick start for the flagship family](/gpt-54-api)
- [Compare it with the older GPT-5 nano quick start](/gpt-5-nano-api)
- [Step up to GPT-5.4 mini when you want a broader low-cost model](/gpt-54-mini-api)
- [Move this same structured-output pattern into PHP](/openai-php-client)
