---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/3541cfb6-74ea-4b08-b0e7-6a7dd77d733d
Title: "Laravel 12: an early look and release date"
Description: "Laravel 12 will be released early 2025. Let's put our investigator hat and see what we can find out about this new major version."
Published at: 2024-03-11
Modified at: 2024-06-25
Categories: laravel
---

## When will Laravel 12 be released?

According to the [Support Policy](https://laravel.com/docs/11.x/releases#support-policy), **Laravel 12 is scheduled to be released during Q1 of 2025**.

The release of Laravel 12 doesn't mean you must update all your projects immediately, though.

The framework last had LTS (Long-Term Support) in version 6, but **each major version has two years of updates**, which should give you enough time to get your codebase in check and upgrade it.

Laravel 11 will receive bug fixes until September 3, 2025 and security fixes until March 12, 2026.

| Version | PHP | Release | Bug fixes until | Security fixes until |
| ------- | --- | ------- | --------------- | -------------------- |
| 11 | 8.2-8.3 | March 12, 2024 | September 3, 2025 | March 12, 2026 |
| 12 | 8.2-8.3 | Q1 2025 | Q3 2026 | Q1 2027 |

## Install and test Laravel 12 right now

Laravel 12 hasn't been released yet. Therefore, you must use the `--dev` flag on the official Laravel installer, which pulls the *main* branch from the [laravel/laravel](https://github.com/laravel/laravel) repository that always contains the latest code.

```bash
laravel new hello-world --dev
```

Or, if you prefer to use Composer explicitly:

```bash
composer create-project --prefer-dist laravel/laravel hello-world dev-master
```

## What's new in Laravel 12: features and changes

For now, there's nothing clearly defined. But the Laravel team prepares the next major release of Laravel as soon as the last one is ready. For instance, they create a branch for every Illuminate repository and make minor (but breaking) changes all over the codebase:
- [[12.x] Prep Laravel v12](https://github.com/laravel/framework/pull/50406/files)
- [[12.x] Make Str::is() match multiline strings](https://github.com/laravel/framework/pull/51196)
- [[12.x] Use native MariaDB CLI commands](https://github.com/laravel/framework/pull/51505)
- [[12.x] Preserve numeric keys on the first level of the validator rules](https://github.com/laravel/framework/pull/51516)
- [[12.x] Adds missing streamJson() to ResponseFactory contract](https://github.com/laravel/framework/pull/51544)

## How to contribute your own breaking changes to Laravel 12

Did you know you can create the next big feature for Laravel 12?

1. See what's going on for Laravel 12 on GitHub: https://github.com/laravel/framework/pulls. The Pull Requests will tell you what's already been done.
2. Take one of your pain points with the framework and create a solution yourself.
3. Send the PR over to the laravel/framework repository, collect feedback, improve, and get merged.

One important tip to increase your chances of being merged: add something to the framework that's a win for developers but not a pain to maintain for Taylor and his team in the long run.

![Pull requests on the laravel/framework repository.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/44dfb5ba-e11a-45a2-be93-bd689bfe891e)
