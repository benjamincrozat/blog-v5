---
id: "01KKEW277N8JPVHS02W12AZNE4"
title: "Use Bun as your package manager in any PHP project"
slug: "bun-php"
author: "benjamincrozat"
description: "Enjoy a faster workflow to build your front-end dependencies in your PHP projects, thanks to the package management abilities of Bun."
categories:
  - "javascript"
  - "php"
published_at: 2023-09-10T00:00:00+02:00
modified_at: 2025-09-29T15:41:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/Wdzw1LQCdjXyTbS.jpg"
sponsored_at: null
---
## Introduction

[Bun](https://bun.com) is a fast JavaScript all‑in‑one toolkit you can use as a package manager in PHP projects. I use it daily in Laravel and Symfony builds to install dependencies and run scripts faster. If you are used to npm, pnpm, or Yarn, Bun feels familiar but quicker.

## Why switch from npm, pnpm, or Yarn to Bun

Most PHP developers only use Node.js to build front‑end assets. Switching tools can feel like a chore, but Bun gives you real gains:

- bun install can be up to 25x faster than npm install. See the [bun install docs](https://bun.com/docs/cli/install).
- bun run has much lower overhead than npm run, roughly 6 ms vs 170 ms in the example (about 28x). See the [quickstart](https://bun.com/docs/quickstart).
- Simple script startup on Linux is shown around 5.2 ms with Bun vs 25.1 ms with Node.js (about 4–5x). See the [bun run docs](https://bun.com/docs/cli/run).

In short:

1. Your front‑end dependencies will install faster.
2. Your assets will compile faster.
3. CI runs will be faster.

## Install Bun on macOS

Use Homebrew. Either of these works (pick one):

```bash
brew install oven-sh/bun/bun
# or
brew tap oven-sh/bun
brew install bun
```

See the official [installation guide](https://bun.com/docs/installation).

## Install Bun on Linux and WSL

Use the installer script:

```bash
curl -fsSL https://bun.com/install | bash
```

Before you start, make sure unzip is installed. On Linux, the minimum kernel is 5.1, and 5.6 or higher is recommended. Details are in the [installation guide](https://bun.com/docs/installation).

## Install Bun on Windows

Bun has first‑class Windows support (Windows 10 version 1809 or later). You can install it in several ways:

PowerShell:

```powershell
powershell -c "irm bun.sh/install.ps1 | iex"
```

Scoop:

```powershell
scoop install bun
```

Chocolatey:

```powershell
choco install bun
```

See [Install Bun on Windows](https://bun.com/docs/installation) and the Windows section on the [Get started page](https://bun.com/get).

## Migrate your project: replace existing lockfiles and run bun install

You can switch from npm, pnpm, or Yarn with a small cleanup, then one install:

- Remove other lockfiles so Bun can create its own lockfile:
  - package-lock.json (npm)
  - pnpm-lock.yaml (pnpm)
  - yarn.lock (Yarn)
- Run:

```bash
bun install
```

As of Bun v1.2, the default lockfile is text‑based bun.lock. Projects that already use bun.lockb still work, and Bun continues to support that format. See the [lockfile docs](https://bun.com/docs/install/lockfile).

For reproducible builds in CI, commit bun.lock and use:

```bash
bun install --frozen-lockfile
# or
bun ci
```

## Add and remove packages with Bun

To install your current dependencies (from package.json), run:

```bash
bun install
```

If you want to troubleshoot network or cache issues, you can disable the cache:

```bash
bun install --no-cache
```

For details and flags, see the [bun install command](https://bun.com/docs/cli/install).

![Terminal showing bun install creating bun.lock on macOS.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/69/conversions/ybYunPr2RMZPLO3vFaMImsaecxEEnz-metaQ2xlYW5TaG90IDIwMjMtMDktMTEgYXQgMDkuMDAuMDJAMnguanBn--medium.jpg)

Add packages with bun add. Here is an example with three packages:

```bash
bun add tailwindcss autoprefixer postcss
```

See [bun add](https://bun.com/docs/cli/add) for options like --dev and --exact.

![Terminal showing bun add installing tailwindcss, autoprefixer, and postcss.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/70/UndzC78D9Uclf8eMBlPr0yN2wbhXRy-metaQ2xlYW5TaG90IDIwMjMtMDktMTEgYXQgMDkuMDIuMTFAMnguanBn-.jpg)

Remove a package with bun remove:

```bash
bun remove axios
```

Since the Fetch API is built in, many apps do not need Axios. Both Bun and Node.js 18+ provide fetch globally. See [bun remove](https://bun.com/docs/cli/remove).

## Run your package.json scripts with Bun

Bun plugs into your current workflow. Run the scripts defined in package.json like you do with npm:

```bash
bun run dev
```

bun run is fast and works well for PHP front‑end build pipelines. It also offers much lower startup overhead than npm run. See the [bun run docs](https://bun.com/docs/cli/run).

On Windows, bun run uses Bun shell by default. If a script expects your system shell, set this in bunfig.toml:

```toml
[run]
shell = "system"
```

Learn more in [bunfig.toml](https://bun.com/docs/runtime/bunfig).

## CI and troubleshooting tips

- Reproducible installs: use bun ci or bun install --frozen-lockfile, and commit bun.lock. See [bun install](https://bun.com/docs/cli/install).
- Speed and caching: Bun uses a global cache and hardlinks/copy‑on‑write to keep disk usage low. See [global cache](https://bun.com/docs/cli/install#disk-efficiency).
- No cache runs: bun install --no-cache can help when debugging registry issues.
- Security note: Bun does not run dependency lifecycle scripts (like postinstall) unless you list the package in trustedDependencies. See [lifecycle scripts and trustedDependencies](https://bun.com/docs/cli/install#lifecycle-scripts).
- Windows shell gotcha: bun run defaults to Bun shell on Windows. If a script fails due to shell syntax, set [run.shell](https://bun.com/docs/runtime/bunfig#runshell---use-the-system-shell-or-buns-shell) to system.

## Conclusion

Bun is a drop‑in package manager that makes PHP front‑end builds feel fast. It installs quickly on macOS, Linux, and Windows, it writes a readable bun.lock by default, and it speeds up installs and scripts. I recommend trying it on a small Laravel or Symfony project and measuring the difference.

Related reading: [What's the fuss around Bun's package manager abilities?](https://benjamincrozat.com/bun-package-manager)

To build on "Use Bun as your package manager in any PHP project", start with these related reads:

- [Use Bun as Your Package Manager in Any Laravel Project](/bun-laravel)
- [Bun package manager vs npm, Yarn, and pnpm in 2025](/bun-package-manager)
- [npm ci vs. npm install: here's the difference](/npm-ci)
- [PHP for Mac: get started fast using Laravel Valet](/laravel-valet)
- [6 ways to check your version of PHP](/check-php-version)
- [Disable "packages are looking for funding" (npm fund message)](/npm-fund)
- [Alpine.js: a lightweight framework for productive developers](/alpine-js)
- [openai-php/client: leverage OpenAI's API, effortlessly](/openai-php-client)
- [Add Alpine.js to any Laravel project](/alpine-js-laravel)
- [Get the current URL path in PHP](/php-current-url-path)

