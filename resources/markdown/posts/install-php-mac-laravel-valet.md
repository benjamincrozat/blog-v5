---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/41/2794209_ckzoyp.png
Title: "PHP for Mac: get started fast using Laravel Valet"
Description: "Use your Mac as an ideal PHP environment thanks to the power of Laravel Valet. You can finally say goodbye to Docker!"
Published at: 2023-06-27
Modified at: 2023-08-12
Categories: php, laravel, tools
---

## Introduction

**Before we start, why don't you [take a look at Laravel Herd](https://benjamincrozat.com/laravel-herd) instead? It's an even simpler solution for people who have better things to do than messing with Homebrew and troubleshooting weird bugs.**

[Laravel Valet](https://laravel.com/docs/valet) is a minimalist's dream development environment for macOS.

It's a lightweight solution that's fast and uses around 7MB of RAM. 

Unlike Docker, Laravel Valet is pragmatic and has minimal impact on your Mac's resources.

With Laravel Valet, you don't have to manage the state of your containers, and you can work on an infinite amount of projects simultaneously since they're always available.

Laravel Valet uses a driver system that supports a wide range of platforms, such as Laravel, Bedrock, CakePHP 3, ConcreteCMS, Contao, Craft, Drupal, ExpressionEngine, Jigsaw, Joomla, Katana, Kirby, Magento, OctoberCMS, Sculpin, Slim, Statamic, Static HTML, Symfony, WordPress, and Zend out of the box.

You can also [extend Valet with your own custom drivers.](https://laravel.com/docs/10.x/valet#custom-valet-drivers)

## Install the Xcode Command Line Tools

Xcode Command Line Tools are a neat little collection of tools provided by Apple. They're super handy for developers who need to compile and debug applications from the terminal.

In our case, we won't directly use them. But Homebrew will! So let's get this out of the way before the next step:

```bash
xcode-select --install
```

## Install Homebrew

[Homebrew](https://brew.sh) is like a super cool genie in a bottle for Mac users. If you're already familiar with any Linux distribution, you will get this concept.

Homebrew is a non-official package manager that makes it ridiculously easy to install software on your Mac.

Instead of hunting down various files, struggling with dependencies, and wrestling with installations, you just tell Homebrew what you want, and it takes care of everything.

Run this command and it will automatically be installed on your Mac. Just follow the instructions that come next, it's super easy!

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

Instead of using the [official way to install Composer](https://getcomposer.org/doc/00-intro.md), we'll let Homebrew do all the work. It's much quicker!

```bash
brew install composer
```

![The official website of Composer.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/142/conversions/CleanShot_2023-06-27_at_12.59.00_2x_cgxb8m-medium.jpg)

## Make the global Composer packages available without their full path

To add Composer's global packages binaries into your `PATH`, you can modify your shell's configuration file.

If you're using a bash shell, this is usually the *.bashrc* or *.bash_profile* file, and for [Zsh](https://ohmyz.sh) users, it's the *.zshrc* file.

Here's a simple step-by-step guide:

- Open your terminal.
- Type `nano ~/.bash_profile` if you're using Bash, or `nano ~/.zshrc` for Zsh. This will open your shell's configuration file in a text editor called nano.
- Add this line to the file: `export PATH="$PATH:$HOME/.composer/vendor/bin"`
- Save and exit by pressing ctrl+X, then Y to confirm saving, and finally Enter to use the same filename.
- Finally, reload your shell's configuration by typing `source ~/.bash_profile` or `source ~/.zshrc`.

Now, why does it mean and why do we need this? When you install packages globally with Composer, their binaries are placed in the `$HOME/.composer/vendor/bin` directory.

By adding this directory to your `PATH` (a simple environment variable), you're telling your shell *"Hey, when I type a command, also look in this directory to see if you can find it."*

It's a way to easily use the commands provided by the packages you install with Composer, without having to type the full path each time. Super convenient, right?
		
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

When everything works correctly, Laravel Valet redirects any .test domain to your local Nginx server.

## Allow Laravel Valet to be run without admin privileges

What's annoying when using Laravel Valet frequently is that it's always asking you for your password.

If you are willing to trust it with the safesty of your Mac, run the following command and be done with passwords!

```bash
valet trust
```

## Park Laravel Valet in your projects' folder

Next, you'll want to direct Valet to 'park' in your projects' directory. This means Valet will automatically serve all projects the chosen directory:

```bash
cd /path/to/projects/folder

valet park
```

![A terminal showing how to add a folder to Laravel Valet with the command `valet park`.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/143/conversions/CleanShot_2023-06-27_at_13.02.42_2x_gcnqpz-medium.jpg)

## Serve a specific project no matter where it is

If you have a project in a random folder, you can also serve it without serving the whole folder.

```bash
valet link /path/to/project
```

## Serve a project over HTTPS with TLS

Being able to serve local projects over HTTPS has several advantages. As someone who also develop native apps for the Apple ecosystem, I can think of serving secure local REST APIs for local iOS apps development, since Xcode doesn't like it when they're not.

```bash
valet secure /path/to/project
```

Whenever you need to, you can unsecure your project:

```bash
valet unsecure /path/to/project
```

## Switch the version of PHP

With Laravel Valet, you are not restricted to just the latest version of PHP. You can also use older versions depending on your needs.

For instance, if you are still working with PHP 8.1, you can install it as well:

```bash
brew install php@8.1
```

And switch back in forth whenever you need it.

```bash
valet use php@8.1
```

## Per-project PHP version

Some projects are more modern than others. This is why it's useful to be able to serve projects with different versions of PHP. Luckily, Valet makes it super easy thanks to the following command:

```bash
valet isolate /path/to/project
```

## Install a database alongside Laravel Valet

Databases can also be installed with Homebrew. You can even install multiple versions, just like with PHP. 

```bash
brew install mysql mysql@5.7 postgresql sqlite
```

Alternatively, you can use [DBngin](https://dbngin.com), a **free** database management tool and the easiest way to get started with PostgreSQL, MySQL, Redis & more.

It can even manage the software that you installed via Homebrew. 👌

![DBngin](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/144/conversions/CleanShot_2023-09-03_at_17.27.44_2x_nwqszy-medium.jpg)

---

So now, what about installing Laravel itself? Here's my guide: [How to install Laravel on macOS](/laravel-installation-macos)
