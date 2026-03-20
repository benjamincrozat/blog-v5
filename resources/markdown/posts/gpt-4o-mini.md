---
id: "01KKEW279RDCDKK4ZNTSSRFX59"
title: "GPT-4o mini API: a practical quickstart"
slug: "gpt-4o-mini"
author: "benjamincrozat"
description: "Call GPT-4o mini with the current OpenAI API, understand the tradeoffs, and use structured JSON output when you need it."
categories:
  - "ai"
  - "gpt"
published_at: 2024-07-19T00:00:00+02:00
modified_at: 2026-03-20T12:45:00Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/2OmaBtVXazOZljL.png"
sponsored_at: null
---
## GPT-4o mini at a glance

GPT-4o mini is OpenAI's small, fast, low-cost model. The current docs say it accepts text and image inputs and produces text outputs, so it is a good fit for chat assistants, extraction jobs, and lightweight vision work.

If you are still getting comfortable with LLMs, start with [how LLMs work](/how-llms-work). It will make the rest of this page easier to follow.

## Create an account and API key

1. Sign in to your OpenAI account.
2. Add billing if your project needs it.
3. Generate an API key and store it securely.
4. Keep that key on the server, not in the browser.

## Make your first request

The Responses API is the cleaner default for new work, but GPT-4o mini still fits older chat-based codebases too. This example uses the newer endpoint:

```bash
curl https://api.openai.com/v1/responses \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-4o-mini",
    "input": "Say hello in one short sentence."
  }'
```

If you already have a chat endpoint in production, you can keep it and just swap the model name.

OpenAI documents GPT-4o mini as a 128K-context model, which is usually more than enough for short apps, automations, and many structured extraction tasks.

## Keep JSON output predictable

If you need strict JSON, keep using structured output instead of hoping the model behaves. The older `response_format` pattern is still familiar to many codebases, but the important bit is the same: tell the model exactly what shape you want and keep the schema tight.

```json
{
  "model": "gpt-4o-mini",
  "messages": [
    {
      "role": "system",
      "content": "You are an assistant, and you only reply with JSON."
    },
    {
      "role": "user",
      "content": "Hello!"
    }
  ],
  "response_format": {
    "type": "json_object"
  }
}
```

If you want a new workflow, check the current structured outputs docs before you ship it. The exact error text and constraints have changed over time, but the core idea has stayed the same.

## Pricing

|  Model | Input | Output |
|--------|-------|--------|
| **gpt-4o-mini (128K context)** | **$0.15 / 1M tokens** | **$0.60 / 1M tokens** |
| **gpt-4o (128K context)** | **$5 / 1M tokens** | **$15 / 1M tokens** |
| **gpt-3.5-turbo-0125 (16K context)** | **$0.5 / 1M tokens** | **$1.5 / 1M tokens** |

## Ideas to build thanks to GPT-4o mini's API

GPT-4o mini is a good fit when you want to ship something useful without paying large-model prices for every request. For instance, I created [Nobinge](https://nobinge.watch), a tool that lets you summarize and chat with YouTube videos.

Here are a few ideas worth experimenting with:
- Additional AI-based features for existing products
- Automated email responses
- Chatbots
- Content summarizers
- Personal assistants
- Personalized teaching programs
- Sentiment analysis tools
- Spam filters

And if you want to add speech later, OpenAI also has a [text-to-speech API](/openai-tts-api).

If cost is the main reason you are comparing models right now, these are the next reads I would keep open:

- [Use GPT-4.1 when you want a clearer step up from older models](/gpt-41)
- [Get your first GPT-5 API call working in PHP](/gpt-5-api)
- [Call the OpenAI API from PHP with less boilerplate](/openai-php-client)
- [Turn text into speech with the OpenAI API from PHP](/openai-tts-api)
- [Get a plain-English explanation of how GPT-style models work](/how-llms-work)
