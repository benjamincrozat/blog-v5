---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/28/laravel-10-upgrade-guide_myjbgv.png
Title: "Laravel 10: the upgrade guide from version 9"
Description: "I show you how to upgrade your Laravel 9 project to version 10 and help you decide whether the return on investment is worth it."
Published at: 2023-02-03
Modified at: 2023-11-02
Categories: laravel
---

## Introduction

Upgrading your Laravel applications to the latest version has many benefits like:
- Reduced bugs.
- Reduced security risks.
- Increased compatibility with first and third-party packages.

## Before upgrading from Laravel 9 to Laravel 10, learn what's new

Major releases of Laravel have been smaller and smaller over the years.

But Laravel 10 still has plenty of changes, though, and I wrote about them: [Laravel 10 is out! Here's every new feature and change.](https://benjamincrozat.com/laravel-10)

## Do you really need to upgrade?

At the time I'm writing these lines, **Laravel 9 is still alive** and will have:
- ~~Bug fixes until August 8th, 2023~~
- ~~Security fixes until February 6th, 2024~~

That being said, **is the return on investment still worth it**?

My recommendations is to upgrade only if:
- The project is still in active development.
- You need a package that only supports Laravel 10.
- Your project is well-tested, and the transition will be easy.

And do not upgrade if:
- The project didn't have any new code written for it recently.
- Your project is in a precarious state due to the lack of tests.
- Or it is in a stable state. Upgrading for the sake of upgrading is not the best way to invest your time and energy.

If you consider upgrading it after reading this, go for it! It's probably the right decision.

But if you're taking the cautious approach, take the two years of support the team offers for Laravel 9 to fix your project first.

![Laravel Upgrade guide](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/130/conversions/Screenshot_2023-02-03_at_11.00.57_sruzij-medium.jpg)

## How to upgrade from Laravel 9 to Laravel 10?

First, **if you are still running Laravel 8, you must [upgrade to Laravel 9](https://benjamincrozat.com/laravel-9-upgrade-guide)**. It's theoretically possible to upgrade from 8 to 10, but I wouldn't recommend it. Proceed one version at a time.

### Meet the requirements

- PHP 8.1+ with the following extensions:
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

As you can see, Laravel 10 doesn't require anything fancy to work properly.

### Make sure third-party Composer packages support Laravel 10

Do the third-party packages you used to build your project support Laravel 10?

To answer this question, go to [Packagist](https://packagist.org), enter the name of the package, and see if it received a recent update. In the list of dependencies, you should see `"ìlluminate/something": "^10.0"`.

Let's take [spatie/laravel-permissions](https://packagist.org/packages/spatie/laravel-permission), for instance, which is one of the most popular package for Laravel.

In the screenshot below, we can see it supports Laravel 7, 8, 9, and 10.

![spatie/laravel-permission](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/131/conversions/Screenshot_2023-02-04_at_18.16.02_nvvzfb-medium.jpg)

If you cannot see any indication that your packages have been upgraded, consider waiting a bit before upgrading your project.

Some people, like me, aren't that patient, though.

Luckily, adding Laravel 10 support for a Composer package is most of the time a matter of changing the *illuminate/something* version constraints in *composer.json*, because there aren't many breaking changes between major updates these days:

```diff
{
    "require": {
        …
-        "illuminate/support": "^9.0",
+        "illuminate/support": "^10.0",
        …
    }
}
```

You could create a Pull Request to help open source maintainers, or fork the package and do the change yourself until the author finds time.

### Follow the upgrade guide

There's no trick in upgrading a Laravel app. It only takes you to follow the instructions in the [upgrade guide](https://laravel.com/docs/10.x/upgrade).

These instructions are usually the following:
1. Make sure your version of PHP meets the minimal requirements.
2. Change the minimum version for a given dependency.
3. Change a line in a given file.
4. A method changed its parameters or return value. Check if you're using it and make the necessary changes.
5. A previously deprecated class or method has been removed, use something else (the guide always gives you alternatives).

Depending on the size of your codebase, how well-tested it is and even the number of projects you have to upgrade, this can be a time-consuming task.

Fortunately, you will see below how ingenuity can help you upgrade Laravel projects almost automatically and simplify your web developer life.

## Laravel Shift: upgrade as many projects to Laravel 10 as you want with a few clicks

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
- Upgrade **from Laravel 5.0 up to Laravel 10**.
- Migrate **from Tailwind CSS 0.x up to 3.x**.
- Migrate **from PHPUnit to Pest**.
- And more!

The pricing is straightforward and extremely generous considering how expensive paying developers is:
- **$99** per year to run as many Shifts as you want on your project
- **$5-$29** per Shift

