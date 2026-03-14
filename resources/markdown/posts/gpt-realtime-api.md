---
id: "01KKP74DYMNXMWBH0SZ04JJSJJ"
title: "GPT Realtime API quick start with a live workflow"
slug: "gpt-realtime-api"
author: "benjamincrozat"
description: "Learn GPT Realtime step by step by creating a realtime session, opening a live WebSocket connection, and getting your first low-latency reply."
categories:
  - "ai"
  - "gpt"
published_at: 2026-03-14T13:03:42Z
modified_at: 2026-03-14T13:03:42Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
## What this GPT Realtime guide covers

As of March 14, 2026, OpenAI's current model docs call this model [`gpt-realtime`](https://developers.openai.com/api/docs/models/gpt-realtime), and the pinned snapshot is `gpt-realtime-2025-08-28`.

The current [Realtime guide](https://developers.openai.com/api/docs/guides/realtime/) says this model is built for low-latency conversations over WebRTC, WebSocket, or SIP. That makes it very different from a normal request-response GPT tutorial.

This guide stays focused on two things that actually matter when you are getting started:

- creating a realtime session token for a browser or mobile client
- sending a first live message over WebSocket and getting the reply back immediately

If you do **not** need a live, interruption-friendly session, read [GPT Audio](/gpt-audio-api) instead. It is simpler when one request and one generated audio response are enough.

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

## Create a realtime session token

For browser and mobile clients, the current Realtime docs use an ephemeral client secret rather than exposing your standard API key to the frontend.

This exact request worked for me:

```bash
curl -s https://api.openai.com/v1/realtime/client_secrets \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "session": {
      "type": "realtime",
      "model": "gpt-realtime-2025-08-28",
      "audio": {
        "output": {
          "voice": "marin"
        }
      }
    }
  }'
```

That exact request completed successfully for me and returned a short-lived client secret plus a session object that confirmed:

- the model was `gpt-realtime-2025-08-28`
- the session type was `realtime`
- the output voice was `marin`

That is the server-side step you need before a browser client opens a live voice session.

## Send your first live GPT Realtime message

Before you add audio streaming, start with a text-only Realtime turn. It is much easier to debug and still proves the live connection works.

This exact Node.js script worked for me on Node 25:

```bash
OPENAI_API_KEY="$OPENAI_API_KEY" node --input-type=module <<'NODE'
const ws = new WebSocket('wss://api.openai.com/v1/realtime?model=gpt-realtime-2025-08-28', {
  headers: {
    Authorization: `Bearer ${process.env.OPENAI_API_KEY}`,
  },
});

let finalText = '';

ws.addEventListener('open', () => {
  ws.send(JSON.stringify({
    type: 'conversation.item.create',
    item: {
      type: 'message',
      role: 'user',
      content: [
        {
          type: 'input_text',
          text: 'Say hello in one short sentence.',
        },
      ],
    },
  }));

  ws.send(JSON.stringify({
    type: 'response.create',
    response: {
      output_modalities: ['text'],
    },
  }));
});

ws.addEventListener('message', (event) => {
  const data = JSON.parse(event.data.toString());

  if (data.type === 'response.output_text.delta' && data.delta) {
    finalText += data.delta;
  }

  if (data.type === 'response.output_text.done') {
    console.log(data.text);
  }

  if (data.type === 'response.done' || data.type === 'error') {
    ws.close();
  }
});
NODE
```

That exact script completed successfully for me and printed:

```text
Hey there, great to meet you!
```

At that point, you know four important things are working:

- the WebSocket connection opens
- the model accepts your user message
- `response.create` triggers generation
- the model streams text events back in realtime

## Build something useful: a low-latency support assistant

The useful Realtime pattern is not "generate a long perfect answer." It is "keep the conversation moving with very low delay."

Imagine a support widget where the user speaks, interrupts, clarifies, and expects the assistant to react immediately.

That is where `gpt-realtime` makes sense:

- live turn-taking matters
- interruptions matter
- audio input and output matter
- session state matters

The architecture is usually:

1. your server creates an ephemeral client secret
2. your browser or mobile client opens the live connection
3. the model keeps the conversation going turn by turn

If you only need "generate one audio reply for this prompt," that architecture is heavier than necessary. [GPT Audio](/gpt-audio-api) is the simpler fit for that job.

## The field name that is easy to get wrong

One current Realtime gotcha is the output field name.

In my live tests:

- `response.modalities` failed
- `response.output_modalities` worked
- `session.modalities` failed

So if your first WebSocket request returns an unknown-parameter error, check that field first.

## What GPT Realtime supports well

The current model page says `gpt-realtime` supports:

- text and audio inputs
- text and audio outputs
- function calling

The same page also says structured outputs are **not** supported on this model, which is another reason Realtime is not the best starting point for strict JSON workflows. For those, standard GPT models like [GPT-5.0](/gpt-5-api) are usually easier.

## When GPT Realtime is the right choice

Pick `gpt-realtime` when you need:

- live voice conversations
- low latency
- interruption-friendly turn-taking
- a long-running session instead of isolated requests

Do **not** pick it just because it sounds newer or more advanced. If the job is fundamentally request-response, you are usually better off with [GPT Audio](/gpt-audio-api) or a standard GPT text model.

## Common mistakes with GPT Realtime

### 1. Starting with audio before proving text works

Start with a text-only live turn first. It isolates connection and event-shape problems quickly.

### 2. Treating Realtime like the Responses API

Realtime is event-based. You send conversation items and response events rather than one big `/v1/responses` payload.

### 3. Expecting strict JSON schemas

The current model page marks structured outputs as unsupported for `gpt-realtime`.

### 4. Exposing your normal API key in the frontend

Use the ephemeral client secret flow for browser and mobile clients.

If you are deciding between low-latency voice and simpler request-response audio, these are the next pages I would keep open:

- [See when GPT Audio is simpler than a live realtime session](/gpt-audio-api)
- [Compare that with a normal GPT-5 text workflow and structured JSON](/gpt-5-api)
- [Move the same OpenAI patterns into a PHP backend](/openai-php-client)
- [Get the GPT model basics straight before choosing another endpoint](/gpt-llm-ai-explanation)
