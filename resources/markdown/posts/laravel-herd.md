---
id: "01KKEW27CG49GRV02BM10BPV0S"
title: "How to install Laravel Herd on macOS and Windows"
slug: "laravel-herd"
author: "benjamincrozat"
description: "Install Laravel Herd on macOS or Windows to get PHP, nginx, Composer, Node.js, and .test sites running with less local setup friction."
categories:
  - "laravel"
  - "tools"
published_at: 2023-07-19T00:00:00+02:00
modified_at: 2026-03-19T22:39:10Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6DQVFEZX3FNMC12QNBH9A0N.png"
sponsored_at: null
---
## Introduction

**Laravel Herd is the easiest local PHP stack for macOS or Windows if you want PHP, nginx, Composer, Node.js, and `.test` domains without stitching everything together by hand.**

If you only need the short version:

- install **Herd** on **macOS 12+** or **Windows 10+**
- open it once and finish the onboarding flow
- verify with `herd --version`, `php --version`, and `composer --version`
- use `herd isolate` when a project needs a different PHP version

The official docs now position Herd as the dependency-light alternative to a Homebrew-heavy or manually assembled local stack.

## What Laravel Herd gives you

After setup, Herd gives you a local environment with:

- PHP
- nginx
- Composer
- the Laravel installer
- Node.js
- `.test` local domains

That is why Herd is attractive for Laravel work: it gets you from "new machine" to "app running locally" faster than a more manual stack.

## Install Laravel Herd on macOS

### Requirements

According to the official macOS installation docs, Herd requires **macOS 12.0 or later**.

### Installation steps

1. Download the latest macOS build from [the official Herd download page](https://herd.laravel.com/download).
2. Open the DMG file.
3. Drag Herd into the **Applications** folder.
4. Launch Herd to start the onboarding process.

During onboarding, Herd downloads the latest PHP version and installs its background service for nginx and local DNS handling.

If you are moving from Valet, Herd can detect an existing Valet installation and migrate sites, certificates, and settings.

### Verify the install

Run:

```bash
herd --version
php --version
laravel --version
composer --version
node --version
```

If those commands resolve, the core setup is working.

## Install Laravel Herd on Windows

### Requirements

The current Windows installation docs say Herd requires **Windows 10 or higher** and needs **administrator privileges** during setup.

### Installation steps

1. Download Herd from [the official Windows download page](https://herd.laravel.com/download/windows).
2. Run the installer as an administrator.
3. Open Herd and complete the setup flow.

The Windows installer adds the Herd helper service that handles host-file updates and maps local sites to `.test` domains.

### Verify the install

Run these in PowerShell or Windows Terminal:

```powershell
herd --version
php --version
laravel --version
composer --version
node --version
```

## Which PHP version does Herd install?

Herd now ships with the **latest stable PHP version by default**.

As of **March 19, 2026**, the Herd docs say that default is **PHP 8.5** on both macOS and Windows.

That is a meaningful update from older Herd guides that were anchored to PHP 8.4.

## Switching PHP versions globally or per project

This is one of Herd's best features.

You can switch the global PHP version:

```bash
herd use 8.5
```

Or isolate a single project to its own PHP version:

```bash
cd ~/Sites/my-app
herd isolate 8.3
```

Remove that per-project pin with:

```bash
herd unisolate
```

If you work across older and newer Laravel apps, this is where Herd becomes more useful than a one-version local stack.

## Managing extra PHP versions

The current Herd docs focus less on a fixed support matrix and more on managing PHP versions through the app or CLI.

The practical takeaway is:

- Herd installs the latest stable PHP version by default
- you can install additional versions as needed
- you can set a global version or isolate a single project

That is a better mental model than memorizing an older static list of bundled versions.

## PHP extensions in Herd

Herd includes many extensions out of the box, but not every possible one.

If you need something extra on macOS, the official docs recommend installing it with **Homebrew** and **PECL**, then enabling it in Herd's `php.ini`.

That means Herd stays lightweight while still letting you add what a specific project needs.

## Herd vs Valet vs Sail

Use this shorter rule of thumb:

- choose **Herd** if you want the easiest native local setup on macOS or Windows
- choose **Valet** if you prefer the Homebrew-style macOS workflow
- choose **Sail** if you want Docker containers that look more like production

Herd is the easiest recommendation when the main goal is "get Laravel running locally without yak-shaving."

## Herd Pro

Herd Basic is free.

Herd Pro is the paid tier and the current checkout page describes it as a **one-year license** that works on **macOS and Windows** and can be activated on **up to two devices**.

The Pro tier adds developer-focused tools like:

- service management
- debugging helpers
- mail, dump, and log viewers

Because pricing can change, I would always confirm the current details on the official [Herd Pro page](https://herd.laravel.com/checkout).

## Troubleshooting note for Windows

The current Windows docs include one practical fix worth knowing: if Herd feels slow, Windows Defender may be scanning Herd's config folder. The official docs recommend excluding `%USERPROFILE%\\.config\\herd` when Defender is the bottleneck.

That is not always necessary, but it is a useful first thing to check if the Windows install feels unexpectedly sluggish.

## Platform availability

Herd is available on **macOS** and **Windows**.

There is still **no Linux version planned** in the official positioning.

## Conclusion

If you want the easiest Laravel-friendly local stack on macOS or Windows in 2026, Herd is the simplest recommendation. The main things to remember are that installation now centers around the onboarding flow, the default PHP version is currently **8.5**, and per-project isolation is the feature that really earns its keep.

If you are piecing together the rest of your local setup after Herd, these are the follow-up reads I would keep open:

- [Check whether your PHP version is part of the problem](/check-php-version)
- [Run PHP and Laravel more smoothly on macOS](/laravel-valet)
- [Set up Laravel on macOS without a messy local stack](/laravel-installation-macos)
- [See whether Laravel Forge still fits the way you deploy](/laravel-forge)
- [Compare hosting options before you deploy another Laravel app](/best-laravel-hosting-providers)
