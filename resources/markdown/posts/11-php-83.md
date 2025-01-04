---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/13/php-83_jyplbw.png
Title: "PHP 8.3 is out, now! Here's what's new and changed."
Description: "PHP 8.3 was released on November 23, 2023, and as usual, you need to be up to date with new features and breaking changes for easier transitions."
Published at: 2022-10-10
Modified at: 2024-01-01
Categories: php
---

## Introduction

PHP is an open-source project. Knowing what's going on for the next version only takes a minute of research. For instance, this page lists all the [accepted RFCs for PHP 8.3](https://wiki.php.net/rfc#php_83).

Below, you will find a condensed list of what's new, with code samples that make sense.

Oh and by the way, I already started writing about [PHP 8.4's release date, new features and changes](/php-84).

## When was PHP 8.3 released?

**PHP 8.3 was released on November 23, 2023**. It has been tested through three alpha releases, three beta, and six release candidates.

| Date               | Release        |
| ------------------ | -------------- |
| June 8, 2023       | Alpha 1        |
| June 22, 2023      | Alpha 2        |
| July 6, 2023       | Alpha 3        |
| July 18, 2023      | Feature freeze |
| July 20, 2023      | Beta 1         |
| August 03, 2023    | Beta 2         |
| August 17, 2023    | Beta 3         |
| August 31, 2023    | RC 1           |
| September 14, 2023 | RC 2           |
| September 28, 2023 | RC 3           |
| October 12, 2023   | RC 4           |
| October 26, 2023   | RC 5           |
| November 9, 2023   | RC 6           |
| November 23, 2023  | GA             |

## How to install and test PHP 8.3 on Mac

If you are using Laravel Herd, this only takes one click!

![Upgrading PHP to version 8.3 using Laravel Herd.](https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/267/conversions/01HFY6KJBWK2WVFG5YAEYFTN27-medium.jpg)

If you are using Laravel Valet, here's how to proceed:

1. Install the [Homebrew package manager](https://brew.sh) if it's not done already.
2. Run `brew update` to make sure Homebrew and the formulae are up to date.
3. Add a new tap (basically a GitHub repository) for PHP 8.3's formula: `brew tap shivammathur/php`.
4. Install the pre-compiled binary for PHP 8.3 (also called "a bottle" in Homebrew's context). This will make the install so much faster. `brew install php@8.3`.
5. Link it to make sure that the `php` alias targets the right binary: `brew link --overwrite --force php@8.3`.

If you want to learn more about how to install PHP on your Mac, I wrote something for you: [PHP for Mac: get started fast using Laravel Valet](https://benjamincrozat.com/install-php-mac-laravel-valet)

## What's new in PHP 8.3: new features and changes

### The new Override attribute

When you are using PHP 8.3's new [`Override`](https://wiki.php.net/rfc/marking_overriden_methods) attribute, here's what you tell other developers or your future self:
- **I'm overriding a method** from the parent class or one of the implemented interfaces.
- **I have correctly overridden the method.** If, for instance, I made a typo, the code would throw an error.

I had so much trouble understanding this one. But in fact, it's **dead simple**. 😅

Here's an example:

```php
class SomeClass
{
	public function someMethod()
	{
	}
}

class SomeOtherClass extends SomeClass
{
	public function someMethod()
	{
	}
}
```

In this example, `SomeOtherClass` overrides the `someMethod` method from its parent class, `SomeClass`. But what happens if we change `someMethod` in `SomeClass`? Our override will be completely nullified.

But as you guessed it, the solution to this problem is the `Override` attribute:

```php
class SomeClass
{
	// We renamed the method.
	public function someRenamedMethod()
	{
	}
}

class SomeOtherClass extends SomeClass
{
	// But now, thanks to Override, we get an error message saying
	// that our override doesn't override any method anymore.
	//
	// Fatal error: SomeOtherClass::someMethod() has #[\Override] attribute, but no matching parent method exists
	
	#[Override]
	public function someMethod()
	{
	}
}
```

How neat is that? 👌

Learn more: [PHP RFC: Marking overridden methods (#[\Override])](https://wiki.php.net/rfc/marking_overriden_methods)

### Validate JSON with PHP 8.3 using the new json_validate() function

PHP 8.3 introduces a new [`json_validate()`](https://www.php.net/json_validate) function. Its purpose is to check if a given string is a valid JSON object.

Currently, most PHP programmers use the [`json_decode()`](https://www.php.net/json_decode) function for this task, but this uses memory and processing power that isn't required for just checking validity.

`json_validate()` will use the existing JSON parser in PHP, ensuring that it will be fully compatible with `json_decode()`.

The function accepts a JSON string, a maximum nesting depth and flags, and returns a Boolean value indicating whether the string represents valid JSON.

If there are any errors during validation, these can be fetched using the [`json_last_error()`](https://www.php.net/json_last_errors) or [`json_last_error_msg()`](https://www.php.net/json_last_error_msg) functions.

```php
$json = '{ "foo": "bar" }';

if (json_validate($json)) {
    // Valid JSON.
} else {
    // Invalid JSON.
}
```

Learn more: [PHP RFC: json_validate](https://wiki.php.net/rfc/json_validate)

### Improved unserialize() error handling

The RFC proposes two main changes to PHP's unserialize function to enhance its error handling.

The current error handling in `unserialize()` is inconsistent, as it may emit an `E_NOTICE`, an `E_WARNING`, or throw an arbitrary `Exception` or `Error` depending on how the input string is malformed. 

This makes it challenging to reliably handle errors during unserialization.

The first proposal is to introduce a new wrapper exception, `UnserializationFailedException`.

Whenever a `Throwable` is thrown during unserialize, this `Throwable` will be wrapped in a new instance of `UnserializationFailedException`.

This allows developers to use a single `catch(UnserializationFailedException $e)` to handle all possible `Throwable` happening during unserialization.

The original `Throwable` is accessible through the $previous property of `UnserializationFailedException`, allowing the developer to learn about the actual cause of the unserialization failure.

The second proposal is to increase the error reporting severity in the `unserialize()` parser.

In the current state, unserialization can fail due to a syntax error in the input string, out-of-range integers, and similar issues, which result in an `E_NOTICE` or `E_WARNING`.

This RFC proposes to increase the severity of these notices/warnings, and ultimately have them throw the new `UnserializationFailedException`.

Before the proposed changes, handling unserialization errors in PHP could look like this:

```php
try {
    set_error_handler(static function ($severity, $message, $file, $line) {
        throw new \ErrorException($message, 0, $severity, $file, $line);
    });
    $result = unserialize($serialized);
} catch (\Throwable $e) {
    // Unserialization failed. Catch block optional if the error should not be handled.
} finally {
    restore_error_handler();
}

var_dump($result); // Do something with the $result.
                   // Must not appear in the 'try' to not 'catch' unrelated errors.
```
			   
And several typical cases can look like this:

```php
unserialize('foo'); // Notice: unserialize(): Error at offset 0 of 3 bytes in php-src/test.php on line 3
unserialize('i:12345678901234567890;'); // Warning: unserialize(): Numerical result out of range in php-src/test.php on line 4
unserialize('E:3:"foo";'); // Warning: unserialize(): Invalid enum name 'foo' (missing colon) in php-src/test.php on line 5
                           // Notice: unserialize(): Error at offset 0 of 10 bytes in php-src/test.php on line 5
```

After the proposed changes, handling unserialization errors in PHP would be as follows:

```php
function unserialize(string $data, array $options = []): mixed
{
    try {
        // The existing unserialization logic happens here.
    } catch (\Throwable $e) {
        throw new \UnserializationFailedException(previous: $e);
    }
}
```

And the implementation of the new exception:

```php
class UnserializationFailedException extends \Exception
{
}
```

The RFC has been partly accepted for PHP 8.3.

Learn more: [PHP RFC: Improve unserialize() error handling](https://wiki.php.net/rfc/improve_unserialize_error_handling)

### Randomizer additions

The RFC for PHP titled "Randomizer Additions" proposes three new methods and an accompanying enum for the `\Random\Randomizer` class.

The `getBytesFromString()` method allows you to generate a string of a specified length using randomly selected bytes from a given string.

The `getFloat()` method returns a float between a defined `$min` and `$max`, with the interval boundaries being open or closed depending on the value of the `$boundary` parameter.

The `nextFloat()` method is equivalent to `->getFloat(0, 1, \Random\IntervalBoundary::ClosedOpen)`.

The enum `IntervalBoundary` is used to determine the nature of the interval boundaries in the `getFloat()` method.

For example, with the `getBytesFromString()` method:

Before:

```php
$randomString = '';

$characters = 'abcdefghijklmnopqrstuvwxyz0123456789';

$charactersLength = strlen($characters);

for ($i = 0; $i < 16; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
}

$randomDomain = $randomString . ".example.com";
```

After:

```php
$randomizer = new \Random\Randomizer();

$randomDomain = $randomizer->getBytesFromString('abcdefghijklmnopqrstuvwxyz0123456789', 16) . ".example.com";
```

Learn more: [PHP RFC: Randomizer Additions](https://wiki.php.net/rfc/randomizer_additions)

### Dynamic class constant fetch

This RFC proposes to allow class constants to be accessed dynamically using variables.

Instead of accessing class constants with a static string value (e.g. `ClassName::CONSTANT`), you could use a variable containing the constant name.

```php
$constant = 'CONSTANT';

ClassName::{$constant}
```

This change would make it easier to access class constants dynamically and programmatically.

Learn more: [PHP RFC: Dynamic class constant fetch](https://wiki.php.net/rfc/dynamic_class_constant_fetch)

### More appropriate Date/Time exceptions

The RFC proposes to introduce Date/Time extension specific exceptions and errors in PHP, instead of the more generic warnings, errors, and exceptions currently used.

This aims to provide better specificity and handling for Date/Time related exceptions.

The proposal includes various specific exceptions, such as `DateInvalidTimeZoneException`, `DateInvalidOperationException`, `DateMalformedStringException`, `DateMalformedIntervalStringException`, and others.

Notably, this change only affects object-oriented style use of date/time functions, as procedural style usage will continue to use warnings and errors as it currently does.

Below are examples of how the new exceptions work:

Before the change:

For creating a `DateInterval` with a malformed string, the function call would return a warning and false:

```php
try {
    $i = DateInterval::createFromDateString("foo");
} catch (Exception $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
```

For trying to subtract a non-special relative time specification from a `DateTimeImmutable` object, the function call would return a warning and `null`:

```php
$now = new DateTimeImmutable("2022-04-22 16:25:11 BST");

var_dump($now->sub($e));
```

After the change:

For creating a `DateInterval` with a malformed string, the function now throws a `DateMalformedIntervalStringException`:

```php
try {
    $i = DateInterval::createFromDateString("foo");
} catch (DateMalformedIntervalStringException $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
```

For trying to subtract a non-special relative time specification from a `DateTimeImmutable` object, the function now throws a `DateInvalidOperationException`:

```php
$now = new DateTimeImmutable("2022-04-22 16:25:11 BST");

try {
    var_dump($now->sub($e));
} catch (DateInvalidOperationException $e) {
    echo $e::class, ': ', $e->getMessage(), "\n";
}
```

Learn more: [PHP RFC: More Appropriate Date/Time Exceptions](https://wiki.php.net/rfc/datetime-exceptions)

### Read only amendments

This is a two parts RFC and only the part where read only properties can now be reinitialized during cloning has been accepted.

This proposal aims to eliminate the limitation that prevents readonly properties from being "deep-cloned".

It allows readonly properties to be reinitialized during the execution of the `__clone()` magic method call. Here is an example of this change:

Before:

```php
class Foo {
    public function __construct(
        public readonly DateTime $bar,
        public readonly DateTime $baz
    ) {}

    public function __clone()
    {
        $this->bar = clone $this->bar; // Doesn't work, an error is thrown.
    }
}
```

After:

```php
class Foo {
    public function __construct(
        public readonly DateTime $bar,
        public readonly DateTime $baz
    ) {}

    public function __clone()
    {
        $this->bar = clone $this->bar; // Works.
        $this->cloneBaz();
    }

    private function cloneBaz()
    {
        unset($this->baz); // Also works.
    }
}

$foo = new Foo(new DateTime(), new DateTime());

$foo2 = clone $foo;
// No error, Foo2::$bar is cloned deeply, while Foo2::$baz becomes uninitialized.
```

Learn more: [PHP RFC: Read only amendements](https://wiki.php.net/rfc/readonly_amendments)

### Saner array_sum() and array_product()

This RFC introduces changes to the behavior of `array_sum()` and `array_product()` functions to make them more consistent with their userland implementations using `array_reduce()`.

Currently, `array_sum()` and `array_product()` skip array or object entries, while their userland implementations using array_reduce() throw a `TypeError`.

The proposed changes include:

1. Using the same behavior for `array_sum()` and `array_product()` as the `array_reduce()` variants.
2. Emitting an `E_WARNING` for incompatible types.
3. Supporting objects with numeric cast to be added/multiplied.
	
Learn more: [PHP RFC: Saner array_(sum|product)()](https://wiki.php.net/rfc/saner-array-sum-product)

### PHP RFC: Typed class constants

Typed class constants are finally coming in PHP 8.3!

They will help reduce bugs and confusion arising from child classes overriding parent class constants.

Typed class constants can be declared in classes, interfaces, traits, and enums.

Key points:

- Class constant type declarations support all PHP type declarations except void, callable, and never.
- Strict_types mode does not affect the behavior since type checks are always performed in strict mode.
- Class constants are covariant, meaning that their types are not allowed to widen during inheritance.
- Constant values must match the type of the class constant, with the exception that float class constants can also accept integer values.
- [ReflectionClassConstant](https://www.php.net/manual/en/class.reflectionclassconstant.php) is extended with two methods: getType() and hasType().

As you may imagine, typed constants are super easy to use, just like typed properties:

```php
interface Foo {
    public const string BAR = 'baz';
}

class Bar extends Foo {
    // Doesn't work anymore! You cannot change the 
    // type and assign a value of another type.
    public const array BAR = ['foo', 'bar', 'baz'];
    // OK.
    public const string BAR = 'foo';
}
```

Learn more: [PHP RFC: Typed class constants](https://wiki.php.net/rfc/typed_class_constants)

### Arbitrary static variable initializers

The RFC proposes a change that would enable the static variable initializer to contain arbitrary expressions, rather than just constant expressions.

This makes the language more flexible and less confusing for users. For example, before this change, a static variable could only be initialized with a constant value:

```php
function foo() {
    static $i = 1;
    echo $i++, "\n";
}

foo();
foo();
foo();
```

This would output:

```
1
2
3
```

With the proposed change, a static variable can be initialized with a function call:

```php
function bar() {
    echo "bar() called\n";
    return 1;
}

function foo() {
    static $i = bar();
    echo $i++, "\n";
}

foo();
foo();
foo();
```

The proposal also introduces several changes to the semantics of static variable initialization, including the treatment of exceptions during initialization, the order of operations when destructors are involved, and the handling of recursion during initialization.

Redeclaring static variables would not be allowed, though, and it changes how `ReflectionFunction::getStaticVariables()` works with static variables.

Learn more: [PHP RFC: Arbitrary static variable initializers](https://wiki.php.net/rfc/arbitrary_static_variable_initializers)

### Improvements to the range() function

This RFC proposes changes to the semantics of the [`range()`](https://www.php.net/range) function, which currently generates an array of values from a start value to an end value with stipulated behavior for integers, floats, and strings.

The current `range()` function has shown to produce unexpected results with certain kinds of inputs and the RFC seeks to make the behavior more predictable and consistent.

The proposed changes include:
1. Checking for and throwing errors when unusable arguments are passed to `range()`.
2. Handling non-finite float values such as `-INF`, `INF`, `NAN`.
3. Throwing a more descriptive error when stepping is zero.
4. Emit warnings when trying to create a range of characters with a float step.
5. Conduct a stricter check on the start and end parameters to ensure they are int, float, or string. Any other types would result in a TypeError.
6. Existing calls to `range()` that return arrays of floats when the step can be an integer will now return an array of integers.

Learn more: [PHP RFC: Define proper semantics for range() function](https://wiki.php.net/rfc/proper-range-semantics)
