---
id: "01KKEW279VENBTCY9H2BN894RH"
title: "GPT-5.0 API quick start with a real workflow"
slug: "gpt-5-api"
author: "benjamincrozat"
description: "Learn GPT-5.0 step by step by sending your first API request and building a support triage workflow with structured JSON."
categories:
  - "ai"
  - "gpt"
published_at: 2025-08-06T15:45:00+02:00
modified_at: 2026-03-14T12:22:18Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K27AZ6VMTJY7M21YWACDXTV2.png"
sponsored_at: null
---
## What this GPT-5.0 guide covers

OpenAI introduced GPT-5 for developers on August 7, 2025 in its [GPT-5 for developers announcement](https://openai.com/index/introducing-gpt-5-for-developers/). This page is about that original GPT-5.0 release, and every example below uses the pinned snapshot `gpt-5-2025-08-07` so the tutorial stays tied to the model that launched that day.

That matters because, as of March 14, 2026, OpenAI's current [GPT-5 model docs](https://developers.openai.com/api/docs/models/gpt-5) treat GPT-5 as a previous model rather than the default recommendation. So this is not a guide to the newest GPT-5-family model. It is a practical walkthrough for the original full GPT-5.0 model on purpose.

By the end, you will have two things working:

- a first successful call to the Responses API
- a small support-triage workflow that returns strict JSON you can use in an app

If you are new to large language models, start with my plain-English explainer on [how GPT-style LLMs work](/gpt-llm-ai-explanation). It will make the rest of this guide much easier to follow.

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

## Send your first GPT-5.0 request

OpenAI's [Responses API](https://platform.openai.com/docs/api-reference/responses/create) is the cleanest place to start today. Here is the smallest useful request I would use for GPT-5.0:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5-2025-08-07",
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

If that request works, your key, billing, and endpoint setup are all fine.

Three details are worth noticing right away:

- The model is pinned to `gpt-5-2025-08-07`, not the moving `gpt-5` alias.
- GPT-5.0 reasoning now lives under `reasoning.effort`.
- Verbosity now lives under `text.verbosity`.

Those nested fields are easy to miss if you learned GPT-5 from older launch-week examples.

## Build something useful: support triage

Now let us turn that first successful call into something you can actually ship.

Imagine your app receives this support message:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

You want GPT-5.0 to do four things in one pass:

1. classify the issue
2. set a priority
3. decide whether a human should step in
4. draft a safe reply

This is a great first production-style workflow because the output is short, structured, and easy to validate.

## Return strict JSON with a schema

This example asks GPT-5.0 to return JSON that matches a schema exactly.

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5-2025-08-07",
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

You should get back JSON shaped roughly like this:

```json
{
  "category": "billing",
  "priority": "high",
  "needs_human": true,
  "reply_draft": "Thanks for letting us know about the duplicate charge - sorry for the hassle. I've escalated this to our billing team to review and will update you as soon as we have an outcome."
}
```

That result is immediately useful in a real application:

- `category` can route the ticket
- `priority` can change queue order
- `needs_human` can trigger escalation
- `reply_draft` can prefill a support response

This is the point where the API becomes much more valuable than a simple chatbot. You are no longer parsing prose and hoping it stays consistent.

## Why this JSON pattern works well

There are three reasons I would start here with GPT-5.0:

- The task is realistic enough to matter, but still easy to debug.
- The schema keeps the output predictable.
- The prompt teaches a habit that scales well: tell the model what role it plays, what output shape you need, and what it must avoid.

If you later move this workflow into PHP, my guide on [using OpenAI's API in PHP with openai-php/client](/openai-php-client) picks up right where this one stops.

## Tune reasoning effort and verbosity on purpose

Two GPT-5.0 controls matter a lot in practice.

### `reasoning.effort`

This controls how much work the model does before answering.

The example above uses `minimal` on purpose so it stays fast and comfortably fits the token budget shown in the tutorial.

For more open-ended versions of this workflow, I would use:

- `minimal` for simple classification or extraction
- `low` when the model has to weigh a few risks before replying
- `medium` only if the workflow becomes more ambiguous or policy-heavy

`high` is usually overkill for a first structured workflow like this.

### `text.verbosity`

This controls how compact or expansive the answer should be.

For structured app workflows:

- use `low` when you want terse fields and minimal explanation
- use `medium` when one field, such as `reply_draft`, should sound natural
- avoid `high` unless your output genuinely benefits from longer prose

In other words, `reasoning.effort` changes how hard the model thinks, while `text.verbosity` changes how much it says.

## Common mistakes that break GPT-5.0 requests

These are the mistakes I would check first if your request fails or behaves strangely.

### 1. Using the wrong model name

If this article is about GPT-5.0 specifically, use `gpt-5-2025-08-07`.

If you switch to the `gpt-5` alias, you are no longer guaranteed to hit the original GPT-5.0 release.

### 2. Using the old top-level GPT-5 fields

Older examples often show:

```json
{
  "verbosity": "medium",
  "reasoning_effort": "minimal"
}
```

For current Responses API requests, the safer shape is:

```json
{
  "reasoning": {
    "effort": "minimal"
  },
  "text": {
    "verbosity": "medium"
  }
}
```

### 3. Mixing Responses API and Chat Completions fields

For the Responses API:

- use `max_output_tokens`
- use `text.format` for JSON schema output

For Chat Completions:

- use `max_completion_tokens`
- use `response_format`

Mixing those field names is a fast way to get an error.

### 4. Forgetting billing or key scope

If you get authentication or quota errors, check the boring stuff first:

- the key is real and active
- billing is enabled
- the environment variable is set in the terminal you are actually using

## When full GPT-5.0 is worth using

Use full GPT-5.0 when answer quality matters more than keeping cost or latency as low as possible.

That usually means tasks like:

- code-heavy assistance
- longer reasoning chains
- app workflows where the output shape matters and mistakes are expensive
- prompts that need more context than a tiny request

If your task is simple classification, lightweight extraction, or massive volume at low cost, GPT-5.0 is probably more model than you need. But for a first serious walkthrough, the full model is a good place to learn the API shape and prompting style that the rest of the GPT-5 family builds on.

Before you lock any production budget, check OpenAI's current [pricing page](https://openai.com/api/pricing/) and the current [GPT-5 model page](https://developers.openai.com/api/docs/models/gpt-5), because pricing and recommendations can change after the original release.

## Conclusion

If you only copy one thing from this page, copy the pattern:

1. pin the model when version specificity matters
2. use the Responses API
3. return strict JSON for app workflows
4. tune `reasoning.effort` and `text.verbosity` on purpose

That is enough to move from "I made my first model call" to "I built something I can wire into a product."

Once this request is working, the next bottleneck is usually prompting, integration, or choosing where GPT-5.0 actually fits in your stack:

- [Move the same workflow into PHP with less boilerplate](/openai-php-client)
- [Build a better mental model for what GPT-style models are actually doing](/gpt-llm-ai-explanation)
- [Compare this with a different OpenAI model before you optimize for cost](/gpt-4o-mini)
- [See how GPT-4.1 changed the trade-off between model quality and context length](/gpt-41)
