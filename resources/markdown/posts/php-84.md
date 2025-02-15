---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/58/php-84_kkilmr.jpg
Title: "PHP 8.4: new features and release date"
Description: "PHP 8.4 is expected to drop on November 21, 2024. Let's review the known new features and changes."
Published at: 2023-09-01
Modified at: 2024-06-03
Categories: php
---

## Introduction

PHP is an open-source project. Knowing what's going on for PHP 8.4 only takes a minute of research. For instance, this page lists all the [accepted RFCs for different versions of PHP](https://wiki.php.net/rfc#php_84), including PHP 8.4.

## When will PHP 8.4 be released?

PHP 8.4 will be released on November 21, 2024, according to the [preparation tasks list](https://wiki.php.net/todo/php84). It will be tested through three alpha releases, three betas, and six release candidates.

| Date               | Release of PHP 8.4 |
|--------------------|--------------------|
| June 6, 2024       | Alpha 1            |
| June 20, 2024      | Alpha 2            |
| July 4, 2024       | Alpha 3            |
| July 16, 2024      | Feature freeze     |
| July 18, 2024      | Beta 1             |
| August 1, 2024     | Beta 2             |
| August 15, 2024    | Beta 3             |
| August 29, 2024    | RC 1               |
| September 12, 2024 | RC 2               |
| September 26, 2024 | RC 3               |
| October 10, 2024   | RC 4               |
| October 24, 2024   | RC 5               |
| November 7, 2024   | RC 6               |
| November 21, 2024  | GA                 |

## Install and test PHP 8.4 on macOS

1. Install the [Homebrew package manager](https://brew.sh) if it's not done already.
2. Run `brew update` to make sure Homebrew and the formulae are up to date.
3. Add a new tap (basically a GitHub repository) for PHP 8.4's formula: `brew tap shivammathur/php`.
4. Install the pre-compiled binary for PHP 8.4 (also called "a bottle" in Homebrew's context). This will make the install so much faster. `brew install php@8.4`.
5. Link it to make sure that the `php` alias targets the right binary: `brew link --overwrite --force php@8.4`.

If you want to learn more about how to install PHP on your Mac, I wrote something for you: [PHP for Mac: get started fast using Laravel Valet](https://benjamincrozat.com/install-php-mac-laravel-valet)

## What's new in PHP 8.4

### new MyClass()->method() without parentheses

This very welcome RFC introduces a new way to instantiate classes without needing to use parentheses when no arguments are passed to the constructor!

Essentially, it aims to simplify the syntax and make the code more concise and readable. Instead of writing `(new MyClass())->myMethod()`, you can now simply write `new MyClass()->myMethod()`.

Here’s a quick example to illustrate the change:

```php
// Before the RFC
$dog = (new Dog())->bark();

// After the RFC
$dog = new Dog()->bark();
```

This tweak doesn’t change any functionality; it just makes the code a bit cleaner and easier to write.

Learn more: [PHP RFC: new MyClass()->method() without parentheses](https://wiki.php.net/rfc/new_without_parentheses)

### New array helper functions (array_find, array_find_key, array_any, array_all)

PHP 8.4 is bringing some handy new array functions to make working with arrays even more convenient. Let's take a look at what these new functions are all about with some code examples.

**1. `array_find`**

This function helps you find the first element in an array that matches a specific condition. It's similar to array_filter, but instead of filtering out all matching elements, it stops at the first one.

**Example:**

```php
$array = ['a' => 'dog', 'b' => 'cat', 'c' => 'cow', 'd' => 'duck', 'e' => 'goose', 'f' => 'elephant'];

// Find the first animal with a name longer than 4 characters.
$result = array_find($array, function ($value) {
    return strlen($value) > 4;
});

var_dump($result); // string(5) "goose"
```

If no matching element is found, `array_find` returns `NULL`.

**2. `array_find_key`**

This function is like `array_find`, but it returns the key of the first matching element instead of the value.

**Example:**

```php
$array = ['a' => 'dog', 'b' => 'cat', 'c' => 'cow', 'd' => 'duck', 'e' => 'goose', 'f' => 'elephant'];

// Find the key of the first animal with a name longer than 4 characters.
$result = array_find_key($array, function ($value) {
    return strlen($value) > 4;
});

var_dump($result); // string(1) "e"
```

If no matching key is found, `array_find_key` returns `NULL`.

**3. `array_any`**

This function checks if any element in the array matches a given condition. It returns `true` if at least one element matches, and `false` otherwise.

**Example:**

```php
$array = ['a' => 'dog', 'b' => 'cat', 'c' => 'cow', 'd' => 'duck', 'e' => 'goose', 'f' => 'elephant'];

// Check if any animal name is longer than 5 characters.
$result = array_any($array, function ($value) {
    return strlen($value) > 5;
});

var_dump($result); // bool(true)
```

**4. `array_all`**

This function checks if all elements in the array match a given condition. It returns `true` if all elements match, and `false` if at least one element does not.

**Example:**

```php
$array = ['a' => 'dog', 'b' => 'cat', 'c' => 'cow', 'd' => 'duck', 'e' => 'goose', 'f' => 'elephant'];

// Check if all animal names are shorter than 12 characters.
$result = array_all($array, function ($value) {
    return strlen($value) < 12;
});

var_dump($result); // bool(true)
```

These functions make it easier to perform common tasks like searching and checking arrays without writing repetitive code. They are also quite similar to functions found in other programming languages like Rust, JavaScript, and C++, making PHP more versatile and easier to use for developers familiar with these languages.

Learn more: [PHP RFC: array_find](https://wiki.php.net/rfc/array_find)

### The DOM extension now supports HTML5

PHP 8.4's DOM extension just got a big upgrade with support for HTML5 parsing and serialization. This means no more headaches when dealing with HTML5 specific tags or embedding HTML in your JavaScript.

Just use the new `DOM\HTMLDocument` class and your HTML5 content will be handled correctly, keeping up with the modern web standards.

Here's how you create an HTML document from a string:

```php
use DOM\HTMLDocument;

$htmlDocument = HTMLDocument::createFromString('<!DOCTYPE html><html><body>Hello, HTML5!</body></html>');
```

Or from a file:

```php
use DOM\HTMLDocument;

$htmlDocument = HTMLDocument::createFromFile('path/to/your/file.html');
```

Learn more: [PHP RFC: DOM HTML5 parsing and serialization](https://wiki.php.net/rfc/domdocument_html5_parser)

### Increased bcrypt cost by default (meaning more secure hashing)

**In PHP 8.4, the default cost of [bcrypt](https://en.wikipedia.org/wiki/Bcrypt#:~:text=bcrypt%20is%20a%20password%2Dhashing,presented%20at%20USENIX%20in%201999.) has been increased to 12.** What does that even mean?

Bcrypt is the password hashing function that PHP uses. It acts as a shield against hackers who would want to crack passwords.

The strength of this shield can be adjusted. The higher the setting or the "cost", the stronger the shield because bcrypt is an adaptive function: *"over time, the iteration count can be increased to make it slower, so it remains resistant to brute-force search attacks even with increasing computation power."*

And why is it such a big deal? Because increasing the cost of bcrypt makes password hashing a few milliseconds slower.

Learn more: [PHP RFC: Increasing the default BCrypt cost](https://wiki.php.net/rfc/bcrypt_cost_2023)

### Large XML documents are parsed more reliably

**This change resolved a potential issue in the ext/xml PHP extension involving its handling of large XML document parsing.**

Conflicting modifications in libxml2 version 2.7.0 unintentionally disrupted large document parsing when using `xml_parse()` and `xml_parse_into_struct()` functions, resulting in a parsing error.

PHP 8.4 introduced a new parser option to properly handle these large XML documents and prevent the parsing error, making it possible for developers to parse large XML documents effectively, without needing complex workarounds.

Learn more: [PHP RFC: XML_OPTION_PARSE_HUGE](https://wiki.php.net/rfc/xml_option_parse_huge)

### New multibyte trimming functions

PHP 8.4's mbstring extension now includes three new functions: `mb_trim()`, `mb_ltrim()`, and `mb_rtrim()`. This addition makes it easier to trim strings with multibyte characters, improving upon the previous workaround of using regex with `preg_replace()`.

The new functions handle whitespace and other characters in a multibyte safe way. The default behavior removes a predefined set of characters which includes various types of space characters, including some that are not typically covered by `\s` in regular expressions.

Here are the functions in PHP 8.4 and their default behaviors:

- `mb_trim($string, $characters)`: Trims characters from both ends of a string.
- `mb_ltrim($string, $characters)`: Trims characters from the beginning (left side) of a string.
- `mb_rtrim($string, $characters)`: Trims characters from the end (right side) of a string.

By default, `$characters` includes a variety of whitespace characters, but not all possible Unicode characters due to storage and compatibility concerns.

Learn more: [PHP RFC: Multibyte for trim function mb_trim, mb_ltrim and mb_rtrim](https://wiki.php.net/rfc/mb_trim)
