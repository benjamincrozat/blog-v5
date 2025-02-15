---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/user-attachments/assets/dc061e0c-6017-4c22-b18d-a1f1e439bb55
Title: "A guide to architecture testing presets in Pest 3"
Description: "Discover how Pest 3 simplifies architecture testing with pre-configured presets, making it effortless to enforce best practices and maintain code quality in your projects."
Published at: 2024-08-28
Categories: php
---

## Introduction

If you're anything like me, you're always looking for ways to improve your code quality and maintainability (but not at the expense of your ability to ship, right?). Writing architecture tests with Pest 2 is one of the ways to do it. But now, Architecture Testing Presets is Pest 3 will make it effortless.

If needed, [learn how to get started with this new version](/pest-3) first.

## What are Architecture Testing Presets?

Architecture Testing Presets in Pest 3 are pre-configured sets of rules that help enforce best practices and structural integrity in your codebase. They're like having a vigilant code reviewer built right into your testing suite. But it's immediate and takes just a few seconds!

Pest 3 comes with several presets out of the box:

- Laravel
- PHP
- Relaxed
- Security
- Strict

Let's break down what each of these presets does for you.

## The Laravel Preset: Your Laravel Project's Best Friend

The Laravel preset is tailor-made for Laravel applications. It's packed with rules that ensure your code follows Laravel's best practices and conventions. Here's a taste of what it does:

- Ensures your models extend `Illuminate\Database\Eloquent\Model` and don't have the 'Model' suffix
- Checks that your form requests extend `Illuminate\Foundation\Http\FormRequest` and have a `rules` method
- Verifies that your commands extend `Illuminate\Console\Command`, have a `handle` method, and end with 'Command'
- Makes sure your mail classes extend `Illuminate\Mail\Mailable` and implement `Illuminate\Contracts\Queue\ShouldQueue`
- Checks that your controllers only have specific public methods (like `index`, `show`, `create`, etc.)
- Prevents the use of debugging functions like `dd`, `ddd`, `env`, and `ray` in your code

And that's just scratching the surface! It's like having Taylor Otwell himself reviewing your code structure, haha!

To use the Laravel preset in your tests, simply add:

```php
arch()->preset()->laravel();
```

You can also exclude certain namespaces if needed:

```php
arch()->preset()->laravel()->excluding('App\Exceptions');
```

## The PHP Preset: For the Rest of Your Projects

The PHP preset is all about maintaining clean, professional PHP code. It focuses on preventing the use of deprecated or potentially dangerous PHP functions. Here's what it looks like in action:

- Blocks the use of debugging functions like `var_dump`, `print_r`, and `debug_backtrace`
- Prevents the use of old MySQL functions (remember, we're all using PDO or mysqli now, right?)
- Stops you from using `echo` and `print` (because we're returning views, not echoing HTML like it's 2005)
- Disallows the use of `goto` (we're not in the 80s writing BASIC)
- Prevents the use of Xdebug functions in production code

To apply the PHP preset to your tests, use:

```php
arch()->preset()->php();
```

## The Relaxed Preset: For When You Need a Little Flexibility

Sometimes, you need a bit more flexibility in your architecture. And if it was up to me, I'd say we need those rules enforced! And that's where the Relaxed preset comes in. It's the most permissive of the bunch:

- Allows the use of non-strict types
- Permits non-final classes (inheritance for everyone!)
- Allows public and protected methods (no private method police here)

It's perfect for those projects where you need a little more breathing room or when you're working with legacy code that you can't refactor just yet.

To use the Relaxed preset, add this to your test file:

```php
arch()->preset()->relaxed();
```

## The Security Preset: Your Code's Bodyguard

The Security preset is all about keeping your code safe and secure.

- Prevents the use of weak cryptographic functions like `md5` and `sha1`
- Blocks the use of potentially dangerous functions like `eval`, `exec`, and `shell_exec`
- Disallows the use of `unserialize`
- Stops you from using `extract` on user input (no variable contamination here!)
- Prevents the use of `assert` in production code

To enforce these security rules, use:

```php
arch()->preset()->security();
```

## The Strict Preset: For the Crazy Ones

Last but not least, we have the Strict preset. This one is for the developers who like their code like a rigid IRS auditor.

- Enforces the use of strict types
- Requires all classes to be final
- Disallows protected methods (if you like to think that everything is black or white)
- Prevents the use of `sleep` and `usleep` functions

To apply this strict ruleset, use:

```php
arch()->preset()->strict();
```

## Wrapping Up

Architecture Testing Presets in Pest 3 are a game-changer for maintaining code quality and consistency. They're like having a team of expert reviewers continuously checking your code structure and practices.

Whether you're working on a Laravel project, need to maintain strict security standards, or just want to ensure your PHP code is clean and modern, there's a preset for you.

So, give these presets a try in your next Pest 3 project. Your future self (and your team) will thank you for the clean, consistent, and secure codebase!

Happy testing, and may your builds always be green!
