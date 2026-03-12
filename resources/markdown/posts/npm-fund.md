---
id: "01KKEW27HFT8GRG8H0DAFW40A6"
title: "Disable \"packages are looking for funding\" (npm fund message)"
slug: "npm-fund"
author: "benjamincrozat"
description: "Learn how you can disable the \"packages are looking for funding\" messages in your project or globally."
categories:
  - "javascript"
published_at: 2024-03-04T00:00:00+01:00
modified_at: 2025-07-26T07:12:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K57MRHV35PSWPMR3PFKQJ105.png"
sponsored_at: null
---
## Introduction

![The npm find command showing in my terminal.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/rUiZiYHULe49bgleBIzQmpvA4eHrB2fytglgSxaT.png/public)

Tired of npm spamming your terminal with **“packages are looking for funding”** every time you run `npm install`? Here’s the one-liner to silence it, plus why this message appears in the first place.

**TL;DR:**

*   **Globally:** `npm config set fund false`
*   **Per project:** Add `fund=false` to your `.npmrc`
*   **Per command:** `npm install --no-fund`

## What exactly is the `npm fund` command?

`npm fund` isn’t an error or warning—it’s informational. This message is npm’s polite reminder that some of the open-source packages you’re using would appreciate financial support.

Maintainers use platforms like GitHub Sponsors, Open Collective, and Tidelift to gather contributions. Supporting packages helps ensure their long-term maintenance, security, and improvement.

But let’s be realistic—financially supporting every dependency isn’t feasible for most of us, so disabling these messages can be totally legitimate.

## Disable the message globally

If you’re absolutely sure you never want to see this message again, disable it globally by running:

npm config set fund false

If you later change your mind, re-enable it:

npm config delete fund

## Disable the message in your project only

To silence funding messages for one project only, use the `.npmrc` file:

1.  Navigate to your project’s root.
2.  Create or edit `.npmrc`.
3.  Add:

fund=false

Now NPM will skip the message only in this project.

## Temporarily disable the message (per command)

Want to keep your options open? Use the `--no-fund` flag to temporarily disable funding messages:

npm install --no-fund

This flag also works with other npm commands like `npm update`.

## Does this affect Yarn or pnpm?

Nope. Yarn and pnpm don’t display NPM’s “packages are looking for funding” message because they handle funding notices differently. This configuration only applies to npm itself.

## Why are packages looking for funding?

Open-source developers dedicate time and resources to maintaining packages that everyone uses freely. The `npm fund` message encourages users to contribute financially to sustain this ecosystem.

However, it’s understandable that you can’t fund everyone. Disabling this notice is a practical choice if it fits your workflow.

## Alternative ways to support maintainers

If financial support isn’t your thing or isn’t feasible, you can still help:

*   Star projects on GitHub to boost their visibility.
*   Submit bug reports, improvements, or PRs.
*   Share projects you find valuable on social media or blogs.

These actions still significantly help maintainers and the community.

## Conclusion

You’ve now got multiple ways to disable npm’s “packages are looking for funding” message. Choose the method that suits you best and enjoy clearer terminal output!

If you want a few more JavaScript rabbit holes after this:

- [npm ci vs. npm install: here's the difference](/npm-ci)
- [Bun package manager vs npm, Yarn, and pnpm in 2025](/bun-package-manager)
- [Use Bun as your package manager in any PHP project](/bun-php)

