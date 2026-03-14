---
id: "01KKEW27HC2H5HQCA5SN929N5G"
title: "npm ci: what it does and when to use it"
slug: "npm-ci"
author: "benjamincrozat"
description: "Learn what npm ci does, how it differs from npm install, and when to use it for CI, Docker, and reproducible builds."
categories:
  - "javascript"
  - "node-js"
published_at: 2025-07-19T11:55:00+02:00
modified_at: 2026-03-14T10:09:06Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K29DVXKM78X3NCPZZ1GYSTF3.png"
sponsored_at: null
---
## Introduction

`npm ci` installs dependencies from your lockfile exactly as committed, wipes `node_modules` first, and fails if `package.json` and `package-lock.json` are out of sync. Use it when you need reproducible installs in CI, Docker, or deployment pipelines.

If you are comparing it with `npm install`, the short version is simple: `npm ci` favors consistency, while `npm install` favors flexibility during local development.

## What does `npm ci` do

When you run `npm ci`, npm does three important things:

- **Lockfile-first install**: it installs exactly what is in `package-lock.json`.
- **Clean slate**: it removes `node_modules` before reinstalling dependencies.
- **Strict validation**: it errors out if the lockfile and `package.json` do not match.

That makes it the right default for environments where "works on my machine" is not good enough.

## What does `npm install` do

When you run `npm install`, here’s what’s happening:

- **Semver resolution and lockfile updates**: npm reads your `package.json`, figures out the latest acceptable versions based on semver ranges, and then checks against my `package-lock.json`. If allowed ranges in `package.json` mean newer versions are available, npm updates the lockfile accordingly. Since NPM 7, the lockfile typically takes priority.
- **Incremental node\_modules mutation**: `npm install` tries to save you time by only updating what’s necessary in `node_modules`. This incremental approach is great for local development, especially with fast hot-reloading.

## Why `npm ci` behaves differently from `npm install`

- **Lockfile-first philosophy**: `npm ci` completely trusts the lockfile. No version guessing, no automatic upgrades. Just precise, byte-for-byte consistency.
- **The “nuke & pave” node\_modules step**: Every time you run `npm ci`, it wipes out the entire `node_modules` folder before rebuilding it exactly according to the lockfile. This ensures absolute cleanliness, though it can be slower locally if you already have an updated node\_modules.
- **Strict sync checks**: If my `package-lock.json` and `package.json` aren’t perfectly synced (or if there’s no lockfile), `npm ci` throws an error immediately.

## When I reach for `npm ci` (and when I don’t)

Here’s my personal rule-of-thumb:

- **npm ci:** Always in CI (Continuous Integration) pipelines, Docker builds, and production deployments. It ensures deterministic, fast, and predictable outcomes.
- **npm install:** Daily local development, especially when frequently adding or upgrading dependencies.

## Common errors I still hit and quick fixes

Despite best practices, I still encounter occasional bumps:

- **“package-lock.json is out of sync”**: Quickly fix by running `npm install` (to regenerate lockfile) or, for a fresh environment, `rm -rf node_modules && npm ci`.
- **Native add-ons rebuild loop**: Mitigate by caching the entire npm cache directory (`~/.npm`) between builds. This avoids unnecessary rebuilds with node-gyp.

## FAQ

### Does `npm ci` respect .npmrc proxies?

Yes, it fully respects npm configuration files. Note: per-project `.npmrc` files override global `.npmrc`.

### Can I add a package with `npm ci`?

Nope. Instead, use `npm install lodash@latest` (or your desired package) and commit the updated lockfile.

### Is pnpm still faster?

Usually, yes—but npm ci is plenty fast for most scenarios.

## TL;DR

- Use `npm ci` for speed, consistency, and CI reliability.
- Use `npm install` locally for flexibility and incremental updates.
- Always commit and maintain a clean, synced `package-lock.json`.

If you are trying to make installs predictable instead of merely fast, these are the next reads I would compare with it:

- [See where Bun fits compared with npm, Yarn, and pnpm](/bun-package-manager)
- [Hide the npm funding message when you just want clean installs](/npm-fund)
- [Use Bun in plain PHP projects too, not just Laravel](/bun-php)
- [Swap npm out for Bun in Laravel without friction](/bun-laravel)
