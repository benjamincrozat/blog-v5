---
id: "01KKEW27HC2H5HQCA5SN929N5G"
title: "npm ci: what it does and when to use it"
slug: "npm-ci"
author: "benjamincrozat"
description: "Use npm ci for clean lockfile installs in CI, Docker, and deployments, understand how it differs from npm install, and fix lockfile and flag errors."
categories:
  - "javascript"
  - "node-js"
published_at: 2025-07-19T09:55:00Z
modified_at: 2026-08-15T09:28:36Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01K29DVXKM78X3NCPZZ1GYSTF3.png"
sponsored_at: null
---
`npm ci` installs the dependency graph from `package-lock.json`, removes the existing `node_modules` directory first, and fails when the lockfile does not match `package.json`.

```bash
npm ci
```

Use it in CI, Docker builds, and deployments. Use `npm install` when you intentionally add, remove, or update dependencies.

## npm ci versus npm install

| Behavior | `npm ci` | `npm install` |
| --- | --- | --- |
| Needs a lockfile | Yes | No |
| Fails when `package.json` and the lockfile disagree | Yes | Usually updates the lockfile |
| Removes `node_modules` first | Yes | No |
| Writes `package.json` or the lockfile | No | Can |
| Adds one package | No | Yes |
| Best use | Automation and clean verification | Local dependency changes |

`npm ci` gives you the same locked dependency graph when the npm version, platform, architecture, configuration, registry, and package artifacts are the same. It does not promise byte-for-byte identical `node_modules` across different operating systems or CPU architectures.

## What npm ci requires

The project must contain `package-lock.json` or `npm-shrinkwrap.json` with a supported lockfile version. Commit that file with `package.json`.

I checked the current behavior with npm 11.16.0:

- a clean matching project installed successfully
- changing `package.json` without updating `package-lock.json` exited with `EUSAGE`
- npm did not repair the lockfile during `npm ci`

That strict failure is the point. It tells you the commit does not describe one dependency tree.

## The normal local and CI workflow

Add or update dependencies locally:

```bash
npm install lodash
```

Review and commit both files:

```text
package.json
package-lock.json
```

Then verify the exact committed state:

```bash
npm ci
npm test
```

If a teammate or dependency bot changes one file without the other, CI should fail before the build reaches production.

## Use npm ci in GitHub Actions

Let `setup-node` cache npm's download cache, then run the clean install:

```yaml
steps:
  - uses: actions/checkout@v4

  - uses: actions/setup-node@v4
    with:
      node-version-file: .nvmrc
      cache: npm

  - run: npm ci
  - run: npm test
  - run: npm run build
```

The cache speeds up package downloads. Do not cache and restore `node_modules` over `npm ci`; the command deletes that directory before installing anyway.

## Use npm ci in Docker

Copy dependency manifests before application source so Docker can reuse the install layer:

```dockerfile
FROM node:24-alpine AS build

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build
```

When the final runtime needs Node dependencies but not development tools, install production dependencies separately:

```dockerfile
RUN npm ci --omit=dev
```

Do not use `--omit=dev` in a frontend build stage when Vite, Tailwind, TypeScript, or another build tool lives in `devDependencies`.

## Keep lockfile creation flags consistent

If `package-lock.json` was created with a flag that changes dependency resolution, `npm ci` needs the same setting. npm's current documentation calls out flags such as `--legacy-peer-deps` and `--install-links`.

Instead of remembering a custom CI command, commit the setting in a project `.npmrc`:

```ini
legacy-peer-deps=true
```

Then both commands read the same policy:

```bash
npm install
npm ci
```

Only add the setting your project actually needs. Do not copy `legacy-peer-deps=true` into every repository to silence a dependency problem you have not inspected.

## Fix common npm ci errors

### package.json and package-lock.json are out of sync

Run the normal resolver locally, review its changes, and commit the result:

```bash
npm install
npm ci
```

Do not delete the lockfile just to make CI green. That removes the dependency decision `npm ci` is supposed to enforce.

### A lockfile is missing

Generate and commit it:

```bash
npm install
git add package.json package-lock.json
```

### The lockfile was created with different peer-dependency settings

Re-run `npm install` with the intended setting, save it in the project's `.npmrc`, and commit both files. The automation and local resolver must use the same rules.

### Native packages still rebuild

Caching npm's cache directory avoids many downloads, but native add-ons may still compile when the Node version, operating system, architecture, or available prebuilt binary changes. Pin the Node version and keep the build environment consistent before blaming `npm ci`.

### CI needs only one workspace

`npm ci` supports npm workspace options. Keep one root lockfile and use the same workspace selection your other commands use:

```bash
npm ci --workspace=apps/web
```

The official [npm ci documentation](https://docs.npmjs.com/cli/v11/commands/npm-ci) is the source for the current lockfile, flag, and workspace behavior.

Related guides:

- [Compare Bun with pnpm and npm](/bun-package-manager)
- [Hide the npm funding message](/npm-fund)
- [Use Bun in a plain PHP project](/bun-php)
- [Swap npm for Bun in Laravel](/bun-laravel)
