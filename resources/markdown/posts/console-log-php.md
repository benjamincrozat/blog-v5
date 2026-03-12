---
id: "01KKEW278247QVW51TXXYX9XAE"
title: "console.log() in PHP"
slug: "console-log-php"
author: "benjamincrozat"
description: "Explore the world of PHP debugging with var_dump(), and Laravel's friendlier alternatives, dump() and dd(). Much charm, such useful!"
categories:
  - "php"
published_at: 2023-06-24T00:00:00+02:00
modified_at: 2025-08-10T07:05:00+02:00
serp_title: "console.log() in PHP (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/UZJJt4EQvodo7Eq.jpg"
sponsored_at: null
---
## How to log into the console in PHP

**PHP is a server-side scripting language and cannot log into the console just like in JavaScript.** PHP code is interpreted and executed by the server, not by your visitors' web browser.

However, like with `console.log()` in JavaScript, **PHP can print values using the [`var_dump()`](https://www.php.net/var_dump) function.**

Let's explore in more details, shall we?

## var_dump() is the console.log() of PHP

[`var_dump()`](https://www.php.net/var_dump) is PHP's workhorse for spitting out a variable's value. 

What does it do exactly? It displays structured information about one or more expressions that include its type and value.

If you `var_dump()` an object, it will display the class name, the object's attributes, and their respective values.

Pretty neat, huh?

Let's look at an example:

```php
var_dump(['Foo', 'Bar', 'Baz']);
```

The output you'd get from this would be:

```php
array(3) {
  [0]=>
  string(3) "Foo"
  [1]=>
  string(3) "Bar"
  [2]=>
  string(3) "Baz"
}
```

While `var_dump()` does a fine job, it isn't without its limitations.

Its output can sometimes be hard to read, especially with large, nested arrays or objects.

Luckily, the world of PHP debugging is rich and diverse.

Therefore, let me present you Laravel's debugging helpers, [`dump()`](https://laravel.com/docs/10.x/helpers#method-dump) and [`dd()`](https://laravel.com/docs/10.x/helpers#method-dd).

## dump() and dd() are the console.log() of Laravel

Laravel, the popular PHP framework, introduces a couple of useful helpers that can make our debugging life a bit easier. They're called `dump()` and `dd()`.

The `dump()` function in Laravel works similarly to `var_dump()`, but it's a bit friendlier to the eyes.

It prints the data in a more structured and stylized format, making it easier to read and understand.

Let's use `dump()` on the same array we used with var_dump():

```php
dump(['Foo', 'Bar', 'Baz']);
```

The output is more neatly structured and presented:

```php
array:3 [
  0 => "Foo"
  1 => "Bar"
  2 => "Baz"
]
```

Now, let's talk about Laravel's dd() function.

"dd" stands for "dump and die."

It works similarly to `dump()`, but with a slight twist. After dumping the data, it halts the execution of the script. This is particularly useful when you want to examine a specific part of your code without letting the rest of it run.

Again, let's use `dd()` on our example array:

```php
dd(['Foo', 'Bar', 'Baz']);

echo "This will never be executed.";
```

The output would be the same as `dump()`, but the script execution will stop immediately after.

## But what if I really want it in the browser console?

I'm struggling to find up to date solutions, but here's one that's working at the moment. If I find more, I'll update this article.

### FirePHP (Chrome and Firefox)

Install the [Chrome](https://chromewebstore.google.com/detail/firephp-official/ikfbpappjhegehjflebknjbhdocbgkdi?hl=en) or [Firefox](https://addons.mozilla.org/en-US/firefox/addon/firephp/) extension. Then, add this package to your project:

```bash
composer require firephp/firephp-core --dev
```

Then, you just need to log something:

```php
$firephp = FirePHP::getInstance(true);
$firephp->log('Greetings from PHP to Firefox!');
```

Also, check out the [GitHub repository](https://github.com/firephp/firephp-for-browser-devtools).

If you are still trying to make PHP debugging less blind, these are the next reads I would keep nearby:

- [Inspect arrays without wrecking your output](/php-laravel-print-array)
- [Show every PHP error when debugging gets vague](/php-show-all-errors)
- [Check whether your PHP version is part of the problem](/check-php-version)
- [Turn a PHP array into valid JSON without surprises](/php-array-to-json)
- [See what changes if you're moving your tests to Pest 4](/pest-4)
