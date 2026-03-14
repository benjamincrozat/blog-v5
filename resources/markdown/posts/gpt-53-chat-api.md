---
id: "01KKP5J4FEWD8TQP8QEBKFRCP8"
title: "GPT-5.3 Chat API quick start with a real workflow"
slug: "gpt-53-chat-api"
author: "benjamincrozat"
description: "Learn how to use GPT-5.3 Chat in the API, what it can and cannot do, and how to build a small structured-output workflow with it."
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
image_path: "images/posts/generated/gpt-53-chat-api.png"
sponsored_at: null
---
## What GPT-5.3 Chat actually is in the API

If you searched for "GPT-5.3 API", the first thing to know is that the current OpenAI catalog does **not** expose GPT-5.3 as a normal frontier model the way it does for GPT-5.1, GPT-5.2, or GPT-5.4.

Instead, the current [GPT-5.3 Chat model page](https://developers.openai.com/api/docs/models/gpt-5.3-chat-latest) exposes `gpt-5.3-chat-latest`, which points to the GPT-5.3 Instant snapshot currently used in ChatGPT. That same page explicitly says OpenAI recommends [GPT-5.2](https://developers.openai.com/api/docs/models/gpt-5.2) for API usage, but you can still use GPT-5.3 Chat to test the latest chat-oriented improvements.

So this guide is deliberately about **GPT-5.3 Chat in the API**, not a full GPT-5.3 frontier model that does not currently exist in the public catalog.

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

## Send your first GPT-5.3 Chat request

The first live difference I hit with this model is that it is stricter than the others about supported controls.

This exact request worked for me:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.3-chat-latest",
    "input": [
      {
        "role": "user",
        "content": [
          { "type": "input_text", "text": "Say hello in one short sentence." }
        ]
      }
    ],
    "text": {
      "verbosity": "medium"
    },
    "max_output_tokens": 80
  }'
```

Why `medium`?

Because GPT-5.3 Chat rejected `text.verbosity: "low"` in my live test. The API error said the only supported value was `medium`.

## Build something useful: support triage

Even though GPT-5.3 Chat is not the recommended general API model, it still supports structured outputs. So you can still build the same kind of small app workflow on top of it.

Incoming message:

```text
Hi, I was billed twice for my Pro plan today. Please refund the extra charge.
```

Goal:

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
    "model": "gpt-5.3-chat-latest",
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
      "verbosity": "medium",
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
  "reply_draft": "Sorry about the possible duplicate charge. Please share the invoice numbers or last four digits of the charge so our billing team can review it."
}
```

So yes, GPT-5.3 Chat can still do structured app work. It is just more constrained than the mainline reasoning models.

## The two GPT-5.3 Chat limitations you should know

These are the differences that mattered in my live testing:

### `text.verbosity` is fixed to `medium`

If you try `low`, the API rejects it.

### `reasoning.effort` is also fixed to `medium`

If you send `reasoning.effort: "none"`, the API rejects that too. In my test, the error said the only supported value was `medium`.

That means GPT-5.3 Chat is much less configurable than GPT-5.1, GPT-5.2, or GPT-5.4.

## Why this model still exists

The current model page describes GPT-5.3 Chat as the GPT-5.3 Instant model used in ChatGPT. In practice, that makes it useful for:

- trying the current chat-oriented style in the API
- lightweight prototyping
- comparing chat behavior against GPT-5.2 or GPT-5.4

But if you are choosing a serious API default, I would not start here. OpenAI's own docs point you toward GPT-5.2 instead.

## When GPT-5.3 Chat is worth using

Use GPT-5.3 Chat if you specifically want to test the latest ChatGPT-style model behavior in the API.

The current model page lists:

- `gpt-5.3-chat-latest` as the model ID
- 128,000 context window
- 16,384 max output tokens
- $1.75 input and $14 output per 1M tokens
- no pinned dated snapshot, only the `-latest` alias

That last point matters. You cannot treat GPT-5.3 Chat like a stable reproducible snapshot in the same way as GPT-5.1, GPT-5.2, or GPT-5.4.

If you want the next most useful comparisons from here, these are the ones I would open:

- [Use GPT-5.2 when you want the recommended API model instead of the chat-only one](/gpt-52-api)
- [Move the same structured-output workflow into PHP](/openai-php-client)
- [Compare the newer GPT-5.4 frontier model with GPT-5.3 Chat](/gpt-54-api)
- [Read the original GPT-5.0 API guide for the start of this family](/gpt-5-api)
