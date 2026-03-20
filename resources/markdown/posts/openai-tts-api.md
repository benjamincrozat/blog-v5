---
id: "01KKEW27HTEAQZ05JPM4CYGWK3"
title: "OpenAI text-to-speech API: current models and voices"
slug: "openai-tts-api"
author: "benjamincrozat"
description: "Use OpenAI's text-to-speech API with the current speech endpoint, model choices, voices, and a working curl example."
categories:
  - "ai"
published_at: 2023-11-07T00:00:00+01:00
modified_at: 2026-03-20T12:45:00Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/7pNvdOotwL3Ebjv.jpg"
sponsored_at: null
---
## OpenAI's text-to-speech API at a glance

OpenAI turns text into audio with `POST /v1/audio/speech`. The current docs center on `gpt-4o-mini-tts` for steerable speech, while `tts-1` and `tts-1-hd` still matter if you want the older speed-versus-fidelity trade-off.

If you are wiring speech generation into a PHP app later, my [OpenAI PHP client guide](/openai-php-client) and [GPT Audio API quick start](/gpt-audio-api) are the natural next reads.

## Get your API key

1. Create or sign in to your OpenAI account.
2. Add billing if your project needs it.
3. Generate an API key and store it in a password manager.
4. Keep the key server-side only.

## Pick the right model

- `gpt-4o-mini-tts` is the best fit when you want more control over the delivery style.
- `tts-1` is a solid default when you want a straightforward speech call.
- `tts-1-hd` is still the safer choice when fidelity matters more than latency.

All of them use the same speech endpoint, so you can switch models without rewriting your whole request shape.

## Choose a voice

OpenAI still gives you a small set of built-in voices. In practice, these are the names most readers reach for:

- `alloy`
- `echo`
- `fable`
- `onyx`
- `nova`
- `shimmer`

If you are shipping something important, check the voice options page before launch in case the list changes.

## Make your first request

Open your terminal and ask OpenAI to say a classic line:

```bash
curl https://api.openai.com/v1/audio/speech \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-4o-mini-tts",
    "input": "Hello, World!",
    "instructions": "Sound warm, clear, and confident.",
    "voice": "alloy",
    "response_format": "mp3"
  }' \
  --output hello-world.mp3
```

That gives you one audio file you can play back or hand off to a frontend.

## Keep pricing current

OpenAI now lists pricing on the model pages, and it changes often enough that I would not hard-code numbers into a tutorial. Check the [TTS-1 model page](https://developers.openai.com/api/docs/models/tts-1) and the [OpenAI pricing page](https://openai.com/pricing) before you publish or budget the feature.

If speech generation is only one part of your OpenAI stack, these are the next reads I would keep open:

- [Use the OpenAI API from PHP with less boilerplate](/openai-php-client)
- [Generate audio from text with the GPT Audio API](/gpt-audio-api)
- [Get your first GPT-5 API call working in PHP](/gpt-5-api)
- [Get a plain-English explanation of how GPT-style models work](/how-llms-work)
