---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/user-attachments/assets/2e4c7701-a2c5-4cdd-aa1c-9326ac56bad3
Title: "What's new in Pest 3 and how to upgrade"
Description: "Step up your PHP testing game with Pest 3. Architecture testing presets, mutations, and todo lists management."
Published at: 2024-08-27
Modified at: 2024-08-28
Categories: php
---

## Introduction to Pest 3

[Pest](https://pestphp.com), my favorite PHP testing framework, has just dropped its version 3. It was presented during Laracon US 2024 by Nuno Maduro and I couldn't wait to dive in and share my thoughts with you.

## Is Pest 3 the easiest upgrade ever?

Before we get into the juicy new features, let's talk about upgrading. Spoiler alert: it's ridiculously simple. All you need to do is bump up your dependency to `^3.x-dev` and change `minimum-stability` to `dev` in your `composer.json` file. That's it! No breaking changes to worry about, no tedious refactoring – just a smooth sail to the latest and greatest version of Pest.

```json
{
    "require-dev": {
        "pestphp/pest": "^3.x-dev"
    }
}
```

Run `composer update`, and you're good to go. Now, let's dive into what's new!

## What's new in Pest 3?

### Architecture Testing Presets

One of the standout features in Pest 3 is the introduction of Architecture Testing Presets. This is a powerful tool that allows us to enforce architectural rules and best practices in our codebase. And the best thing being that we don't have to write them ourselves!

Pest 3 comes with several presets out of the box:

- Laravel
- PHP
- Relaxed
- Security
- Strict

Using these presets is as simple as calling a method. For example, if you're working on a Laravel project, you can use the Laravel preset like this:

```php
arch()->preset()->laravel();
```

This will apply a set of architectural rules that are tailored for Laravel applications, helping you maintain a clean and consistent codebase.

And here how you can use the other ones:

```php
arch()->preset()->php();
arch()->preset()->relaxed();
arch()->preset()->security();
arch()->preset()->strict();
```

But what if you need to make exceptions? Pest 3 has got you covered. You can exclude specific files or namespaces using the `ignoring()` method. For instance:

```php
arch()->preset()->laravel()->excluding('App\Models\Scopes');
```

This flexibility allows you to adhere to best practices while still accommodating the unique needs of your project.

I wrote more about this in my [guide to architecture testing presets in Pest 3](/pest-3-architecture-testing-presets).

### Mutation testing: how reliable are your tests?

Pest 3 is bringing mutation testing to the table! This feature enhances the reliability of your test suites by making small changes to your code and running tests against these mutated versions.

To get started with mutation testing, install the plugin:

```bash
composer require pestphp/pest-plugin-mutate --dev
```

Once installed, you can run mutation tests with this simple command:

```bash
./vendor/bin/pest --mutate
```

I ran this on my project, and here's what it looks like:

![Pest's mutation tests in action.](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/user-attachments/assets/fe303b15-3a35-4f8b-8a6b-f066e566576c)

As you can see, this is a disaster. But for my defense, it's a new project and a work in progress!

For more information and detailed documentation, check out the [Pest Mutate Plugin GitHub repository](https://github.com/pestphp/pest-plugin-mutate).

### Todos Management with GitHub

One of the most exciting features in Pest 3 is the new Todos Management system, which integrates seamlessly with GitHub. This feature allows you to manage your testing workflow directly from your test files and command line, creating a bridge between your tests and your project management on GitHub.

Here's what you can do with this new feature:

1. **Mark Tests as Todo or Done**: You can now explicitly mark tests as "todo" or "done" in your test files:

   ```php
   test('something happens when…', function () { … })
       ->todo()
       ->assignee('benjamincrozat')
       ->issue(42);

   test('an event is triggered when…', function () { … })
       ->done()
       ->pr(101);
   ```

2. **Link Tests to GitHub Issues and Pull Requests**: Easily associate your tests with specific GitHub issues or pull requests:

   ```php
   test('users can…', function () { … })
       ->issue(13)
       ->pr(25);
   ```

3. **Assign Tests to Team Members**: Delegate responsibilities by assigning tests to specific team members:

   ```php
   test('…', function () { … })
       ->assignee('benjamincrozat');
   ```

4. **Add Notes to Tests**: Include additional context or instructions with your tests:

   ```php
   test('…', function () { … })
       ->note('Focus on optimizing the user lookup query.');
   ```

5. **Command-Line Interface**: There are new options allowing you to filter and run tests based on their associated issues, assignees, pull requests, or notes.

   ```bash
   pest --issue=11
   pest --assignee=benjamincrozat --pr=1
   pest --notes
   ```
