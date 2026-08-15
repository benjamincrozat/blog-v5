---
id: "01KKEW27HFT8GRG8H0DAFW40A6"
title: "npm fund: what it means and how to disable it"
slug: "npm-fund"
author: "benjamincrozat"
description: "Understand npm's packages are looking for funding message, inspect dependency funding links, and disable the notice for one command, one project, or your user config."
categories:
  - "javascript"
published_at: 2024-03-03T23:00:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/npm-fund.png"
sponsored_at: null
---
The npm message saying “packages are looking for funding” is informational. It does not mean the install failed or that payment is required.

To hide it for one install:

```bash
npm install --no-fund
```

To hide it in one repository:

```bash
npm config set fund false --location=project
```

To hide it for your user account across projects:

```bash
npm config set fund false --location=user
```

![The npm fund message in my terminal after installing packages.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/rUiZiYHULe49bgleBIzQmpvA4eHrB2fytglgSxaT.png/public)

## What npm fund does

Package authors can add a `funding` field to `package.json`. npm collects those entries from the installed dependency tree.

Run this to see them:

```bash
npm fund
```

The command prints a tree of packages and their funding URLs. It does not send money, change dependencies, or modify the lockfile.

The install summary is only telling you that this metadata exists:

```text
added 47 packages, and 12 packages are looking for funding
  run `npm fund` for details
```

## Choose the right config scope

| Scope | Command | What changes |
| --- | --- | --- |
| One command | `npm install --no-fund` | Nothing is saved |
| One project | `npm config set fund false --location=project` | Writes `fund=false` to the project's `.npmrc` |
| Your user | `npm config set fund false --location=user` | Applies to projects using your user npm config |

Project scope is the right choice when a repository wants quiet CI logs for everyone. Commit `.npmrc` if that is an intentional team setting:

```ini
fund=false
```

User scope is better when the preference is yours and should not change the repository.

## Check the active value

Run:

```bash
npm config get fund
```

It prints `true` or `false` after npm merges project, user, global, and command-line settings.

To see which configuration files npm is reading:

```bash
npm config get userconfig
npm config get globalconfig
```

If the funding notice still appears, check for a higher-priority project `.npmrc` or a command that explicitly passes `--fund`.

## Turn the message back on

For your user configuration:

```bash
npm config set fund true --location=user
```

For a project, either set it back to `true` or delete the `fund=false` line from `.npmrc`.

Disabling the notice does not affect `npm fund`; you can still run the command whenever you want to inspect the funding links.

The official [npm fund documentation](https://docs.npmjs.com/cli/v11/commands/npm-fund) covers the command, while the [npm config documentation](https://docs.npmjs.com/cli/v11/using-npm/config) explains scope and precedence.

Related guides:

- [Use npm ci for clean lockfile installs](/npm-ci)
- [Compare Bun with pnpm and npm](/bun-package-manager)
- [Use Bun in a plain PHP project](/bun-php)
- [Swap npm for Bun in Laravel](/bun-laravel)
