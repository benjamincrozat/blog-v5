---
id: "01KKRRZZC4TD4CKABKHW7XP29P"
title: "Remodex makes Codex from my iPhone surprisingly usable"
slug: "remodex-review"
author: "benjamincrozat"
description: "Remodex is a local-first iPhone companion for Codex that pairs through a QR code and keeps the real runtime on your Mac."
categories:
  - "ai"
published_at: null
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/remodex-review.png"
sponsored_at: null
---
I like [Remodex](https://github.com/Emanuele-web04/remodex) a lot more than I expected to.

The short version is simple: it lets me control [Codex](https://openai.com/index/codex/) from my iPhone without pretending the phone should become the real runtime. The actual work still happens on my Mac. The phone is the remote control, the session stays paired, and the whole thing feels much more practical than "AI on mobile" usually does.

If you want to try it right away, there is a public [TestFlight beta](https://testflight.apple.com/join/PKZhBUVM) for iOS, and the bridge itself is also published on [npm as `remodex`](https://www.npmjs.com/package/remodex).

## What Remodex actually is

The [README](https://github.com/Emanuele-web04/remodex/blob/main/README.md) describes Remodex as a local-first open-source bridge plus iOS app, and that framing is exactly why it stands out to me.

This is not "run a toy coding chatbot on your phone." It is "keep Codex where it belongs, on your Mac, then give your phone a clean remote interface for the moments when you are away from the keyboard or just do not want to sit in front of it."

That distinction matters. According to the README, Remodex already supports:

- end-to-end encrypted pairing and chats
- fast mode and plan mode
- follow-up prompts while a turn is still running
- in-app notifications
- git actions like commit, push, pull, and branch switching
- photo attachments from the camera or library
- shared thread history with Codex on your Mac

That is a much stronger product direction than a vague "mobile AI assistant" pitch. It is specific. It knows what job it is trying to do.

If you mainly use Codex for real repo work, my guide on [using AGENTS.md with Codex](/agents-md) is the broader setup piece I would keep nearby too.

## The setup already feels real

I tried the published bridge first because that is the most honest way to judge the happy path.

The install is exactly what the project promises:

```sh
npm install -g remodex@latest
remodex up
```

On my side, `remodex up` immediately printed a pairing QR code, created a session, and connected cleanly. That does not prove every part of the iPhone flow is perfect, but it does prove this is not just a nice README with an imaginary quick start.

One detail I appreciate is that the repo draws a clear line between the public source checkout and the published package. The [README](https://github.com/Emanuele-web04/remodex/blob/main/README.md) says source checkouts stay self-host friendly and do not bake in a public relay, while the published npm package may include a default relay at publish time. That is exactly the kind of detail I like seeing in an early project, because it shows the author has thought about the distribution model instead of hiding it.

If you clone the repo instead of using npm, the README tells you to run:

```sh
git clone https://github.com/Emanuele-web04/remodex.git
cd remodex
./run-local-remodex.sh
```

It also calls out a small but useful onboarding detail: scan the QR from inside the Remodex app, not with the generic iPhone camera app, or you may just get a web search instead of a pairing flow.

<!-- Screenshot placeholder: the terminal right after `remodex up` shows the QR code and session details. -->

## Why I like the product direction

The main thing I like is that Remodex does not try to fake convenience by centralizing everything on somebody else's server.

The [project architecture in the README](https://github.com/Emanuele-web04/remodex/blob/main/README.md#architecture) keeps the bridge on the Mac, forwards JSON-RPC traffic to Codex, and lets the phone act as a paired client. The [self-hosting model](https://github.com/Emanuele-web04/remodex/blob/main/SELF_HOSTING_MODEL.md) also makes the philosophy explicit: the public repo stays open-source and self-host friendly, while hosted defaults are treated as a distribution choice rather than the whole product.

That feels right for this kind of tool.

If I am letting an app touch prompts, repository actions, and git commands, I want the local-first story to be strong. Remodex seems to understand that trust is part of the product.

I also think the feature set is well chosen. Git controls from the phone sound like a gimmick until you imagine real moments when they help: reviewing a branch while away from your desk, steering an ongoing run, or queueing a follow-up prompt before you forget it. The same goes for fast mode, plan mode, and notifications. They all make more sense on a remote companion than on a standalone mobile coding app.

If you want the wider mental model behind why agent tooling works at all, my plain-English post on [how LLMs work](/how-llms-work) is the background read I would pair with this one.

## A few rough edges are part of the deal

The author is very direct in the README: this is still early, and you should expect bugs.

I think that is the right expectation to keep in mind before you install it. This does not read like a polished enterprise product yet. It reads like a promising tool made by someone who understands the workflow and is shipping quickly.

That is not a criticism. It is part of the appeal.

Remodex already feels more intentional to me than a lot of AI side projects because the scope is tight. It is trying to solve one real problem: make Codex sessions reachable from an iPhone without moving the real machine, repo, and runtime away from your Mac.

If the app keeps getting smoother, that is a compelling niche.

<!-- Screenshot placeholder: the Remodex onboarding or paired-device screen after the QR scan succeeds. -->
<!-- Screenshot placeholder: an active thread on iPhone showing prompt input, plan mode, or git actions. -->

## If you want to try Remodex

This is the path I would use:

1. Install the bridge with `npm install -g remodex@latest`
2. Run `remodex up` on your Mac
3. Install the iOS beta from [TestFlight](https://testflight.apple.com/join/PKZhBUVM) or build the app from source in Xcode
4. Open the app and scan the pairing QR from inside Remodex

If you prefer reading the source before you touch anything, start with the [GitHub repo](https://github.com/Emanuele-web04/remodex). If you want the quickest route, the [TestFlight beta](https://testflight.apple.com/join/PKZhBUVM) plus the npm bridge looks like the real shortcut.

My current take is simple: Remodex already feels like one of the more interesting Codex companion apps around. Not because it tries to do everything, but because it understands exactly what should stay on the Mac and exactly what is useful to move onto the phone.

If Remodex has you thinking less about one app and more about the broader coding-agent workflow, these are the next reads I would open:

- [Set up Codex with project instructions that actually help](/agents-md)
- [Use AGENTS.md and skills without turning your repo into a mess](/agents-md-vs-skills)
- [Get the simple mental model for what LLMs are doing under the hood](/how-llms-work)
