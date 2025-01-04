---
Title: "Remove final from PHP packages with Unfinalize"
Description: "Bypass PHP's final keyword restriction with Unfinalize, a tool that safely removes final from classes and methods in third-party packages."
Published at: 2023-10-03
Categories: php, packages
---

PHP's [`final` keyword](https://www.php.net/manual/en/language.oop5.final.php) is a well-known feature that allows developers to restrict further inheritance or overriding of classes and methods.

But what if you need the freedom to extend or override a third-party class that has been marked as `final`? Meet [Unfinalize](https://github.com/stevebauman/unfinalize) from [Steve Bauman](https://stevebauman.ca), a tool that helps us be anarchists by bypassing this restriction safely and efficiently.

## Introduction to Unfinalize

Unfinalize is a tool that utilizes [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer) to remove the `final` keywords from classes and methods in the Composer packages you use.

Here are some of its features:

- Safely removes `final` keywords from classes and methods.
- Operates quickly and efficiently, with no performance impact.
- Requires no additional dependencies; everything is compiled into a single PHAR file.

Let's dive into how you can install and use Unfinalize in your project.

## Install Unfinalize

Open your terminal and run the following command:

```bash
composer require stevebauman/unfinalize
```

Still with me? Great!

## Configure Unfinalize

In your project's *composer.json* file, add an "unfinalize" section. Here you'll specify which packages should have the `final` keywords removed.

```json
{
	"unfinalize": [
		"vendor/package"
	]
}
```

Still in your `composer.json`, add the `unfinalize` command to the scripts section so that it runs whenever you execute `composer update`.

```json
{
	"scripts": {
		"post-update-cmd": [
			"@php vendor/bin/unfinalize run"
		]
	}
}
```

Finally, run `composer update` to apply the changes:

```bash
composer update
```

## Additional Unfinalize options

### Annotate classes and methods as @final

If you want to annotate classes and methods with `@final` instead of outright removing the `final` keyword, you can add the `--mark-final` option when running the command.

Update your `composer.json` like this:

```json
{
    "scripts": {
        "post-update-cmd": [
            "@php vendor/bin/unfinalize run --mark-final"
        ]
    }
}
```

This will docblock the classes and methods with `@final` while still allow them to be inherited or overridden.

## Simulate the changes using the dry run mode

To see what changes Unfinalize would make without actually altering any files, use the `--dry` option:

```bash
php vendor/bin/unfinalize run --dry
```

This will print out a list of files that would be modified.
