---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/abd33b35-a318-41e7-859c-7dab1e8010fe
Title: "How to install Laravel on macOS"
Description: "A step-by-step guide to install Laravel on macOS using Composer or the official Laravel installer."
Published at: 2024-01-15
Categories: laravel, tools
---

## Introduction to installing Laravel on macOS

So, you finally decided to give [Laravel](https://laravel.com) a try.

But how do you even get started? Well, first, you must have a working PHP environment (see how to [install PHP on macOS](https://benjamincrozat.com/install-php-mac-laravel-valet)). Then, you must create a new Laravel project using the official Laravel installer or [Composer](https://getcomposer.org) (the package manager every PHP developer uses to manage dependencies).

Feels overwhelming? Don't worry. In this article, I'll show you exactly how to do that.

## How to install Laravel on macOS

### Using Composer

To install Laravel on macOS, Linux, or WSL, you can create a new project using Composer like so (given you already have a [working PHP environment](https://benjamincrozat.com/install-php-mac-laravel-valet)):

```bash
composer create-project laravel/laravel example-app
```

Then, you can just `cd` into your project and start the development server:

```bash
php artisan serve
```

Finally, open http://localhost:8000 in your browser.

But installing Laravel using Composer is limited and I will show you a better way.

### Using the official Laravel installer

Laravel offers a convenient installer that you can add to Composer's global packages using the following command:

```bash
composer global require laravel/installer
```

Then, you can create a new project:

```bash
laravel new example-app
```

**If this command doesn't work, make sure you followed [my guide](https://benjamincrozat.com/install-php-mac-laravel-valet) correctly.**

The Laravel installer even offers options to create a new initialized Git repository with a first commit without having to go through the various prompts:

```bash
laravel new example-app --git
```

Or even use Pest as a default test runner:

```Bash
laravel new example-app --pest
```

And you can combine these:

```bash
laravel new example-app --git --pest
```

There are a ton of options and you can see them all by running `laravel new --help`:

```
Description:
  Create a new Laravel application

Usage:
  new [options] [--] <name>

Arguments:
  name                             

Options:
      --dev                        Installs the latest "development" release
      --git                        Initialize a Git repository
      --branch=BRANCH              The branch that should be created for a new repository [default: "main"]
      --github[=GITHUB]            Create a new repository on GitHub [default: false]
      --organization=ORGANIZATION  The GitHub organization to create the new repository for
      --stack[=STACK]              The Breeze / Jetstream stack that should be installed
      --breeze                     Installs the Laravel Breeze scaffolding
      --jet                        Installs the Laravel Jetstream scaffolding
      --dark                       Indicate whether Breeze or Jetstream should be scaffolded with dark mode support
      --typescript                 Indicate whether Breeze should be scaffolded with TypeScript support (Experimental)
      --ssr                        Indicate whether Breeze or Jetstream should be scaffolded with Inertia SSR support
      --api                        Indicates whether Jetstream should be scaffolded with API support
      --teams                      Indicates whether Jetstream should be scaffolded with team support
      --verification               Indicates whether Jetstream should be scaffolded with email verification support
      --pest                       Installs the Pest testing framework
      --phpunit                    Installs the PHPUnit testing framework
```
