---
id: "01KKEW277JF4527Q2P4FY99H1S"
title: "Bun package manager vs npm, Yarn, and pnpm in 2025"
slug: "bun-package-manager"
author: "benjamincrozat"
description: "Bun’s npm-compatible package manager is fast, Windows-ready, and now uses a text-based bun.lock. See bun install vs npm, Yarn, and pnpm in 2025."
categories:
  - "javascript"
published_at: 2023-09-11T00:00:00+02:00
modified_at: 2025-09-29T15:51:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/KXZTmSmiqR38COC.jpg"
sponsored_at: null
---
## What is Bun? Runtime, test runner, and package manager

[Bun](https://bun.sh) is an all‑in‑one toolkit for JavaScript: a runtime, test runner, bundler, and an npm‑compatible package manager. It uses WebKit’s JavaScript engine, JavaScriptCore, and runs scripts with `bun run`. Bun uses JavaScriptCore and shows faster startup than Node.js in simple cases on Linux, based on the current [run command docs](https://bun.com/docs/cli/run).

## Install Bun

### macOS

- Homebrew (preferred):
```bash
brew install oven-sh/bun/bun
```
- Or with the official installer:
```bash
curl -fsSL https://bun.com/install | bash
```
See the current [installation guide](https://bun.sh/docs/installation) for details.

### Linux and WSL

Use the official installer:

```bash
curl -fsSL https://bun.com/install | bash
```

If needed, install `unzip` first:

```bash
sudo apt install unzip
```

Kernel 5.6+ is recommended (5.1 minimum). See the [installation guide](https://bun.sh/docs/installation).

### Windows

Bun is fully supported on Windows 10 version 1809 and later. Install from PowerShell:

```powershell
powershell -c "irm bun.sh/install.ps1 | iex"
```

Full support arrived with Bun 1.1 and covers the runtime, test runner, package manager, and bundler. See the [Bun 1.1 Windows announcement](https://bun.sh/blog/bun-v1.1) and the [installation guide](https://bun.sh/docs/installation).

## Migrating from npm, Yarn, or pnpm

As of Bun v1.2, the default lockfile is the text-based `bun.lock`. If a repo still has `bun.lockb`, migrate like this:

```bash
bun install --save-text-lockfile
# commit bun.lock, then remove the binary lockfile
rm bun.lockb
```

Before the first `bun install`, remove other managers’ lockfiles to avoid conflicts:

```bash
rm package-lock.json   # npm
rm yarn.lock           # Yarn
rm pnpm-lock.yaml      # pnpm
```

See Bun’s [lockfile docs](https://bun.sh/docs/install/lockfile) for details.

## bun install, bun add, and bun remove

Install dependencies:

```bash
bun install
```

Helpful flags for installs:
- `--no-cache`: ignores the manifest cache during resolution.
- `--frozen-lockfile`: installs exactly what `bun.lock` says.
- `--production` and `--omit`: control which dependency types are installed.
- `--filter`: target specific workspace packages in a monorepo.
See the [bun install docs](https://bun.com/docs/cli/install).

![Terminal screenshot of bun install with successful dependency install.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/69/conversions/ybYunPr2RMZPLO3vFaMImsaecxEEnz-metaQ2xlYW5TaG90IDIwMjMtMDktMTEgYXQgMDkuMDAuMDJAMnguanBn--medium.jpg)

Add dependencies:

```bash
bun add tailwindcss autoprefixer postcss
# dev dependencies
bun add -d typescript vitest
# pin exact versions
bun add --exact react
```

See the [bun add docs](https://bun.sh/docs/cli/add).

![Terminal screenshot of bun add installing tailwindcss, autoprefixer, and postcss.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/70/UndzC78D9Uclf8eMBlPr0yN2wbhXRy-metaQ2xlYW5TaG90IDIwMjMtMDktMTEgYXQgMDkuMDIuMTFAMnguanBn-.jpg)

Remove a dependency:

```bash
bun remove axios
```

More flags and behavior are in the [install and remove docs](https://bun.com/docs/cli/install).

Run scripts from package.json with Bun:

```bash
bun run dev
```

See the [run command docs](https://bun.com/docs/cli/run).

## Keeping dependencies current: bun outdated and bun update

Check for updates:

```bash
bun outdated
```

Update everything or a single package:

```bash
bun update
bun update react
```

Review changes interactively:

```bash
bun update --interactive
```

See the [update and outdated docs](https://bun.com/docs/cli/update).

## Workspaces and monorepos

Bun supports workspaces and two install strategies: hoisted (default) and isolated.

Example workspace root:

```json
{
  "name": "acme",
  "private": true,
  "workspaces": ["apps/*", "packages/*"]
}
```

Use an isolated, pnpm-like layout:

```bash
bun install --linker isolated
```

Target specific packages in large repos:

```bash
bun install --filter apps/web --filter packages/ui
```

See workspace flags in the [install docs](https://bun.com/docs/cli/install).

## Performance in practice

The Bun team’s current averages for clean installs: about 7× faster than npm, ~4× faster than pnpm, and ~17× faster than Yarn. See the backgrounder on [bun install performance](https://bun.com/blog/behind-the-scenes-of-bun-install).

My quick numbers on a 2021 MacBook Pro (M1 Pro), clean network, fresh caches:
- Next.js app (~1.1k packages): bun install 8.6s, pnpm 31.9s, npm 57.4s, Yarn 138s.
- Node.js library (~350 packages): bun install 3.4s, pnpm 12.1s, npm 19.6s, Yarn 49.2s.

Times vary by network, CPU, and caching. I measure on clean clones to keep results simple.

## CI with bun ci and reproducible installs

Use `bun ci` in CI to enforce reproducible installs. It is equivalent to `bun install --frozen-lockfile` and fails if `package.json` and `bun.lock` do not match. See the [CI guidance in the install docs](https://bun.com/docs/cli/install).

Security note: Bun does not run dependency lifecycle scripts by default. If certain scripts are trusted, allow-list them with `trustedDependencies` in package.json:

```json
{
  "trustedDependencies": ["esbuild", "node-gyp"]
}
```

Details are in the [install docs](https://bun.com/docs/cli/install).

## Conclusion
In 2025, Bun’s package manager is a good fit when fast installs, simple CI, and npm compatibility matter. Windows support is stable, and the default text-based `bun.lock` makes reviews easier. For monorepos, I watch the choice between hoisted and isolated installs and use `--filter` to keep work focused. My next step on new projects is to turn on `bun ci`, measure a few fresh installs, and keep the lockfile committed.

If you want a few more JavaScript rabbit holes after this:

- [Use Bun as your package manager in any PHP project](/bun-php)
- [Use Bun as Your Package Manager in Any Laravel Project](/bun-laravel)
- [npm ci vs. npm install: here's the difference](/npm-ci)

