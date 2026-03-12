---
id: "01KKEW27CZY9FC491NKDCWRR72"
title: "Fix \"No application encryption key has been specified.\" in Laravel"
slug: "laravel-no-application-key-specified"
author: "benjamincrozat"
description: "Laravel's application encryption key is mandatory for it to properly work. Let me show you why this error occurs and how to fix it."
categories:
  - "laravel"
  - "security"
published_at: 2023-06-25T00:00:00+02:00
modified_at: 2023-08-12T00:00:00+02:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/PcFpIFBdIKCRi0o.png"
sponsored_at: null
---
## How to fix the "No application encryption key has been specified." error message in Laravel

*"No application encryption key has been specified."* is quite common in Laravel, and it means exactly what it says: *there's no encryption key specified for your application.*

**To fix _"No application encryption key has been specified."_, run the command `php artisan key:generate`.**

## Understanding the "No application encryption key has been specified." error message in Laravel

Laravel uses an encryption key to secure sessions, cookies, serialized data, password hashes, and other encrypted data.

If this key is not set, Laravel cannot guarantee the security of these things, hence the error message.

This is a big problem, especially in production where sensitive data must be encrypted. Here's how to fix the issue:

1. **Generate an application key:** Open a terminal, navigate to your project directory, and run the command `php artisan key:generate`. This command will generate a new random key for your application. To be safe, run `php artisan config:clear` just in case you previously cached the config values.
2. **Create the eventually missing *.env* file:** The above command sets the generated key to your `APP_KEY` environment variable in your *.env* file. Laravel should automatically have created it based on *.env.example* at the root of your project. If you get the `file_get_contents(/path/to/project/.env): Failed to open stream: No such file or directory` error message, it means it didn't. You must create it yourself by running `cp .env.example .env` for instance.

Remember, it's important to keep your `APP_KEY` secret and not to commit your *.env* file to version control systems.

[Learn more on Laravel's documentation.](https://laravel.com/docs/10.x/encryption)

## Bonus: fix the "No application encryption key has been specified." error message in Laravel with one click

As I said, the *"No application encryption key has been specified."* error message is extremely frequent in Laravel.

So much that Laravel offers you to fix it with just a single click! 

Did you notice the button? Try it, it's so convenient! 👍

![No application encryption key has been specified.](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/laravel-no-application-key-specified-f778d09f58c0bcff538b.jpg/public)

If you are still tightening the parts of Laravel where config and security collide, these are the next posts I would open:

- [Close the Laravel security gaps that are easy to miss](/laravel-security-best-practices)
- [Protect your API with Laravel Sanctum before it gets exposed](/laravel-sanctum-api-tokens-authentication)
- [Fix the 419 error before it keeps breaking forms](/419-page-expired-laravel)
- [Adjust Laravel 11 middleware without hunting through the framework](/customize-middleware-laravel-11)
