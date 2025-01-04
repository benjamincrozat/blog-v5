---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/68/NZbLqdyKKjVodLp6Yesr1iBA50Vk3K-metacGFja2FnZS1tYW5hZ2VyLmpwZw%3D%3D-.jpg
Title: "Bun: The Lightning-Fast Alternative to NPM, Yarn, and pnpm"
Description: "Discover why Bun is revolutionizing package management in JavaScript. Learn how it outperforms NPM, Yarn, pnpm, and others in speed and efficiency."
Published at: 2023-09-11
Categories: javascript
---

## What is Bun?

**[Bun](https://bun.sh) is a blazing-fast JavaScript all-in-one toolkit.** It serves as a runtime (a drop-in replacement that's significantly faster than Node.js), a test runner, and even a package manager – which is our focus today.

Unlike Node.js or Deno, Bun is built on top of [WebKit](https://webkit.org)'s JavaScript engine (JavaScriptCore). WebKit forms the foundation for Mobile Safari on Apple's mobile platforms, as well as Safari for Mac.

## Why switch from NPM, pnpm, or Yarn to Bun?

You might wonder why you should invest time in switching to Bun instead of sticking with your familiar Node.js runtime. The answer lies in Bun's incredible performance.

When you test Bun, you'll immediately notice how astonishingly faster it is compared to Node.js. **We're talking up to 30 times faster!** This speed boost translates to:

1. Lightning-fast installation of front-end dependencies.
2. Significantly quicker asset compilation.
3. Accelerated continuous integration environments, as installing and compiling front-end dependencies takes a fraction of the time.

## How to install Bun on macOS using Homebrew

Installing Bun on macOS is a breeze. Simply add the new source and install Bun using these two commands:

```bash
brew tap oven-sh/bun
brew install bun
```

## How to install on Linux and WSL

Installing Bun on Linux is just as straightforward as on macOS. Run this single command:

```bash
curl -fsSL https://bun.sh/install | bash
```

Linux users should ensure the `unzip` package is installed first. It's also recommended to run kernel version 5.1 or higher, with version 5.6+ being the optimal choice for the best experience.

## How to install Bun on Windows

Currently, Bun's package manager capabilities are not available natively for Windows. However, this shouldn't be an issue if you're using Windows Subsystem for Linux (WSL).

There's an [experimental version](https://bun.sh/docs/installation#windows) available for Windows, but it's not recommended for production use at this time.

## Preparing to replace NPM, Yarn, or pnpm with Bun 

The beauty of package managers is that you're not locked into a specific one. This flexibility is great news for Bun!

If you're transitioning from NPM or pnpm, you'll need to remove their lock files as Bun uses its own lock file called *bun.lockb* by default.

For NPM users:

```bash
rm package-lock.json
```

For pnpm users:

```bash
rm pnpm-lock.yaml
```

And for Yarn users:

```bash
rm yarn.lock
```

## Installing dependencies with Bun's package management

To install your dependencies using Bun, simply run:

```bash
bun install
```

You'll be amazed at how quickly it completes the task!

If you encounter any issues and need to bypass the cache, use:

```bash
bun install --no-cache
```

For more detailed information and additional options, consult the [official documentation of the `bun install` command](https://bun.sh/docs/cli/install).

![bun install in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/69/conversions/ybYunPr2RMZPLO3vFaMImsaecxEEnz-metaQ2xlYW5TaG90IDIwMjMtMDktMTEgYXQgMDkuMDAuMDJAMnguanBn--medium.jpg)

## Adding packages with Bun

Adding packages with Bun is a breeze using the `bun add` command. You'll appreciate its incredible speed here as well.

Here's an example of adding multiple packages:

```bash
bun add tailwindcss autoprefixer postcss
```

For more information and options, refer to the [official documentation of the `bun add` command](https://bun.sh/docs/cli/install#bun-add).

![bun add in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/70/UndzC78D9Uclf8eMBlPr0yN2wbhXRy-metaQ2xlYW5TaG90IDIwMjMtMDktMTEgYXQgMDkuMDIuMTFAMnguanBn-.jpg)

## Removing packages with Bun

Removing packages with Bun is just as simple, using the `bun remove` command. The speed here is equally impressive.

Let's use Axios as an example, since we can now use the native Fetch API:

```bash
bun remove axios
```

For additional details and options, check out the [official documentation of the `bun remove` command](https://bun.sh/docs/cli/install#bun-remove).

## Running scripts with Bun

Bun seamlessly integrates into your existing workflow. Run the scripts defined in your *package.json* file just like before, using `bun run`.

For instance, if you're using Vite for your compilation process:

```bash
bun run dev
```

For more information and options, consult the [official documentation of the `bun run` command](https://bun.sh/docs/cli/run).

## Bun's video presentation

Get a visual introduction to Bun's capabilities with this official video:

https://www.youtube.com/watch?v=BsnCpESUEqM
