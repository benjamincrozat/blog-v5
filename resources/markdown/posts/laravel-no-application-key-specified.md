---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/39/app-key_udl0ji.png
Title: "Fix \"No application encryption key has been specified.\" in Laravel"
Description: "Laravel's application encryption key is mandatory for it to properly work. Let me show you why this error occurs and how to fix it."
Published at: 2023-06-25
Modified at: 2023-08-12
Categories: laravel, security
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

![No application encryption key has been specified.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/140/conversions/CleanShot_2023-06-25_at_11.37.56_2x_qo61rw-medium.jpg)

