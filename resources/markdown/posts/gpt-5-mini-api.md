---
id: "01KKP6HSFT8WGGN7GJJYYBHFDZ"
title: "GPT-5 mini API quick start with a real workflow"
slug: "gpt-5-mini-api"
author: "benjamincrozat"
description: "Learn GPT-5 mini step by step by sending your first API request and building a structured support workflow at a lower cost than full GPT-5."
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
image_path: "images/posts/generated/gpt-5-mini-api.png"
sponsored_at: null
---
## What this GPT-5 mini guide covers

If you want a cheaper GPT-5 model without dropping back to an older family, GPT-5 mini is the obvious place to start.

The current [GPT-5 mini model page](https://developers.openai.com/api/docs/models/gpt-5-mini) describes it as a faster, more cost-efficient version of GPT-5 for well-defined tasks and precise prompts. The broader [models guide](https://developers.openai.com/api/docs/models) also points developers toward GPT-5 mini when lower latency and lower cost matter more than frontier-level reasoning.

This guide is about the original GPT-5 mini API snapshot, `gpt-5-mini-2025-08-07`.

If you are starting a new project and mostly want the latest flagship quality, start with GPT-5.4 from the current [models guide](https://developers.openai.com/api/docs/models). Reach for GPT-5 mini when lower cost and lower latency are the real reason you are choosing it.

By the end, you will have:

- a first successful GPT-5 mini Responses API call
- a structured support-triage workflow that returns strict JSON

If you want the bigger-picture model first, read my [GPT-5.0 API guide](/gpt-5-api). If you want the absolute cheapest GPT-5 variant after this one, open [GPT-5 nano](/gpt-5-nano-api) next.

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

## Send your first GPT-5 mini request

GPT-5 mini uses the same [Responses API](https://platform.openai.com/docs/api-reference/responses/create) shape as full GPT-5, but it does **not** support `reasoning.effort: "none"`. In my live test, the API only accepted `minimal`, `low`, `medium`, or `high`.

This first request worked for me:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5-mini-2025-08-07",
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

Three details matter:

- the model is pinned to `gpt-5-mini-2025-08-07`
- `reasoning.effort` is set to `minimal`
- `text.verbosity` stays under `text`

## Build something useful: support triage

GPT-5 mini is a good fit for smaller workflows where the prompt is precise and the output shape matters more than deep open-ended reasoning.

Imagine your app receives this message:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

You want GPT-5 mini to:

1. classify the issue
2. set a priority
3. decide whether a human should step in
4. draft a safe reply

That is the kind of workflow where mini makes sense. It is clear, bounded, and easy to validate.

## Return strict JSON with a schema

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5-mini-2025-08-07",
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
    "reasoning": {
      "effort": "minimal"
    },
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

That exact request completed successfully for me and returned JSON in this shape:

```json
{
  "category": "billing",
  "priority": "high",
  "needs_human": true,
  "reply_draft": "Sorry about the double charge - I have flagged this to our billing team to investigate. Please reply with the transaction IDs or a screenshot of the two charges so we can resolve this faster."
}
```

That is immediately useful in an app:

- `category` can route the ticket
- `priority` can change queue order
- `needs_human` can trigger escalation
- `reply_draft` can prefill the first response

If you want to move this pattern into PHP next, my guide on [using OpenAI's API in PHP with openai-php/client](/openai-php-client) picks up right there.

## What changes with GPT-5 mini

The big change is not a new shiny parameter. It is the trade-off.

GPT-5 mini keeps the GPT-5-style API surface, but it is tuned for:

- lower latency
- lower cost
- well-defined tasks
- precise prompts

The current pricing page lists GPT-5 mini at [\$0.25 input and \$2.00 output per 1M tokens](https://openai.com/api/pricing/), which is much cheaper than full GPT-5. The current model page lists a 400,000-token context window, which gives mini a lot of room even though it is the cheaper tier.

So if your task is structured and repeatable, mini often makes more sense than the full model.

## How I would choose reasoning effort on GPT-5 mini

For GPT-5 mini, I would use:

- `minimal` for extraction, classification, and routing
- `low` when the task needs a bit more judgment
- `medium` only when mini is struggling and you still want to stay on this cheaper model
- `high` sparingly

The key point is that `none` is not available here, unlike on later GPT-5.1+ frontier models.

## When GPT-5 mini is a better choice than full GPT-5

Pick GPT-5 mini when:

- the prompt is precise
- the output schema is simple
- you need lower cost
- you expect higher request volume

Do **not** assume mini is always the right default, though. If the task is more ambiguous, coding-heavy, or tradeoff-heavy, full GPT-5 or a newer model like GPT-5.4 may still pay for itself in answer quality.

## Common mistakes with GPT-5 mini

### 1. Assuming it supports `reasoning.effort: "none"`

It does not. In my live test, the API rejected `none` and only accepted `minimal`, `low`, `medium`, or `high`.

### 2. Using mini for vague prompts

Mini works best when the task is tightly specified. If the prompt is fuzzy, the lower cost will not save you from lower-quality outcomes.

### 3. Forgetting that Nano exists

If your workload is even simpler than this one, such as fast classification or summarization at very high volume, [GPT-5 nano](/gpt-5-nano-api) may be the better fit.

If GPT-5 mini looks close to what you need, these are the next reads I would keep open:

- [See when the full GPT-5 model is still worth the extra cost](/gpt-5-api)
- [Compare GPT-5 mini with the even cheaper GPT-5 nano](/gpt-5-nano-api)
- [Move this same structured-output workflow into PHP](/openai-php-client)
- [Build a better mental model for what GPT-style models are actually doing](/how-llms-work)
