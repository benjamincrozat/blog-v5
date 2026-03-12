---
id: "01KKEW27HC2H5HQCA5SN929N5G"
title: "npm ci vs. npm install: here's the difference"
slug: "npm-ci"
author: "benjamincrozat"
description: "Should you run npm ci or stick with good old npm install? Here's exactly what I learned."
categories:
  - "javascript"
  - "node-js"
published_at: 2025-07-19T11:55:00+02:00
modified_at: 2025-11-27T10:34:00+01:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K29DVXKM78X3NCPZZ1GYSTF3.png"
sponsored_at: null
---
## Introduction

If you’ve ever wondered whether to run `npm ci` (which literally means _clean install_) or stick with good old `npm install`, you’re in the right place. Here’s exactly what I learned.

## What does `npm install` do

When you run `npm install`, here’s what’s happening:

- **Semver resolution and lockfile updates**: npm reads your `package.json`, figures out the latest acceptable versions based on semver ranges, and then checks against my `package-lock.json`. If allowed ranges in `package.json` mean newer versions are available, npm updates the lockfile accordingly. Since NPM 7, the lockfile typically takes priority.
- **Incremental node\_modules mutation**: `npm install` tries to save you time by only updating what’s necessary in `node_modules`. This incremental approach is great for local development, especially with fast hot-reloading.

## What does `npm ci` do

But what about `npm ci`? Here’s why it’s special:

- **Lockfile-first philosophy**: `npm ci` completely trusts the lockfile. No version guessing, no automatic upgrades. Just precise, byte-for-byte consistency.
- **The “nuke & pave” node\_modules step**: Every time you run `npm ci`, it wipes out the entire `node_modules` folder before rebuilding it exactly according to the lockfile. This ensures absolute cleanliness, though it can be slower locally if you already have an updated node\_modules.
- **Strict sync checks**: If my `package-lock.json` and `package.json` aren’t perfectly synced (or if there’s no lockfile) `npm ci` throws an error immediately.

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
