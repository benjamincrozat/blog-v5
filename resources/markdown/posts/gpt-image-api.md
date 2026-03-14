---
id: "01KKP8CHPWHXN7WPEVBSD7MHC5"
title: "GPT Image API quick start with real outputs"
slug: "gpt-image-api"
author: "benjamincrozat"
description: "Learn GPT Image step by step by generating images, editing one of them, and seeing the real results from OpenAI's latest image model."
categories:
  - "ai"
  - "gpt"
published_at: 2026-03-14T13:25:48+00:00
modified_at: 2026-03-14T13:25:48+00:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/gpt-image-api.png"
sponsored_at: null
---
## What this GPT Image guide covers

As of March 14, 2026, OpenAI's current image-generation docs point to [`gpt-image-1.5`](https://developers.openai.com/api/docs/models/gpt-image-1.5) as the latest GPT Image model. If you still see examples using `gpt-image-1`, treat those as older examples.

The current [image generation guide](https://developers.openai.com/api/docs/guides/image-generation/) says the OpenAI API can both generate and edit images from prompts. This walkthrough focuses on the simplest practical path:

- generate your first image
- save the returned file
- improve the prompt
- edit an existing image

Just as important, every image in this article was generated from the live API while writing it. So you are not only seeing the requests. You are seeing the actual results too.

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

## Generate your first GPT Image output

This exact request worked for me:

```bash
curl -s https://api.openai.com/v1/images/generations \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-image-1.5",
    "prompt": "A bright editorial illustration of a tiny orange robot painting a blue website mockup on a giant canvas, clean shapes, warm light, modern product-design style.",
    "size": "1024x1024"
  }' > response.json
```

In my live test, that call returned image data in `data[0].b64_json`.

Decode it into a file:

macOS:

```bash
jq -r '.data[0].b64_json' response.json | base64 -D > robot-canvas.png
```

Linux:

```bash
jq -r '.data[0].b64_json' response.json | base64 --decode > robot-canvas.png
```

This was the actual output from that request:

![A bright editorial illustration of a tiny orange robot painting a blue website mockup on a giant canvas.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/01KKP8EX86KNGYBE7CZ7D8EJEF.png/public)

That first result is already useful because it proves the whole path end to end:

- request the image
- receive base64 image data
- decode it locally
- inspect the real output

## Prompt detail matters more than people expect

The easiest way to get better results is usually not a fancy parameter. It is a better prompt.

The first prompt above specified:

- the subject
- the color palette
- the visual style
- the lighting

That kind of specificity helps more than a vague prompt like "make a cool robot image."

## Try a second prompt that pushes text rendering

One reason to test GPT Image carefully is that some prompts depend on readable text inside the image.

This exact request also worked for me:

```bash
curl -s https://api.openai.com/v1/images/generations \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "gpt-image-1.5",
    "prompt": "A retro-futuristic conference poster in orange and blue, bold readable headline that says SHIP FASTER, smaller subheading that says Design with prompts, crisp typography, centered composition, screenprint feel.",
    "size": "1024x1536"
  }' > poster.json
```

This was the actual result from that prompt:

![A retro-futuristic conference poster in orange and blue with the headline SHIP FASTER and the subheading Design with prompts.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/01KKP8EX86MSE2CPSVHV9MJKH4.png/public)

That is a good example of why prompt wording matters. Asking for readable text, layout style, and composition gave the model a much clearer target.

## Edit an existing image

Generation is only half of the story. The current image guide also covers editing, and that is often the more practical feature in a real app.

I used the first robot image as the source and asked the model to add a sticky note that says `DRAFT`.

This exact request worked for me:

```bash
curl -s https://api.openai.com/v1/images/edits \
  -H "Authorization: Bearer $OPENAI_API_KEY" \
  -F "model=gpt-image-1.5" \
  -F "image[]=@robot-canvas.png" \
  -F 'prompt=Keep the same illustration, but add a yellow sticky note on the easel with the word DRAFT in readable uppercase letters.' \
  > edit.json
```

Then I decoded the result the same way:

macOS:

```bash
jq -r '.data[0].b64_json' edit.json | base64 -D > robot-canvas-draft.png
```

Linux:

```bash
jq -r '.data[0].b64_json' edit.json | base64 --decode > robot-canvas-draft.png
```

This was the actual edited result:

![The original robot website illustration edited to include a yellow sticky note with the word DRAFT.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/01KKP8EX86QSCDD2RA2SS3SHWS.png/public)

That is the kind of workflow I would reach for in a product:

1. generate a base image
2. keep the source file
3. ask for a targeted change
4. review the new output

## Build something useful: a blog or landing-page artwork workflow

If you publish often, GPT Image is useful for more than one-off experiments.

A small repeatable workflow could be:

1. write a prompt for the article hero image
2. generate 2-3 candidates
3. pick the closest one
4. edit it to add or remove one detail
5. export the final image for the post

That is much more realistic than treating image generation like a magic one-shot button.

## When GPT Image is the right tool

Use `gpt-image-1.5` when you need:

- new images from text prompts
- edits to an existing source image
- stronger prompt adherence than older image examples

If your goal is image understanding rather than image creation, OpenAI's [images and vision guide](https://developers.openai.com/api/docs/guides/images-vision/) is the better next doc to open.

## Common mistakes with GPT Image

### 1. Copying old `gpt-image-1` examples without checking the current model docs

As of March 14, 2026, OpenAI's current image-model page points to `gpt-image-1.5`.

### 2. Writing vague prompts

Subject, style, composition, lighting, and any text you want in the image all help.

### 3. Forgetting to decode `b64_json`

The image data is not ready to view until you save it as a real file.

### 4. Throwing away the source image too early

If you want to iterate with edits, keep the first generated file around.

The next clicks here depend on what you want to build around those images:

- [Move the same OpenAI API patterns into a PHP app](/openai-php-client)
- [Compare image generation with the standard GPT-5 text workflow](/gpt-5-api)
- [Understand the broader OpenAI model landscape before choosing another endpoint](/how-llms-work)
