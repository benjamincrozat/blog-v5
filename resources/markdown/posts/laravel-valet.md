---
id: "01KKEW27E5J81QZXT2KP8KNGMY"
title: "PHP for Mac: get started fast using Laravel Valet"
slug: "laravel-valet"
author: "benjamincrozat"
description: "Learn how to install and use Laravel Valet on macOS with Homebrew, PHP, and Composer to create a fast local PHP environment without Docker."
categories:
  - "laravel"
  - "php"
  - "tools"
published_at: 2023-06-27T00:00:00+02:00
modified_at: 2025-09-28T06:06:00+02:00
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/Z722VhKaUg9HUy8.png"
sponsored_at: null
---
## Introduction

**Before we start, why don't you [take a look at Laravel Herd](https://benjamincrozat.com/laravel-herd) instead? It's an even simpler solution for people who have better things to do than messing with Homebrew and troubleshooting weird bugs. If you prefer a one-click setup, see the [Herd documentation](https://herdphp.com/docs/1/getting-started/installation) too.**

[Laravel Valet](https://laravel.com/docs/valet) is a minimalist's dream development environment for macOS.

It's a lightweight solution that's fast and has very low overhead compared to VMor container-based stacks like Docker.

Unlike Docker, Laravel Valet is pragmatic and has minimal impact on your Mac's resources.

With Laravel Valet, you don't have to manage the state of your containers, and you can work on many projects at once since they're always available.

Valet ships with drivers for many popular frameworks and CMSs. You can also [extend Valet with your own custom drivers.](https://laravel.com/docs/10.x/valet#custom-valet-drivers)

It works on Intel and Apple Silicon (M1/M2/M3) Macs and uses Nginx and dnsmasq under the hood.

## Install the Xcode Command Line Tools

Xcode Command Line Tools are a neat little collection of tools provided by Apple. They're super handy for developers who need to compile and debug applications from the terminal.

In our case, we won't directly use them. But Homebrew will! So let's get this out of the way before the next step:

```bash
xcode-select --install
```

The Command Line Tools are enough for this guide. You do not need to install the full Xcode app.

## Install Homebrew

[Homebrew](https://brew.sh) is like a helpful package manager for Mac users. If you're already familiar with any Linux distribution, you will get this concept.

Homebrew is an unofficial package manager that makes it easy to install software on your Mac.

Instead of hunting down various files, struggling with dependencies, and wrestling with installations, you just tell Homebrew what you want, and it takes care of everything.

Run this command and it will automatically be installed on your Mac. Just follow the instructions that come next, it's easy.

```bash
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"
```

![The official website for Homebrew.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/141/conversions/CleanShot_2023-06-27_at_12.55.42_2x_tbjj8v-medium.jpg)

## Install the latest version of PHP

With Homebrew installed, you can now also install the latest version of PHP on your system using the following command:

```bash
brew install php
```

Laravel Valet is built on top of PHP, so this is a mandatory step.

## Install Composer

Instead of using the [official way to install Composer](https://getcomposer.org/doc/00-intro.md), we'll let Homebrew do all the work. It's quicker.

```bash
brew install composer
```

![The official website of Composer.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/142/conversions/CleanShot_2023-06-27_at_12.59.00_2x_cgxb8m-medium.jpg)

## Add Composer's global bin to PATH

To add Composer's global package binaries into your PATH, first ask Composer for the exact directory:

```bash
composer global config bin-dir --absolute
```

Then add that path to your shell config so you can run global Composer tools by name:

- Open your terminal.
- Edit your shell file: `nano ~/.bash_profile` for Bash or `nano ~/.zshrc` for Zsh.
- Add a line like: `export PATH="$PATH:/the/path/you/got/from/composer"`
- Save and exit (Ctrl+X, then Y, then Enter).
- Reload your shell: `source ~/.bash_profile` or `source ~/.zshrc`.

Now, what does it mean and why do we need this? When you install packages globally with Composer, their binaries live in that directory. By adding it to your PATH, you're telling your shell to look there when you type commands. It's a simple way to use those tools without typing the full path each time.

## Install Laravel Valet

Now that we have Composer installed, we can install Laravel Valet as a global package:

```bash
composer global require laravel/valet
```

And thanks to the previous step, we can now do this to complete the installation:

```bash
valet install
```

Then, make sure it works by running the following command:

```bash
ping foo.test
```

And you should see something like this:

```
PING foo.test (127.0.0.1): 56 data bytes
64 bytes from 127.0.0.1: icmp_seq=0 ttl=64 time=0.081 ms
64 bytes from 127.0.0.1: icmp_seq=1 ttl=64 time=0.139 ms
64 bytes from 127.0.0.1: icmp_seq=2 ttl=64 time=0.254 ms
```

When everything works correctly, Laravel Valet redirects any .test domain to your local Nginx server using dnsmasq.

Common Valet commands you may need:

- `valet links` lists linked sites. `valet paths` lists parked folders.
- `valet restart` restarts Nginx, PHP, and dnsmasq.
- `valet proxy` and `valet proxies` manage proxies if you need to route a domain to another local port (for example, a Node server).
- `valet tld` shows or changes the top-level domain (default is `test`).
- `valet diagnose` prints a report to help you fix problems.

## Allow Laravel Valet to be run without admin privileges

What's annoying when using Laravel Valet frequently is that it's always asking you for your password.

If you are willing to trust it with the safety of your Mac, run the following command and be done with passwords:

```bash
valet trust
```

This adds sudoers rules so Valet and Homebrew services can run without password prompts. Only run it on a machine you trust.

## Park Laravel Valet in your projects' folder

Next, you'll want to direct Valet to "park" in your projects' directory. This means Valet will automatically serve all projects in the chosen directory:

```bash
cd /path/to/projects/folder

valet park
```

Tip: run `valet paths` anytime to see which folders are parked. You can also change the TLD (for example, from `.test` to `.dev`) with `valet tld dev`.

![A terminal showing how to add a folder to Laravel Valet with the command `valet park`.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/143/conversions/CleanShot_2023-06-27_at_13.02.42_2x_gcnqpz-medium.jpg)

## Link a single project

If you have a project in a random folder, you can also serve it without serving the whole folder.

```bash
cd /path/to/project
valet link
```

Or from inside the project, set a custom name:

```bash
valet link mysite
```

List and remove links with:

```bash
valet links
valet unlink mysite
```

## Serve a project over HTTPS with TLS

Being able to serve local projects over HTTPS has several advantages. As someone who also develops native apps for the Apple ecosystem, I can think of serving secure local REST APIs for iOS app development. iOS App Transport Security (ATS) enforces HTTPS by default, so using HTTPS locally avoids extra setup.

Secure a site by name or from inside the project directory:

```bash
valet secure mysite
# or, inside the project directory
valet secure
```

Whenever you need to, you can unsecure your project:

```bash
valet unsecure mysite
```

Valet manages certificates under `~/.config/valet`. After some macOS upgrades, Keychain may ask you to trust new certs.

## Switch PHP versions

With Laravel Valet, you are not restricted to just the latest version of PHP. You can install and use other maintained versions as needed.

For example, install PHP 8.3:

```bash
brew install php@8.3
```

And switch back and forth whenever you need:

```bash
valet use php@8.4
```

## Per-site PHP versions (isolate)

Some projects are more modern than others. This is why it's useful to be able to serve projects with different versions of PHP. Luckily, Valet makes it easy with the `isolate` command:

```bash
cd /path/to/project
valet isolate php@8.2
```

Optionally target a site by name:

```bash
valet isolate php@8.2 --site="mysite"
```

To revert to the global PHP version:

```bash
valet unisolate
```

## Install a database alongside Laravel Valet

Databases can also be installed with Homebrew. You can even install multiple versions, just like with PHP.

```bash
brew install mysql postgresql redis sqlite
```

Need specific versions? Try:

```bash
brew install mysql@8.4 postgresql@17
# or use `brew search mysql@` and `brew search postgresql@` to see what is available on your system
```

Alternatively, you can use [DBngin](https://dbngin.com), a free database management tool and an easy way to get started with PostgreSQL, MySQL, Redis & more.

It can even manage the software that you installed via Homebrew. 👌

![DBngin](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/144/conversions/CleanShot_2023-09-03_at_17.27.44_2x_nwqszy-medium.jpg)

So now, what about installing Laravel itself? Here's my guide: [How to install Laravel on macOS](/laravel-installation-macos)

## Troubleshooting

- If something stops working, run `valet restart`.
- Make sure `.test` domains resolve to `127.0.0.1` with `ping foo.test`. If not, check that dnsmasq is running and try `valet install` again.
- Run `valet diagnose` and read the report for common fixes.
- If Nginx or PHP looks stuck, `brew services list` can show which services are running.

## Conclusion

You now have PHP, Composer, and Valet installed on macOS. You can park a folder to serve many projects or link a single project anywhere. You can secure sites with HTTPS, switch PHP versions, and even isolate a project to a specific PHP version. When you're ready, continue with my next step: [install Laravel on macOS](/laravel-installation-macos).

If you are still shaping the local Mac setup around your PHP work, these are the next reads I would keep handy:

- [How to install Laravel on macOS](/laravel-installation-macos)
- [Laravel Herd: the simplest way to install PHP on macOS and Windows](/laravel-herd)
- [6 ways to check your version of PHP](/check-php-version)
- [6 ways to check Laravel's version](/check-laravel-version)
- [Laravel Forge: price, review and alternatives (2025)](/laravel-forge)
