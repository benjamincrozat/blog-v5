---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/244/dWhvP99H2F5X07q5kSWbK2u7m45yoQ-metadm9pY2UuanBn-.jpg
Title: "Master OpenAI's new Text-to-speech API"
Description: "Add super realistic voices with ease to your applications, thanks to OpenAI's Text-to-speech API."
Published at: 2023-11-07
Categories: ai
---

## Introduction to OpenAI's Text-to-speech API

Imagine inviting someone with the perfect voice to read out your content. 

That's *exactly* what OpenAI's TTS API feels like, except, there are no hiring or casting calls involved!

With a simple endpoint, your application can now convert text to lifelike spoken audio.

Whether you're looking to boost accessibility or add auditory flair to your products, this API is your ticket.

## Create an account to get your Text-to-speech API key

1. [Create an account](https://chat.openai.com/auth/login).

![Creating an account on OpenAI](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/229/conversions/Dt2ElwOQoKtwjEhuw2eu1uGceEDJnF-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMTkuNTQuMjZAMngucG5n--medium.jpg)

3. Confirm your email address.
4. [Log in](https://chat.openai.com/auth/login).
5. Check for your free $5 of credits on [this page](https://platform.openai.com/account/billing/overview).

![The free $5 of credit given to all new developers.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/228/conversions/V2xA6LlqgeEAd87BpKshqkY19sV9rp-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMTkuNTUuMDdAMngucG5n--medium.jpg)

7. [Generate your first API key](https://platform.openai.com/api-keys). Be careful, it will only be displayed once. Copy and paste it into a password manager so it's stored securely.
8. Start using the Text-to-speech API! (Continue reading to learn how.)

![API key generation on OpenAI](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/227/conversions/yZF7oBp7WI9jbq8gFcNWDWtmQDWWXb-metaQ2xlYW5TaG90IDIwMjMtMTEtMDYgYXQgMjAuMDIuMjhAMngucG5n--medium.jpg)

## Choose the voice for your speech

Personality matters, and so does the voice of your app!

OpenAI lets you choose from six unique built-in voices—each with its distinctive style.

Want something that sounds optimistic and bright? Try "Alloy".

Looking for a voice with a bit of gravitas? "Onyx" could be the way to go. 

It's like picking your favorite character in a video game, just that they're here to narrate your user's journey, haha!

Learn more about all [the available voices for the Text-to-speech API](https://platform.openai.com/docs/guides/text-to-speech/voice-options).

## Choose the audio file's format

When it comes to audio formats, OpenAI offers a selection designed to cater to diverse needs and preferences.

Your choice can impact the audio quality, file size, and compatibility with various devices and applications.

Here's a breakdown of the available formats and why you might choose one over another:

- **AAC (Advanced Audio Coding)**: AAC files are widely used and recognized for their superior compression efficiency, delivering better audio quality than MP3 at the same bitrate. Choose AAC if you need a balance between quality and file size, expect users to listen on a wide range of devices.

- **FLAC (Free Lossless Audio Codec)**: FLAC is your go-to for lossless compression, meaning it reduces file size without any loss of quality. Audiophiles and professional settings where pristine audio is paramount will benefit from FLAC. It's ideal for archival purposes and whenever you have enough bandwidth to handle larger file sizes.

- **MP3**: This format is universally supported and known for its compatibility across all devices and software. MP3 uses lossy compression, resulting in smaller file sizes at the cost of some audio quality. It's a safe bet for general use, especially when ease of distribution and storage limitations are a concern.

- **Opus**: Opus is perfect for real-time applications such as gaming or communication apps because of its low latency and good compression at various bitrates. It's also versatile, providing decent quality even with minimal bandwidth.

## Make your first request to the Text-to-speech API

Alright, time to get our hands dirty (in a clean coding sense, of course)! 

Open your terminal and let's ask our AI to say "Hello, World!" in the most classic of developer traditions:

```bash
curl https://api.openai.com/v1/audio/speech \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "tts-1",
    "input": "Hello, World!",
    "voice": "alloy",
    "response_format": "mp3"
  }' \
  --output hello-world.mp3
```

Remember to replace `YOUR_API_KEY` with your actual API key. This little snippet sends a request to our new TTS friend, asking it to generate that classic phrase in an MP3.

Tweak, play, and have fun with it—programming is all about exploration, after all.

![The Text-to-speech API from OpenAI in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/243/conversions/uIHvav58f1yA18pPedbbCrS832AMAZ-metaQ2xlYW5TaG90IDIwMjMtMTEtMDcgYXQgMTIuNTkuMTVAMngucG5n--medium.jpg)

## Generate higher quality audio with the tts-1-hd model

Now, if you want to dial up the quality, the `tts-1-hd` is what you need. 

This is for those moments when nothing but the crispest, cleanest audio will do.

Keep in mind that the higher quality comes with a bit more patience needed for processing.

But for those premium experiences, it's a no-brainer.

```bash
curl https://api.openai.com/v1/audio/speech \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "model": "tts-1-hd",
    "input": "Hello, World!",
    "voice": "alloy",
    "response_format": "flac",
  }' \
  --output hello-world.flac
```

## Pricing for the Speech-to-text API

**[The pricing for the Speech-to-text API](https://openai.com/pricing) is surprisingly cheap and starts at $0.015 per 1,000 characters for the TTS model and $0.030 per 1,000 characters for the TTS-HD model.**

Here's an old-fashioned table:

|  Model | Usage |
|--------|-------|
| TTS | $0.015 / 1K characters |
| TTS-HD | $0.030 / 1K characters |
