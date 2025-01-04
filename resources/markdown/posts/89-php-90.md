---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/222/Oezl6om6sBJmaiKSF2sh78yXpzyNvp-metacGhwLTkwLnBuZw%3D%3D-.png
Title: "An early look at PHP 9.0's new features and changes"
Description: "PHP 9.0 is still far in the future. We don't know a lot, but we have a few breaking changes planned for it."
Published at: 2023-11-03
Modified at: 2024-01-01
Categories: php
---

## Introduction

PHP is an open-source project. Knowing what new features and changes are planned for the next version only takes a minute of research. For instance, this page lists all the [accepted RFCs for different versions of PHP](https://wiki.php.net/rfc).

That being said, despite PHP 9.0 being planned, no work has been started yet and we have to dig deeper.

## When will PHP 9.0 be released?

**For now, the release date of PHP 9.0 hasn't been announced yet.** This version is still far in the future. We could get PHP 8.5 and 8.6 before 9.0 is even considered. Who knows?

## How to install and test PHP 9.0?

To this day, no work has been started on PHP 9.0, so you won't even be able to pull the latest code and compile it yourself.

## New features and changes planned for PHP 9.0

### Better increment and decrement behavior

PHP 9 is cleaning up how the `++` and `--` operators work. Here's what's changing:

1. No more weird string increments:
   ```php
   // PHP 8 and earlier
   $foo = 'a9';
   $foo++;
   echo $foo;  // Outputs: 'b0'

   // PHP 9
   $foo = 'a9';
   $foo++;  // Throws a TypeError
   ```

2. Booleans and null will be treated as numbers:
   ```php
   // PHP 8 and earlier
   $bar = true;
   $bar++;
   var_dump($bar);  // Outputs: bool(true)

   // PHP 9
   $bar = true;
   $bar++;
   var_dump($bar);  // Outputs: int(2)
   ```

3. Empty strings won't magically become numbers:
   ```php
   // PHP 8 and earlier
   $baz = '';
   $baz--;
   var_dump($baz);  // Outputs: int(-1)

   // PHP 9
   $baz = '';
   $baz--;  // Throws a TypeError
   ```

These changes make PHP more predictable.

If you still need the old string increment behavior, you can use the new `str_increment()` function.

[PHP RFC: Path to Saner Increment/Decrement operators](https://wiki.php.net/rfc/saner-inc-dec-operators)

### PHP 9.0 throws an exception on unserialization errors

This RFC, [Improve unserialize() error handling](https://wiki.php.net/rfc/improve_unserialize_error_handling), which has been partially implemented in PHP 8.3, upgrades unserialization errors from `E_NOTICE` to `E_WARNING`.

**In PHP 9.0, these will be upgraded to an `UnserializationFailedException`.**

This will allow developers to stop using a custom error handler and get a behavior more consistent with other parts of the language.

```php
// PHP 8.3: "Warning: unserialize(): Error at offset 0 of 3 bytes"
// PHP 9.0: "Uncaught UnserializationFailedException: unserialize(): Error at offset 0 of 3 bytes"
unserialize("foo");
```

### Simplified function signatures

PHP 9 is will make functions easier to understand and use. How? By simplifying their signatures. Let's break it down with two examples.

First, take a look at `array_keys()`:

```php
// Current PHP:
$allKeys = array_keys($myArray);
$specificKeys = array_keys($myArray, 'searchValue', true);

// PHP 9:
$allKeys = array_keys($myArray);
$specificKeys = array_keys_filter($myArray, 'searchValue', true);
```

See the difference? Instead of one function doing two jobs, we'll have two separate, more focused functions.

Here's another example with `DatePeriod::__construct()`:

```php
// Current PHP:
$period1 = new DatePeriod($start, $interval, $end);
$period2 = new DatePeriod('R4/2012-07-01T00:00:00Z/P7D');

// PHP 9:
$period1 = new DatePeriod($start, $interval, $end);
$period2 = DatePeriod::createFromISO8601String('R4/2012-07-01T00:00:00Z/P7D');
```

Again, we're moving from one multi-purpose constructor to a constructor and a separate creation method.

Why these changes? They make PHP more predictable. When a function or method does just one thing, it's easier to understand and use correctly.

Here's the plan:
1. PHP 8.3 introduces the new functions.
2. PHP 8.4 warns you about using the old ways.
3. PHP 9 (or 10, it's still not decided) will complete the transition.

[PHP RFC: Deprecate functions with overloaded signatures](https://wiki.php.net/rfc/deprecate_functions_with_overloaded_signatures)

This RFC proposes to deprecate and eventually remove autovivification (automatic creation of arrays) from false values in PHP. Here's a simplified explanation for the blog post:

### No more arrays out of false values

PHP 9 is getting stricter about how arrays are created, particularly when it comes to false values. Let's break this down:

Currently in PHP, you can do something like this:

```php
$arr = false;
$arr[] = 2; // This creates an array [2]
```

This feature, called "autovivification", automatically converts false to an array. While convenient, it can lead to unexpected behavior and bugs.

In PHP 9, this won't be allowed anymore. Instead, you'll see an error:

```php
$arr = false;
$arr[] = 2; // Error: Cannot use a scalar value as an array
```

You already can't do this with other values like true or 0, so why should `false` be special?

[PHP RFC: Deprecate autovivification on false](https://wiki.php.net/rfc/autovivification_false)

This RFC proposes to deprecate and eventually remove certain forms of string interpolation in PHP. Here's a simplified explanation for the blog post:

### Simplified string interpolation

PHP 9 is simplifying how you can embed variables in strings. Let's break it down:

Currently, PHP allows several ways to put variables inside strings:

1. Direct: `"$foo"`
2. Braces outside: `"{$foo}"`
3. Braces after dollar: `"${foo}"`
4. Variable variables: `"${expr}"`

PHP 9 will keep options 1 and 2, but remove options 3 and 4. Why? Because they're confusing and less useful.

For example, this won't work in PHP 9:

```php
$foo = 'world';
echo "Hello ${foo}";  // This will cause an error
```

Instead, you'll need to use one of these:

```php
echo "Hello $foo";     // Option 1
echo "Hello {$foo}";   // Option 2
```

[PHP RFC: Deprecate ${} string interpolation](https://wiki.php.net/rfc/deprecate_dollar_brace_string_interpolation)

### Some warnings will become errors in PHP 9.0

In order to make PHP more reliable, warnings for undefined variables and properties will now become errors.

For instance, the following code will not run anymore in PHP 9.0:

```php
// PHP 8.x: "Warning: Undefined variable $foo"
// PHP 9.0: "Fatal error: Uncaught Error: Undefined variable '$foo'"
echo $foo;
```

Also, from what I understand, these changes with variables and properties will make the maintainers' lives easier, which is good for everyone!

I let you check out the RFCs for more details:
- [PHP RFC: Undefined Variable Error Promotion](https://wiki.php.net/rfc/undefined_variable_error_promotion)
- [PHP RFC: Undefined Property Error Promotion](https://wiki.php.net/rfc/undefined_property_error_promotion)

### Deprecated features from earlier PHP versions will be removed

**Features that have been deprecated in PHP 8.1, 8.2, 8.3, and 8.4 ([learn more about PHP 8.4](/php-84)) will finally be removed in PHP 9.0.** This will translate to breaking changes for developers who ignored the warnings. 😅

Here's a list of RFCs containing all the deprecated features:
- [PHP RFC: Deprecations for PHP 8.1](https://wiki.php.net/rfc/deprecations_php_8_1)
- [PHP RFC: Deprecations for PHP 8.2](https://wiki.php.net/rfc/deprecations_php_8_2)
- [PHP RFC: Deprecations for PHP 8.3](https://wiki.php.net/rfc/deprecations_php_8_3)
- [PHP RFC: Deprecations for PHP 8.4](https://wiki.php.net/rfc/deprecations_php_8_4)
