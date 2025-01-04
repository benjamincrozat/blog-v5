---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/b2e6b3f7-0663-4357-b5f9-47f5deed33e2
Title: "Laravel 11: an easy and step by step upgrade guide"
Description: "Is upgrading to Laravel 11 worth it? Let me help you decide, show you how to proceed step by step, and give you additional tips."
Published at: 2024-03-12
Categories: laravel
---

## Introduction

Upgrading your Laravel applications to the latest version has many benefits like:
- Reduced bugs.
- Reduced security risks.
- Increased compatibility with first and third-party packages.

Let me guide you and give you tips you won't find in the official upgrade guide.

## Before upgrading from Laravel 10 to Laravel 11, learn what's new

Laravel 11 has plenty of changes and I wrote about them: [Laravel 11 is out! Here's every new feature and change.](https://benjamincrozat.com/laravel-11)

## Do you really need to upgrade?

At the time I'm writing these lines, **Laravel 10 is still alive** and will have:
- **Bug fixes until August 6th, 2024**
- **Security fixes until February 4th, 2025**

My recommendation is to upgrade only if:
- The project is still in active development and there's a clear potential return on investment.
- You need a package that only supports Laravel 11.
- Your project is well-tested, and the transition will be easy.

And do not upgrade if:
- The project didn't have any new code written for it recently.
- Your project is in a precarious state due to the lack of tests.
- Or it is in a stable state. Upgrading for the sake of upgrading is not the best way to invest your time and energy.

If you still consider upgrading it after reading this, go for it! It's probably the right decision.

But if you're taking the cautious approach, take the two years of support the team offers for Laravel 10 to fix your project first.

## How to upgrade from Laravel 10 to Laravel 11?

First, **if you are still running Laravel 9, you must [upgrade to Laravel 10](https://benjamincrozat.com/laravel-10-upgrade-guide)**. It's theoretically possible to upgrade from 9 to 11, but I wouldn't recommend it. Proceed one version at a time.

### Meet the requirements

- PHP 8.2+ with the following extensions:
  - Ctype
  - cURL
  - DOM
  - Fileinfo
  - Filter
  - Hash
  - Mbstring
  - OpenSSL
  - PCRE
  - PDO
  - Session
  - Tokenizer
  - XML
- Nginx or Apache

As you can see, Laravel 11 doesn't require anything fancy to work properly.

### Make sure third-party Composer packages support Laravel 11

Do the third-party packages you used to build your project support Laravel 11?

To find out, run the following command:

```bash
composer why-not laravel/framework 11.0
```

If some packages show up, run:

```bash
composer outdated --direct
```

This way, you will see which packages have a major version waiting for you.

But obviously, some packages may not support Laravel 11 yet. You can either contribute to them, or look for alternatives.

### Follow the official upgrade guide

There's no trick in upgrading a Laravel app. It only takes you to follow the instructions in the [upgrade guide](https://laravel.com/docs/11.x/upgrade).

These instructions are usually the following:
1. Make sure your version of PHP meets the minimal requirements.
2. Change the minimum version for a given dependency.
3. Change a line in a given file.
4. A method changed its parameters or return value. Check if you're using it and make the necessary changes.
5. A previously deprecated class or method has been removed, use something else (the guide always gives you alternatives).

Depending on the size of your codebase, how well-tested it is and even the number of projects you have to upgrade, this can be a time-consuming task.

Fortunately, you will see below how ingenuity can help you upgrade Laravel projects almost automatically and simplify your web developer life.

## Laravel Shift: upgrade as many projects to Laravel 11 as you want with a few clicks

If you're a professional developer, you might have tons of clients who want to be on the latest version of Laravel.

Upgrading can be easy on clean and tested projects, but it can also be a nightmare for others.

This is why a fellow Laravel developer, Jason McCreary, invented [Laravel Shift](https://laravelshift.com?utm_campaign=laravel-10-upgrade-guide&utm_source=benjamincrozat.com&utm_medium=blogpost&utm_content=textlink)

![Laravel Shift](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/132/conversions/Screenshot_2023-02-03_at_10.55.36_ccqoia-medium.jpg)

It's effortless:
1. **Sign in** with GitHub, BitBucket, or GitLab.
2. **Choose a Shift** and enter your repository details.
3. **Receive a Pull Request** full of atomic commits for review.

Under the hood, Laravel Shift will upgrade your dependencies and make the necessary changes described in the official upgrade guide.

Here are some of the [available Shifts](https://laravelshift.com/shifts?utm_campaign=laravel-10-upgrade-guide&utm_source=benjamincrozat.com&utm_medium=blogpost&utm_content=textlink) (paid and free). You will also see that Laravel Shift can do more than just upgrade Laravel (which already was a considerable feat):
- Migrate **from Laravel Mix to Vite** (this one is **free**, so try it!).
- **Lint your Laravel project** to see if you're doing things the "Laravel way" (it's also **free**).
- Upgrade **from Laravel 5.0 up to Laravel 11**.
- Migrate **from Tailwind CSS 0.x up to 3.x**.
- Migrate **from PHPUnit to Pest**.
- And more!

The pricing is straightforward and extremely generous considering how expensive paying developers is:
- **$99** per year to run as many Shifts as you want on your project
- **$5-$29** per Shift
