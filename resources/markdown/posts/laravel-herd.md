---
id: "01KKEW27CG49GRV02BM10BPV0S"
title: "Laravel Herd: install it on macOS or Windows"
slug: "laravel-herd"
author: "benjamincrozat"
description: "Laravel Herd is Laravel's native local PHP development environment for macOS and Windows. Here's how to install it, verify it, and switch PHP versions."
categories:
  - "laravel"
  - "tools"
published_at: 2023-07-18T22:00:00Z
modified_at: 2026-03-19T22:45:00Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K6DQVFEZX3FNMC12QNBH9A0N.png"
sponsored_at: null
---
## Introduction

Laravel Herd is Laravel's native local PHP development environment for macOS and Windows.

If you want the short version: download Herd from the official site, run the installer, approve the admin prompt when asked, and then verify `herd`, `php`, `composer`, `laravel`, and `node` in your terminal. Herd handles local `.test` domains for you and bundles the PHP, nginx, and Node.js tooling you need to start quickly.

This guide focuses on the current install flow for both platforms, how Herd handles PHP and Node.js today, and when it makes more sense than [Laravel Valet](/laravel-valet).

## Laravel Herd requirements at a glance

- macOS: Herd requires macOS 12 or later.
- Windows: Herd requires Windows 10 or later and needs administrator privileges during setup.
- Default project folder: Herd parks `~/Herd` on macOS and `%USERPROFILE%\Herd` on Windows, so projects there are automatically available at `your-project.test`.

## How to install Laravel Herd on macOS

1. [Download Herd](https://herd.laravel.com/download).
2. Open the DMG file.
3. Drag Herd into your Applications folder.
4. Launch Herd from Applications.
5. Complete onboarding. Herd downloads the latest stable PHP version, installs its background service, and configures local `.test` routing.
6. If you already use Valet, Herd can detect your existing Valet sites, certificates, and settings and help you migrate them.

### Verify the macOS install

Run:

```bash
herd --version
php --version
laravel --version
composer --version
node --version
```

If you use Fish shell, add Herd's binaries to your path:

```bash
fish_add_path -U $HOME/Library/Application\ Support/Herd/bin/
```

## How to install Laravel Herd on Windows

1. [Download Herd for Windows](https://herd.laravel.com/download/windows).
2. Run the installer as administrator.
3. Let the installer add the HerdHelper service, which updates your hosts file and maps your local sites to `.test` domains.
4. Open Herd and finish setup.
5. Put Laravel projects in `%USERPROFILE%\Herd` if you want them available automatically as `your-project.test`.

### Verify the Windows install

Run these commands in PowerShell or Command Prompt:

```powershell
herd --version
php --version
laravel --version
composer --version
node --version
```

### Windows performance note

If Herd feels slow on Windows, the official docs recommend excluding `%USERPROFILE%\.config\herd` from Windows Defender scans.

## What Laravel Herd installs for you

Herd is designed to be the fast native option for local Laravel work.

- On macOS, Herd ships with PHP, nginx, dnsmasq, and Node.js tooling.
- On Windows, Herd ships with PHP, nginx, and Node.js tooling, and uses HerdHelper to handle `.test` mappings.
- On both platforms, Herd includes the `herd` CLI and lets you use `php`, `composer`, and the Laravel installer from your terminal.

That makes it a much simpler starting point than piecing together a Homebrew-based stack on macOS or a Docker-based one with Sail.

## PHP versions in Laravel Herd

Herd now ships with the latest stable PHP version by default. As of March 19, 2026, the official macOS and Windows docs say that default is PHP 8.5.

You can change the global PHP version from the app or with the CLI:

```bash
herd use 8.2
```

To see what Herd can install and add another version:

```bash
herd php:list
herd php:install 8.3
herd php:update 8.4
```

If one project needs a different PHP version than the global default, isolate it:

```bash
cd ~/Herd/my-app
herd isolate 8.1
herd unisolate
```

On Windows, the same commands work. If your project lives in the default parked directory, start in `%USERPROFILE%/Herd/my-app` instead.

If you need help checking which PHP version Laravel is actually using, see [Check PHP version in CLI, browser, or Laravel](/check-php-version).

## Node.js and extensions

Herd ships with `nvm` and installs the latest available Node.js version during setup. You can switch Node versions with `nvm use` or isolate a project with Herd's Node commands if you need a different version for one app.

Herd also includes many common PHP extensions out of the box, but the exact list can change by platform and release. If you need something extra:

- On macOS, Herd's docs recommend installing extra extensions with Homebrew and PECL, then enabling them in `~/Library/Application Support/Herd/config/php/<version>/php.ini`.
- On Windows, Herd's docs point you to non-thread-safe Windows builds from PECL and to `%USERPROFILE%\.config\herd\bin\<version>\php.ini` for activation.

For the always-current details, check the official [macOS PHP extensions docs](https://herd.laravel.com/docs/macos/technology/php-extensions) or [Windows PHP extensions docs](https://herd.laravel.com/docs/windows/technology/php-extensions).

## Laravel Herd Pro pricing

Herd Basic is free.

Herd Pro starts at $99 per year for one license, and that license can be activated on two devices at the same time. Team licenses start at $299. Pro adds the integrated services and debugging tools that Herd does not include in the free tier by default.

If you do not renew, Herd drops back to the free version. The official pricing page also says there is a 14-day refund policy.

## Herd vs Valet vs Sail

- Choose Herd if you want the fastest native Laravel setup on macOS or Windows, with `.test` domains, GUI controls, and optional Pro services.
- Choose [Laravel Valet](/laravel-valet) if you are on macOS and prefer a lighter, CLI-first workflow built around Homebrew.
- Choose Sail if you want Docker containers because you need closer parity with a containerized production setup.

## Is Laravel Herd available on Linux?

No. The official Herd pricing FAQ says there are currently no plans for a Linux version.

## Conclusion

Laravel Herd is the easiest way to get a modern Laravel PHP environment running on macOS or Windows without piecing the stack together yourself. For most Laravel projects, the free tier is enough to get started, and the install flow is now straightforward on both platforms.

If you are building out the rest of your local Laravel setup, these next reads are the most relevant:

- [How to install Laravel Valet on macOS](/laravel-valet)
- [How to install Laravel on macOS](/laravel-installation-macos)
- [Check PHP version in CLI, browser, or Laravel](/check-php-version)
- [Latest Laravel version and support status](/laravel-versions)
