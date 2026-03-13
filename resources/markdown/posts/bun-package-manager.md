---
id: "01KKEW277JF4527Q2P4FY99H1S"
title: "Bun vs pnpm, npm, and Yarn for package management"
slug: "bun-package-manager"
author: "benjamincrozat"
description: "Compare Bun with pnpm, npm, and Yarn for installs, lockfiles, monorepos, and security defaults before you switch package managers."
categories:
  - "javascript"
published_at: 2023-09-11T00:00:00+02:00
modified_at: 2026-03-13T15:40:00Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/KXZTmSmiqR38COC.jpg"
sponsored_at: null
---
## Bun vs pnpm, npm, and Yarn

**If you are comparing Bun to pnpm, npm, or Yarn, the first thing to know is that Bun is more than a package manager.**

[Bun](https://bun.sh) is also a runtime, test runner, and bundler, with an npm-compatible package manager built in.

That makes the comparison slightly uneven:

- compare **Bun vs pnpm** when you care most about installs, lockfiles, and monorepos
- compare **Bun vs npm** when you want a more modern default than the standard Node.js toolchain
- compare **Bun vs Yarn** when you are moving off an older workflow and want fewer moving pieces

## When Bun is a good fit

Bun makes sense when you want:

- one tool for runtime, scripts, tests, and package management
- automatic migration from `package-lock.json`, `yarn.lock`, or `pnpm-lock.yaml`
- a text-based `bun.lock`
- monorepo filtering with `bun install --filter`
- stricter dependency-script defaults through `trustedDependencies`

If you only want a package manager and are otherwise happy with your current Node.js setup, pnpm is usually the closer comparison.

## What Bun's package manager does today

Bun's package manager can:

- install dependencies with `bun install`
- add and remove packages with `bun add` and `bun remove`
- migrate existing npm, Yarn, and pnpm lockfiles
- use filtered installs in monorepos
- use isolated installs for new workspaces and monorepos
- skip dependency lifecycle scripts unless you explicitly trust them

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

When `bun install` runs in a project without `bun.lock`, Bun can automatically migrate these lockfiles:

- `package-lock.json`
- `yarn.lock`
- `pnpm-lock.yaml`

The original lockfile is preserved, so you can verify the install before deleting the old file.

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

![Terminal screenshot of bun install with successful dependency install.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/bun-laravel-5b693360c03cdefebee1.jpg/public)

Add dependencies:

```bash
bun add tailwindcss autoprefixer postcss
# dev dependencies
bun add -d typescript vitest
# pin exact versions
bun add --exact react
```

See the [bun add docs](https://bun.sh/docs/cli/add).

![Terminal screenshot of bun add installing tailwindcss, autoprefixer, and postcss.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/bun-laravel-c58deeed47edc94b9356.jpg/public)

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

Bun supports workspaces and two linker strategies: `isolated` and `hoisted`.

For fresh workspaces and monorepos, Bun now defaults to `isolated`, which helps prevent phantom dependencies. For new single-package projects, the default is `hoisted`.

Example workspace root:

```json
{
  "name": "acme",
  "private": true,
  "workspaces": ["apps/*", "packages/*"]
}
```

Use an isolated layout explicitly:

```bash
bun install --linker isolated
```

Target specific packages in large repos:

```bash
bun install --filter apps/web --filter packages/ui
```

See workspace flags in the [install docs](https://bun.com/docs/cli/install).

## Security defaults and CI

Bun does not run dependency lifecycle scripts by default. If certain scripts are trusted, allow-list them with `trustedDependencies` in `package.json`:

```json
{
  "trustedDependencies": ["esbuild", "node-gyp"]
}
```

For CI, use:

```bash
bun ci
```

That is equivalent to `bun install --frozen-lockfile`.

## So, should you pick Bun or pnpm?

If your real comparison is **Bun vs pnpm**, I would simplify it like this:

- choose **Bun** when you want a faster-moving all-in-one toolchain and are happy to adopt its runtime and package-manager model together
- choose **pnpm** when you mainly want a package manager and want the smallest change to an otherwise standard Node.js setup
- keep **npm** when compatibility and lowest-friction defaults matter more than changing tools
- keep **Yarn** mostly when your existing project already depends on Yarn-specific behavior

## Conclusion

Bun is strongest when you want one tool to cover package management, script execution, and the runtime itself. If your decision is mostly about monorepo installs and lockfiles, pnpm is still the comparison to think through most carefully. If your goal is a simpler modern default with fewer moving pieces, Bun is worth serious consideration.

If you are deciding whether Bun should replace your current package-manager habit, these are the next reads I would compare alongside it:

- [Use Bun in plain PHP projects too, not just Laravel](/bun-php)
- [Swap npm out for Bun in Laravel without friction](/bun-laravel)
- [Use npm ci when you need repeatable installs, not surprises](/npm-ci)
- [Hide the npm funding message when you just want clean installs](/npm-fund)
