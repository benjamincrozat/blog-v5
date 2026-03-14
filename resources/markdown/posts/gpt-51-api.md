---
id: "01KKP5J4FEWD8TQP8QEBKFRCP6"
title: "GPT-5.1 API quick start with a real workflow"
slug: "gpt-51-api"
author: "benjamincrozat"
description: "Learn GPT-5.1 step by step by sending your first API request and building a support triage workflow with structured JSON."
categories:
  - "ai"
  - "gpt"
published_at: 2026-03-14T12:36:21Z
modified_at: 2026-03-14T12:36:21Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
## What this GPT-5.1 guide covers

OpenAI released GPT-5.1 for developers on November 13, 2025 in its [GPT-5.1 for developers announcement](https://openai.com/index/gpt-5-1-for-developers/). The current [GPT-5.1 model page](https://developers.openai.com/api/docs/models/gpt-5.1) positions it as the best GPT-5-era model for coding and agentic tasks before GPT-5.4 took over as the default recommendation.

This guide is about the full GPT-5.1 model, not the chat or codex variants. Every runnable example below uses the pinned snapshot `gpt-5.1-2025-11-13` so the behavior stays tied to the original API release.

By the end, you will have two things working:

- a first successful GPT-5.1 Responses API call
- a small support-triage workflow that returns strict JSON

If you want the original GPT-5.0 walkthrough first, open my [GPT-5.0 API guide](/gpt-5-api). If you are new to large language models in general, read [how GPT-style LLMs work](/gpt-llm-ai-explanation) before you go further.

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

## Send your first GPT-5.1 request

OpenAI's [Responses API](https://platform.openai.com/docs/api-reference/responses/create) is the cleanest place to start. GPT-5.1's most useful change for normal app work is that `reasoning.effort` now supports `none`, and that is the default on the model page as well as the best starting point for low-latency tasks.

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.1-2025-11-13",
    "input": [
      {
        "role": "user",
        "content": [
          { "type": "input_text", "text": "Say hello in one short sentence." }
        ]
      }
    ],
    "reasoning": {
      "effort": "none"
    },
    "text": {
      "verbosity": "low"
    },
    "max_output_tokens": 80
  }'
```

That exact request completed successfully in my own test with `Hello!`.

The important detail is not the greeting. It is the shape:

- `reasoning.effort` is set to `none`
- `text.verbosity` stays under `text`
- the model is pinned to `gpt-5.1-2025-11-13`

If you learned GPT-5 from older August 2025 examples, this is the first place where the API feels meaningfully different.

## Build something useful: support triage

Let us turn that first successful request into something you could actually use in an app.

Imagine your product receives this message:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

You want GPT-5.1 to do four things in one pass:

1. classify the issue
2. set a priority
3. decide whether a human should step in
4. draft a safe reply

This is a strong starter workflow because it stays small, structured, and easy to validate before you wire it into queues or automations.

## Return strict JSON with a schema

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.1-2025-11-13",
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
      "effort": "none"
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

This completed successfully for me and returned JSON in this shape:

```json
{
  "category": "billing",
  "priority": "high",
  "needs_human": true,
  "reply_draft": "It looks like there may have been a duplicate charge on your Pro plan today, and our billing team will need to review this. Please share the last 4 digits of the card charged and any relevant invoice or transaction IDs so we can investigate and follow up with you."
}
```

That is immediately useful in real code:

- `category` can route the ticket
- `priority` can influence queue order
- `needs_human` can trigger escalation
- `reply_draft` can prefill the first answer

If you want to move this exact pattern into PHP, my guide on [using OpenAI's API in PHP with openai-php/client](/openai-php-client) is the next step.

## GPT-5.1's new practical parameter: `prompt_cache_retention`

The most useful new parameter in GPT-5.1 is not flashy. It is `prompt_cache_retention`.

OpenAI introduced extended prompt caching in the GPT-5.1 launch post, with support for keeping prompts cached for up to 24 hours. That matters when you have repeated follow-up requests over the same long context, like coding sessions, help-desk threads, or knowledge-heavy chat.

Here is the smallest working example:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.1-2025-11-13",
    "input": [
      {
        "role": "user",
        "content": [
          { "type": "input_text", "text": "Reply with OK only." }
        ]
      }
    ],
    "reasoning": {
      "effort": "none"
    },
    "text": {
      "verbosity": "low"
    },
    "prompt_cache_retention": "24h",
    "max_output_tokens": 20
  }'
```

That exact request worked in my test and came back with `OK`.

Use this when the same long prompt or context is likely to be reused later in the day. Skip it for throwaway one-off requests.

## When to use `none`, `low`, `medium`, or `high`

GPT-5.1 is the first GPT-5 mainline model where I would actively recommend `none` as the default for ordinary app work.

Here is the quick rule:

- use `none` for extraction, classification, formatting, and most simple app logic
- use `low` when the task has a few judgment calls
- use `medium` when the task is ambiguous and the answer quality matters more than latency
- use `high` when the model really needs to work through tradeoffs

That is the biggest practical difference between GPT-5.0 and GPT-5.1. GPT-5.1 gives you a better low-latency floor without dropping back to a different model family.

## Common mistakes with GPT-5.1

### 1. Forgetting that `none` exists

If you copy an older GPT-5 prompt and leave it at `minimal` or `low` by habit, you may be paying more latency than you need.

### 2. Using the moving alias when you want reproducibility

For tutorials, tests, and regression checks, prefer `gpt-5.1-2025-11-13` over `gpt-5.1`.

### 3. Mixing Responses API and Chat Completions fields

For the Responses API:

- use `max_output_tokens`
- use `text.format` for JSON schema output

For Chat Completions:

- use `max_completion_tokens`
- use `response_format`

## When full GPT-5.1 is worth using

Use GPT-5.1 when you want GPT-5-class quality, but you also care about speed and token efficiency on everyday coding and agentic tasks.

The current model page lists a 400,000-token context window, 128,000 max output tokens, and GPT-5-level pricing at $1.25 input and $10 output per 1M tokens. That makes GPT-5.1 a cleaner default than GPT-5.0 for many practical apps.

If your work is more professional-knowledge-heavy than coding-heavy, GPT-5.2 or GPT-5.4 may be a better fit. But for coding assistants, multi-step app workflows, and lower-latency tool use, GPT-5.1 is still a very sensible target.

If this guide helped you get a working request but you are still deciding where GPT-5.1 fits, these are the next reads I would keep open:

- [See how the original GPT-5.0 API guide differs from GPT-5.1](/gpt-5-api)
- [Move this same workflow into PHP with less boilerplate](/openai-php-client)
- [Compare GPT-5.1 with the more professional-work-focused GPT-5.2](/gpt-52-api)
- [Build a better mental model for what GPT-style models are actually doing](/gpt-llm-ai-explanation)
