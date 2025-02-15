---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/65/cBfXt4if95bYJwmHQIcNRiBI7mpJaa-metaYnVuLmpwZw%3D%3D-.jpg
Title: "Use Bun as your package manager in any PHP project"
Description: "Enjoy a faster workflow to build your front-end dependencies in your PHP projects, thanks to the package management abilities of Bun."
Published at: 2023-09-10
Categories: php, javascript
---

[Bun](https://bun.sh) is a fast JavaScript all-in-one toolkit which can be used as a package manager. I wrote about it in more details: [What's the fuss around Bun's package manager abilities?](https://benjamincrozat.com/bun-package-manager)

But first, let's see why you should care about Bun as a PHP developer.

## Why would you switch away from NPM, pnpm, or Yarn?

Most PHP developers don't use Node.js for anything other than compiling front-end assets. So, why would you take some time to switch to Bun instead of sticking with a regular Node.js runtime?

Well, if you actually test Bun, you will notice how incredibly faster than Node.js it is. **Up to 30x!**

1. Your front-end dependencies will install faster.
2. Your assets will compile faster.
3. Your continuous integration environment will also run faster since installing and compiling front-end dependencies takes less time.

## How to install Bun on macOS using Homebrew

Installing Bun on macOS couldn't be easier. Just add the new source using `brew tap oven-sh/bun` and install Bun by running `brew install bun`.

## How to install on Linux and WSL

Installing Bun on Linux is as easy as on macOS. Run `curl -fsSL https://bun.sh/install | bash`. That's it!

Linux users are recommended to make sure the unzip package is installed first. You should also be running the kernel in at least version 5.1, even if version 5.6 or higher is a better choice.

## How to install Bun on Windows

For now, unfortunately, Bun's package manager abilities are not available for Windows. But this shouldn't be a problem if you are running WSL.

There's currently an [experimental version](https://bun.sh/docs/installation#windows) for it, but it's not recommended to use it in production.

## Make some room to replace NPM, Yarn, or pnpm with Bun 

The package manager you use in your PHP projects is completely up to your preferences. Which is great news for Bun!

If you were using NPM or pnpm, remove their lock files because you won't need them anymore since Bun uses its own lock file called *bun.lockb* by default.

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

## Install your front-end dependencies using Bun

To install your dependencies using Bun, use `bun install`. So, how fast was it? I bet you didn't expect that!

Oh and by the way, in case of a problem, if you want to disable the cache, use `bun install --no-cache`.

For additional information and options, please refer to the [official documentation of the `bun install` command](https://bun.sh/docs/cli/install).

![bun install in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/69/conversions/ybYunPr2RMZPLO3vFaMImsaecxEEnz-metaQ2xlYW5TaG90IDIwMjMtMDktMTEgYXQgMDkuMDAuMDJAMnguanBn--medium.jpg)

## Add a package using Bun

Adding a package using Bun can easily be done using the `bun add` command. You will certainly appreciate how incredibly fast it is as well.

Here's an example with 3 packages:

```bash
bun add tailwindcss autoprefixer postcss
```

For additional information and options, please refer to the [official documentation of the `bun add` command](https://bun.sh/docs/cli/install#bun-add).

![bun add in action.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/70/UndzC78D9Uclf8eMBlPr0yN2wbhXRy-metaQ2xlYW5TaG90IDIwMjMtMDktMTEgYXQgMDkuMDIuMTFAMnguanBn-.jpg)

## Remove a package using Bun

Removing a package using Bun can easily be done using the `bun remove` command. You will certainly appreciate how incredibly fast it is as well.

Let's use Axios as an example, since JavaScript's native Fetch API is as easy to use:

```bash
bun remove axios
```

For additional information and options, please refer to the [official documentation of the `bun remove` command](https://bun.sh/docs/cli/install#bun-remove).

## Run your scripts using Bun

Bun should be able to be integrated into your existing workflow without any issues. Run the scripts defined in your *package.json* file just like before using `bun run`.

For instance, you might have a compilation process in your PHP process for front-end assets:

```bash
bun run dev
```

For additional information and options, please refer to the [official documentation of the `bun run` command](https://bun.sh/docs/cli/run).
