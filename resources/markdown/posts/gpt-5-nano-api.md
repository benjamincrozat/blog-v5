---
id: "01KKP6HSFT8WGGN7GJJYYBHFE0"
title: "GPT-5 nano API quick start with a real workflow"
slug: "gpt-5-nano-api"
author: "benjamincrozat"
description: "Learn GPT-5 nano step by step by sending your first API request and building a fast classification workflow for high-volume tasks."
categories:
  - "ai"
  - "gpt"
published_at: 2026-03-14T12:53:43+00:00
modified_at: 2026-03-14T13:10:13+00:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/gpt-5-nano-api.png"
sponsored_at: null
---
## What this GPT-5 nano guide covers

If your main goal is ultra-low cost and ultra-fast responses, GPT-5 nano is the GPT-5 variant to look at first.

The current [GPT-5 nano model page](https://developers.openai.com/api/docs/models/gpt-5-nano) describes it as the fastest, cheapest version of GPT-5, especially suited to summarization and classification tasks. That matches the live API behavior I saw too: it works well when you keep the job small and explicit.

This guide is about the original GPT-5 nano API snapshot, `gpt-5-nano-2025-08-07`.

If you are starting fresh and mostly want the newest flagship model, look at GPT-5.4 in the current [models guide](https://developers.openai.com/api/docs/models) first. Nano is the deliberate choice when cost, speed, and very high volume matter more than broad reasoning quality.

By the end, you will have:

- a first successful GPT-5 nano Responses API call
- a compact ticket-classification workflow that returns strict JSON

If you need a broader low-cost model rather than the absolute cheapest one, compare this with [GPT-5 mini](/gpt-5-mini-api). If you want the full flagship context first, read [GPT-5.0](/gpt-5-api).

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

## Send your first GPT-5 nano request

GPT-5 nano uses the same Responses API structure as mini and full GPT-5. Like mini, it does **not** support `reasoning.effort: "none"`, so `minimal` is the best place to start.

This exact request worked for me:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5-nano-2025-08-07",
    "input": [
      {
        "role": "user",
        "content": [
          { "type": "input_text", "text": "Say hello in one short sentence." }
        ]
      }
    ],
    "reasoning": {
      "effort": "minimal"
    },
    "text": {
      "verbosity": "low"
    },
    "max_output_tokens": 80
  }'
```

That exact request completed successfully for me and returned `Hello!`.

## Build something useful: ticket classification

Nano is not where I would start for a long nuanced reply draft. It is where I would start for short, repetitive, high-volume jobs.

So instead of support triage with a long response, let us use a cleaner nano-style workflow: classify a ticket and summarize it in one sentence.

Incoming message:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

Goal:

1. classify the issue
2. set a priority
3. decide whether a human is needed
4. summarize the problem in one short sentence

## Return strict JSON with a schema

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5-nano-2025-08-07",
    "instructions": "You classify short support messages for a SaaS app. Keep summary to one short sentence.",
    "input": [
      {
        "role": "user",
        "content": [
          {
            "type": "input_text",
            "text": "Hi, I was billed twice for my Pro plan today. Please refund the extra charge."
          }
        ]
      }
    ],
    "reasoning": {
      "effort": "minimal"
    },
    "text": {
      "verbosity": "low",
      "format": {
        "type": "json_schema",
        "name": "ticket_classification",
        "schema": {
          "type": "object",
          "properties": {
            "category": {
              "type": "string",
              "enum": ["billing", "bug", "account", "feature_request", "other"]
            },
            "priority": {
              "type": "string",
              "enum": ["low", "medium", "high"]
            },
            "needs_human": {
              "type": "boolean"
            },
            "summary": {
              "type": "string"
            }
          },
          "required": ["category", "priority", "needs_human", "summary"],
          "additionalProperties": false
        },
        "strict": true
      }
    },
    "max_output_tokens": 180
  }'
```

That exact request completed successfully for me and returned JSON in this shape:

```json
{
  "category": "billing",
  "priority": "high",
  "needs_human": false,
  "summary": "User was billed twice and requests a refund."
}
```

That kind of output is perfect for:

- queue routing
- analytics
- dashboards
- tagging
- cheap preprocessing before a bigger model sees the ticket

## Why GPT-5 nano is different

GPT-5 nano is not just "mini, but smaller." It is the variant you pick when throughput and cost matter most.

The current pricing page lists GPT-5 nano at [\$0.05 input and \$0.40 output per 1M tokens](https://openai.com/api/pricing/), which makes it dramatically cheaper than full GPT-5 and still much cheaper than mini. The current model page lists a 400,000-token context window, which is generous for a model mainly used for narrow high-volume tasks.

That means nano can still handle modern GPT-5-style requests. It is just best used for narrower jobs.

## How I would choose reasoning effort on GPT-5 nano

For nano, I would start here:

- `minimal` for almost everything
- `low` only if the task needs a little more judgment
- `medium` or `high` rarely

Like mini, nano does **not** support `reasoning.effort: "none"`. In my live test, the API only accepted `minimal`, `low`, `medium`, or `high`.

## When GPT-5 nano is the right model

Pick GPT-5 nano when you care about:

- very high request volume
- low latency
- very low cost
- simple classification and summarization
- preprocessing before handing work to a stronger model

That last pattern is especially useful. Nano can label, summarize, or filter incoming data cheaply, and then you can send only the harder cases to [GPT-5 mini](/gpt-5-mini-api) or full [GPT-5.0](/gpt-5-api).

## Common mistakes with GPT-5 nano

### 1. Expecting it to behave like a flagship model

Nano is great at short, repetitive tasks. It is not where I would start for messy, open-ended reasoning.

### 2. Using long reply-drafting workflows by default

Nano works better when the output is short and structured.

### 3. Forgetting that mini is often the better "cheap but still flexible" choice

If your task goes beyond classification, tagging, and summarization, [GPT-5 mini](/gpt-5-mini-api) is often the better compromise.

If GPT-5 nano looks close to what you need, these are the next reads I would keep open:

- [Step up to GPT-5 mini when you need a cheaper model with more flexibility](/gpt-5-mini-api)
- [See when the full GPT-5 model is still worth the extra cost](/gpt-5-api)
- [Move this same structured-output workflow into PHP](/openai-php-client)
- [Get a plain-English explanation of how GPT-style models work](/how-llms-work)
