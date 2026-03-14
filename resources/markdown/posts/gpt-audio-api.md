---
id: "01KKP74DYMNXMWBH0SZ04JJSJK"
title: "GPT Audio API quick start with a working example"
slug: "gpt-audio-api"
author: "benjamincrozat"
description: "Learn GPT Audio step by step by generating a spoken reply, saving the WAV file, and using the returned transcript in your app."
categories:
  - "ai"
  - "gpt"
published_at: 2026-03-14T13:03:43Z
modified_at: 2026-03-14T13:10:13Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
## What this GPT Audio guide covers

As of March 14, 2026, OpenAI's current model docs call this model [`gpt-audio`](https://developers.openai.com/api/docs/models/gpt-audio), and the pinned snapshot is `gpt-audio-2025-08-28`.

The current model page describes it as OpenAI's first generally available audio model. The current [Audio and speech guide](https://developers.openai.com/api/docs/guides/audio/) also says it accepts audio inputs and outputs.

If you are starting fresh and want the newer model in this part of the catalog, compare this guide with [`gpt-audio-1.5`](https://developers.openai.com/api/docs/models/gpt-audio-1.5) first. Keep reading here when you specifically want the original `gpt-audio` model and its current Chat Completions workflow.

This guide is deliberately about the simplest useful path:

- send one request
- get spoken output back
- save the WAV file
- use the transcript in your app

If you need a live, low-latency conversation that stays open turn by turn, jump to [GPT Realtime](/gpt-realtime-api) instead. `gpt-audio` is the simpler fit when one request and one answer are enough.

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

## Send your first GPT Audio request

The current Audio guide says audio generation is available through the Chat Completions API, and that audio generation is not yet supported in the Responses API.

This exact request worked for me:

```bash
curl -s https://api.openai.com/v1/chat/completions \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-audio-2025-08-28",
    "modalities": ["text", "audio"],
    "audio": {
      "voice": "alloy",
      "format": "wav"
    },
    "messages": [
      {
        "role": "user",
        "content": "Say hello in one short sentence."
      }
    ]
  }' > response.json
```

That exact request completed successfully for me and returned:

- the model `gpt-audio-2025-08-28`
- a base64-encoded WAV file in `choices[0].message.audio.data`
- a transcript in `choices[0].message.audio.transcript`

## Save the WAV file

Once you have `response.json`, decode the audio payload into a file.

macOS:

```bash
jq -r '.choices[0].message.audio.data' response.json | base64 -D > hello.wav
```

Linux:

```bash
jq -r '.choices[0].message.audio.data' response.json | base64 --decode > hello.wav
```

The exact flow above worked for me and produced a valid WAV file.

## Read the transcript too

The transcript is just as useful as the audio file because you can store it, index it, or show it in the UI.

```bash
jq -r '.choices[0].message.audio.transcript' response.json
```

That exact command returned this for me:

```text
Hello! It's great to talk with you.
```

So with one request, you now have:

- a spoken answer you can play
- the text version you can show in the interface

## Build something useful: voice replies for support tickets

This model is a good fit when your app needs to turn one prompt into one audio response without maintaining a live session.

For example, imagine a customer support dashboard that wants to generate a short spoken reply for an accessibility-friendly playback feature.

The flow is simple:

1. write the safe text reply prompt
2. ask `gpt-audio` for `text` and `audio`
3. save the WAV payload
4. display the transcript next to the player

That is much simpler than a full Realtime setup, and for many products it is exactly enough.

## What to notice in the request shape

Three fields matter right away:

- `modalities` must include `"audio"` if you want spoken output
- `audio.voice` picks the voice
- `audio.format` picks the output format

The Audio guide currently shows this pattern through Chat Completions, not the Responses API. That is a detail worth checking again if OpenAI changes the guide later, because this part of the API surface is still more specialized than a standard GPT text request.

## When GPT Audio is the right choice

Pick `gpt-audio` when you need:

- one request and one generated spoken reply
- audio output plus a transcript
- a simpler integration than a live Realtime session

Do **not** pick it by default if you need interruption handling, streaming turn-taking, or a long-running voice session. That is [GPT Realtime](/gpt-realtime-api).

## Common mistakes with GPT Audio

### 1. Reaching for the Responses API first

The current Audio guide says audio generation is not yet supported in the Responses API.

### 2. Forgetting to request audio in `modalities`

If you leave out `"audio"`, you are no longer asking for spoken output.

### 3. Ignoring the transcript

The transcript is often just as valuable as the audio file for UI, logging, and search.

### 4. Using GPT Audio for a live conversation

If the user needs to interrupt, talk back immediately, or stay in an ongoing session, use [GPT Realtime](/gpt-realtime-api) instead.

If you are mapping out the rest of your OpenAI stack, these are the next pages I would open from here:

- [Use GPT Realtime when your audio workflow needs low-latency conversation](/gpt-realtime-api)
- [Compare this with a standard GPT-5 text workflow and structured outputs](/gpt-5-api)
- [Move the same OpenAI patterns into a PHP backend](/openai-php-client)
- [Get the GPT model basics straight before choosing another endpoint](/gpt-llm-ai-explanation)
