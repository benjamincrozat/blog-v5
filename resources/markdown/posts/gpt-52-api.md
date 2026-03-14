---
id: "01KKP5J4FEWD8TQP8QEBKFRCP7"
title: "GPT-5.2 API quick start with a real workflow"
slug: "gpt-52-api"
author: "benjamincrozat"
description: "Learn GPT-5.2 step by step by sending your first API request and building a support triage workflow with structured JSON."
categories:
  - "ai"
  - "gpt"
published_at: 2026-03-14T12:36:21+00:00
modified_at: 2026-03-14T12:36:21+00:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/gpt-52-api.png"
sponsored_at: null
---
## What this GPT-5.2 guide covers

OpenAI introduced GPT-5.2 on December 11, 2025 in its [GPT-5.2 announcement](https://openai.com/index/introducing-gpt-5-2/). The current [GPT-5.2 model page](https://developers.openai.com/api/docs/models/gpt-5.2) now treats it as the previous frontier model for professional work and recommends GPT-5.4 as the newer default.

This page is about the full GPT-5.2 model, not GPT-5.2 Chat or GPT-5.2-Codex. Every main example below uses the pinned snapshot `gpt-5.2-2025-12-11`.

By the end, you will have two things working:

- a first successful GPT-5.2 Responses API call
- a small support-triage workflow that returns strict JSON

If you want the original GPT-5.0 or GPT-5.1 context first, start with [GPT-5.0](/gpt-5-api) or [GPT-5.1](/gpt-51-api).

## Get your API key ready

You need an OpenAI account, a funded API project, and an API key from the [API keys page](https://platform.openai.com/api-keys).

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

## Send your first GPT-5.2 request

GPT-5.2 keeps the cleaner GPT-5.1-style Responses API surface, including `reasoning.effort: none` as the default. That makes the first request very simple:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.2-2025-12-11",
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

That exact request completed successfully in my test and returned `Hello!`.

## Build something useful: support triage

Let us use the same kind of workflow you would build in a real app.

The incoming message:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

The goal:

1. classify the issue
2. set a priority
3. decide whether a human should step in
4. draft a safe reply

## Return strict JSON with a schema

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.2-2025-12-11",
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
  "reply_draft": "Sorry about that - please share the invoice or receipt IDs and the email on the account so we can investigate the duplicate Pro charge. Once confirmed, our billing team will process any applicable refund and follow up with an update."
}
```

GPT-5.2 is good at keeping this kind of output compact and useful without much prompt drama.

## GPT-5.2's extra reasoning level: `xhigh`

The most important GPT-5.2-specific addition is support for `xhigh` reasoning effort.

That gives you one more tier above `high` for harder professional and tradeoff-heavy tasks. It is not something I would use by default, but it is a real upgrade over GPT-5.1 when the task needs deeper work.

Here is a tiny live-tested request that uses it:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.2-2025-12-11",
    "input": [
      {
        "role": "user",
        "content": [
          { "type": "input_text", "text": "Reply with OK only." }
        ]
      }
    ],
    "reasoning": {
      "effort": "xhigh"
    },
    "text": {
      "verbosity": "low"
    },
    "max_output_tokens": 120
  }'
```

That exact request worked in my test and returned `OK`.

The lesson is not the output. It is that `xhigh` is accepted and practical, but you should still budget for extra tokens because the model may spend more of them thinking before it answers.

## How I would choose GPT-5.2 reasoning levels

For most app workflows:

- use `none` for extraction, routing, and simple transforms
- use `low` for lightweight judgment
- use `medium` for ambiguous tasks
- use `high` for serious planning or analysis
- use `xhigh` only when the task is valuable enough to justify the extra depth

In other words, GPT-5.2 is where the GPT-5 line becomes more obviously tuned for professional, slower, higher-value work.

## Common mistakes with GPT-5.2

### 1. Reaching for `xhigh` too early

If the task is simple, `xhigh` is wasted effort. Start with `none` or `low`, then move up only when the answers are not good enough.

### 2. Forgetting that GPT-5.2 is now a previous frontier model

If you want OpenAI's newest general recommendation today, that is GPT-5.4, not GPT-5.2.

### 3. Using the alias when you want stable tests

For tutorials and evals, prefer `gpt-5.2-2025-12-11`.

## When full GPT-5.2 is worth using

Use GPT-5.2 when your work looks more like professional analysis than ordinary low-latency app logic.

The current model page lists:

- 400,000 context window
- 128,000 max output tokens
- $1.75 input and $14 output per 1M tokens
- `reasoning.effort` support for `none`, `low`, `medium`, `high`, and `xhigh`

That makes GPT-5.2 a strong fit for harder knowledge work, document-heavy analysis, and higher-stakes workflows where better reasoning can justify the extra spend.

If you are trying to decide whether GPT-5.2 is the right stop on the ladder, these are the next posts I would compare it with:

- [See how GPT-5.1 handles the same kind of workflow with lower-cost defaults](/gpt-51-api)
- [Use the newest GPT-5.4 model when you want the current frontier option](/gpt-54-api)
- [Move this exact structured-output pattern into PHP](/openai-php-client)
- [Understand the original GPT-5.0 model this family started from](/gpt-5-api)
