---
id: "01KKP5J4FEWD8TQP8QEBKFRCP9"
title: "GPT-5.4 API quick start with a real workflow"
slug: "gpt-54-api"
author: "benjamincrozat"
description: "Learn GPT-5.4 step by step by sending your first API request, building a structured workflow, and using the new phase-aware output model."
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
## What this GPT-5.4 guide covers

OpenAI released GPT-5.4 on March 5, 2026 in its [GPT-5.4 announcement](https://openai.com/index/introducing-gpt-5-4/). The current [GPT-5.4 model page](https://developers.openai.com/api/docs/models/gpt-5.4) positions it as the frontier model for complex professional work, and the broader [models guide](https://developers.openai.com/api/docs/models) recommends it as the default place to start if you are not sure which GPT-5 model to pick.

This guide is about the full GPT-5.4 model, not GPT-5.4 Pro. Every main example below uses the pinned snapshot `gpt-5.4-2026-03-05`.

By the end, you will have:

- a first successful GPT-5.4 Responses API call
- a structured support-triage workflow
- a working example of GPT-5.4's phase-aware assistant messages

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

## Send your first GPT-5.4 request

GPT-5.4 keeps the same clean baseline request shape as GPT-5.2, so the first request is easy:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.4-2026-03-05",
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

That exact request completed successfully for me and returned `Hello!`.

## Build something useful: support triage

The first real workflow can stay simple.

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
    "model": "gpt-5.4-2026-03-05",
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

That exact request completed successfully for me and returned JSON in this shape:

```json
{
  "category": "billing",
  "priority": "high",
  "needs_human": true,
  "reply_draft": "Sorry about the duplicate charge. I'm escalating this to our billing team to review the charges and contact you about any applicable refund."
}
```

That is short, predictable, and easy to plug into real app logic.

## GPT-5.4's new practical parameter: `phase`

The most important GPT-5.4-specific addition for multi-turn agent work is the `phase` field on assistant messages.

OpenAI's latest model guide uses `phase` to distinguish assistant commentary from the final answer, and GPT-5.4 also returned `phase: "final_answer"` in my live tests by default.

Here is a working example that sends prior assistant messages with phases, then asks GPT-5.4 to summarize what the assistant already said:

```bash
curl -s https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-5.4-2026-03-05",
    "input": [
      {
        "role": "assistant",
        "phase": "commentary",
        "content": [
          { "type": "output_text", "text": "I am checking the policy first." }
        ]
      },
      {
        "role": "assistant",
        "phase": "final_answer",
        "content": [
          { "type": "output_text", "text": "The policy allows a replacement within 30 days." }
        ]
      },
      {
        "role": "user",
        "content": [
          { "type": "input_text", "text": "Summarize the assistant's prior answer in 5 words." }
        ]
      }
    ],
    "reasoning": {
      "effort": "none"
    },
    "text": {
      "verbosity": "low"
    },
    "max_output_tokens": 40
  }'
```

That exact request worked for me and returned:

```text
Replacement allowed within 30 days.
```

Why this matters:

- `commentary` is useful for progress-style assistant updates
- `final_answer` marks what the user should treat as the real answer
- multi-turn tool-heavy apps become easier to structure cleanly

This is one of the clearest ways GPT-5.4 feels more agent-ready than the earlier GPT-5 releases.

## GPT-5.4 is also the long-context jump

The current model page lists a 1,050,000-token context window and 128,000 max output tokens. It also notes that prompts above 272K input tokens are priced differently: 2x input and 1.5x output for the whole session.

So yes, GPT-5.4 is the best GPT-5-family default right now, but it is also the first one where long-context cost planning becomes something you need to think about early.

## Common mistakes with GPT-5.4

### 1. Treating it like GPT-5.2 with only a better benchmark score

GPT-5.4 also changes how tool-heavy and multi-turn interactions are modeled. The `phase` field is part of that story.

### 2. Ignoring the long-context pricing threshold

If you regularly go over 272K input tokens, the pricing model changes for the whole session.

### 3. Using a moving alias when you want stable tests

For tutorials, evals, and regression checks, prefer `gpt-5.4-2026-03-05`.

## When full GPT-5.4 is worth using

Use GPT-5.4 when you want the newest frontier default for coding, professional work, long context, and agentic workflows.

The current model page lists:

- 1,050,000 context window
- 128,000 max output tokens
- $2.50 input and $15 output per 1M tokens
- `reasoning.effort` support for `none`, `low`, `medium`, `high`, and `xhigh`
- support for tools like web search, file search, code interpreter, hosted shell, apply patch, skills, computer use, MCP, and tool search

That is a powerful package, but it is not the cheapest one. If the task is simpler, GPT-5.1 or GPT-5.2 may be the more practical fit.

If you want the next useful comparisons from here, these are the ones I would keep open:

- [Compare GPT-5.4 with the previous professional-work frontier model, GPT-5.2](/gpt-52-api)
- [See the original GPT-5.0 API guide to understand where these controls started](/gpt-5-api)
- [Move this same structured-output pattern into PHP](/openai-php-client)
- [Get a simpler mental model for how GPT-style systems behave before you build a bigger agent](/gpt-llm-ai-explanation)
