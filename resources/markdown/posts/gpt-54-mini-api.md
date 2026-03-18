---
id: "01KXQ2Z5Y7R8J6M1N4P9V3C8HD"
title: "GPT-5.4 mini API quick start with a real workflow"
slug: "gpt-54-mini-api"
author: "benjamincrozat"
description: "Learn GPT-5.4 mini step by step by sending your first API request and building a structured workflow for coding-friendly, high-volume tasks."
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
image_path: "images/posts/generated/gpt-54-mini-api.png"
sponsored_at: null
---
## What this GPT-5.4 mini guide covers

OpenAI released GPT-5.4 mini on March 17, 2026 in its [launch post](https://openai.com/index/introducing-gpt-5-4-mini-and-nano/). The current [GPT-5.4 mini model page](https://developers.openai.com/api/docs/models/gpt-5.4-mini) describes it as OpenAI's strongest mini model yet for coding, computer use, and subagents.

That makes GPT-5.4 mini the practical middle ground in the GPT-5.4 family. It is built for high-volume workloads, it brings the strengths of GPT-5.4 to a smaller model, and it improves noticeably over GPT-5 mini across coding, reasoning, multimodal understanding, and tool use. OpenAI also says it runs more than 2x faster than GPT-5 mini.

If you are starting fresh and want the flagship option first, read my [GPT-5.4 API guide](/gpt-54-api). If you only care about the cheapest branch, compare this with [GPT-5.4 nano](/gpt-5-nano-api).

By the end, you will have:

- a first successful GPT-5.4 mini Responses API call
- a structured support-triage workflow that returns strict JSON

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

## Send your first GPT-5.4 mini request

GPT-5.4 mini uses the same [Responses API](https://platform.openai.com/docs/api-reference/responses/create) shape as the rest of the GPT-5.4 family, so the first request stays simple.

This exact request is a good first check:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.4-mini-2026-03-17",
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

That gives you a quick way to verify the API key, endpoint, and model selection are all working.

The model page also lists the parts that matter for real apps:

- 400,000 context window
- 128,000 max output tokens
- text and image input
- structured outputs
- function calling
- web search, file search, image generation, code interpreter, hosted shell, apply patch, skills, computer use, MCP, and tool search

## Build something useful: support triage

Now let us turn the first successful call into something you could actually ship.

Imagine your app receives this support message:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

You want GPT-5.4 mini to do four things in one pass:

1. classify the issue
2. set a priority
3. decide whether a human should step in
4. draft a safe reply

That is a good fit for this model because the output is bounded, the prompt is precise, and the result is easy to validate.

## Return strict JSON with a schema

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.4-mini-2026-03-17",
    "instructions": "You triage support messages for a SaaS app. Be cautious. Do not promise actions the billing team has not confirmed. Keep reply_draft to 2 short sentences.",
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
    "text": {
      "verbosity": "low",
      "format": {
        "type": "json_schema",
        "name": "support_triage",
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
            "reply_draft": {
              "type": "string"
            }
          },
          "required": ["category", "priority", "needs_human", "reply_draft"],
          "additionalProperties": false
        },
        "strict": true
      }
    },
    "max_output_tokens": 220
  }'
```

That request should give you JSON shaped like this:

```json
{
  "category": "billing",
  "priority": "high",
  "needs_human": true,
  "reply_draft": "Sorry about the duplicate charge. I have flagged this to our billing team to review the transaction and follow up with you."
}
```

That is immediately useful in an app:

- `category` can route the ticket
- `priority` can change queue order
- `needs_human` can trigger escalation
- `reply_draft` can prefill the first response

If you want to move this pattern into PHP next, my guide on [using OpenAI's API in PHP with openai-php/client](/openai-php-client) picks up right there.

## What changes with GPT-5.4 mini

GPT-5.4 mini sits in a sweet spot.

The launch post says it is a significant upgrade over GPT-5 mini, and the model page backs that up with a clearer value proposition: a faster, more efficient model for high-volume workloads. The pricing page puts it at [\$0.75 input and \$4.50 output per 1M tokens](https://openai.com/api/pricing/), while the same comparison area shows GPT-5.4 at [\$2.50 input and \$15 output per 1M tokens](https://openai.com/api/pricing/). That makes GPT-5.4 mini much cheaper than the full model while still staying in the newer GPT-5.4 family.

So if your task is precise and repeatable, mini often makes more sense than the flagship model. If the work is even narrower and cost is the main constraint, [GPT-5.4 nano](/gpt-5-nano-api) is the next model I would compare.

## How I would use GPT-5.4 mini

I would reach for GPT-5.4 mini when:

- the prompt is clear
- the output shape is predictable
- the task needs to be fast and affordable
- the job is a coding assistant subtask, a computer-use step, or a structured classification workflow

I would not use it as a blanket default for messy reasoning. If the task is ambiguous, long, or coordination-heavy, full [GPT-5.4](/gpt-54-api) still has the advantage.

## Common mistakes with GPT-5.4 mini

### 1. Treating it like the flagship by default

Mini is excellent when the job is bounded. It is not the right starting point for every hard problem.

### 2. Using vague prompts

This model shines when you tell it exactly what to classify, extract, or decide.

### 3. Skipping the cheaper or larger sibling when the fit is obvious

If you only need very fast, very cheap classification, [GPT-5.4 nano](/gpt-5-nano-api) may be enough. If the task needs more breadth or judgment, [GPT-5.4](/gpt-54-api) is the better upgrade path.

If GPT-5.4 mini looks close to what you need, these are the next reads I would keep open:

- [See when the full GPT-5.4 model is still worth the extra cost](/gpt-54-api)
- [Compare GPT-5.4 mini with the cheaper GPT-5.4 nano](/gpt-5-nano-api)
- [Move this same structured-output pattern into PHP](/openai-php-client)
- [Build a better mental model for what GPT-style models are actually doing](/how-llms-work)
