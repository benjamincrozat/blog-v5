---
id: "01KKEW277GJQTNVBWPNXEGVYY7"
title: "Use Bun as Your Package Manager in Any Laravel Project"
slug: "bun-laravel"
author: "benjamincrozat"
description: "Enjoy a faster workflow to build your front-end dependencies in your Laravel projects, thanks to the package management abilities of Bun."
categories:
  - "javascript"
  - "laravel"
published_at: 2023-09-10T00:00:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/pq7TFpAhB0yAuVf.jpg"
sponsored_at: null
---
[Bun](https://bun.sh) is a fast JavaScript all-in-one toolkit that can be used as a package manager. I wrote about it in more detail: [What's the Fuss Around Bun's Package Manager Abilities?](https://benjamincrozat.com/bun-package-manager)

But first, let's see why you should care about Bun as a Laravel developer.

## Why switch away from NPM, pnpm, or Yarn?

Most Laravel developers don't use Node.js for anything other than compiling front-end assets. So, why would you take some time to switch to Bun instead of sticking with a regular Node.js runtime?

Well, if you actually test Bun, you'll notice how incredibly faster than Node.js it is. **Up to 30x faster!**

1. Your front-end dependencies will install faster.
2. Your assets will compile faster.
3. Your continuous integration environment will also run faster since installing and compiling front-end dependencies takes less time.

## Supercharge your macOS development with Bun and Homebrew

Installing Bun on macOS couldn't be easier. Just add the new source using `brew tap oven-sh/bun` and install Bun by running `brew install bun`.

## Turbocharge your Linux and WSL setup with Bun

Installing Bun on Linux is as easy as on macOS. Run `curl -fsSL https://bun.sh/install | bash`. That's it!

Linux users are recommended to make sure the unzip package is installed first. You should also be running the kernel in at least version 5.1, although version 5.6 or higher is a better choice.

## Windows users: Stay tuned for full Bun support

For now, unfortunately, Bun's package manager abilities are not fully available for Windows. But this shouldn't be a problem if you're running WSL.

There's currently an [experimental version](https://bun.sh/docs/installation#windows) for Windows, but it's not recommended for use in production.

## Prepare your project for Bun: Out with the old, in with the new

Laravel doesn't require a specific package manager, which is great news for Bun!

If you were using NPM or pnpm, remove their lock files because you won't need them anymore. Bun uses its own lock file called *bun.lockb* by default.

If you were using NPM:

```bash
rm package-lock.json
```

If you were using pnpm:

```bash
rm pnpm-lock.yaml
```

And if you were using Yarn:

```bash
rm yarn.lock
```

## Lightning-fast dependency installation with Bun

To install your dependencies using Bun, use `bun install`. You'll be amazed at how fast it is!

If you encounter any issues and want to disable the cache, use `bun install --no-cache`.

For additional information and options, please refer to the [official documentation of the `bun install` command](https://bun.sh/docs/cli/install).

![bun install in action.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/bun-laravel-5b693360c03cdefebee1.jpg/public)

## Add packages at warp speed with Bun

Adding a package using Bun is a breeze with the `bun add` command. You'll certainly appreciate how incredibly fast it is as well.

Here's an example with 3 packages:

```bash
bun add tailwindcss autoprefixer postcss
```

For additional information and options, please refer to the [official documentation of the `bun add` command](https://bun.sh/docs/cli/install#bun-add).

![bun add in action.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/bun-laravel-c58deeed47edc94b9356.jpg/public)

## Effortlessly remove packages with Bun

Removing a package using Bun is just as easy with the `bun remove` command. You'll be impressed by its speed here as well.

Let's use Axios as an example, as it's still installed by default on every new Laravel project:

```bash
bun remove axios
```

For additional information and options, please refer to the [official documentation of the `bun remove` command](https://bun.sh/docs/cli/install#bun-remove).

## Seamlessly run your scripts with Bun

Bun integrates effortlessly into your existing workflow. Run the scripts defined in your *package.json* file just like before, using `bun run`.

We can run our compilation process, which uses Vite or Mix by default on Laravel projects:

```bash
bun run dev
```

For additional information and options, please refer to the [official documentation of the `bun run` command](https://bun.sh/docs/cli/run).

If you are modernizing the frontend tooling around a Laravel app, these next reads cover the trade-offs and companion setup choices:

- [Use Bun in plain PHP projects too, not just Laravel](/bun-php)
- [See where Bun fits compared with npm, Yarn, and pnpm](/bun-package-manager)
- [Use npm ci when you need repeatable installs, not surprises](/npm-ci)
- [Add Alpine to Laravel when you just need lightweight interactivity](/alpine-js-laravel)
- [Add Vue to Laravel without overbuilding the frontend](/laravel-vue)
- [Add Tailwind to Laravel without setup guesswork](/tailwind-css-laravel)
