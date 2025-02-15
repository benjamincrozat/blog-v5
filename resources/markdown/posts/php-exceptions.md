---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/20/programming_lferts.jpg
Title: "PHP try & catch: what are exceptions and how to handle them?"
Description: "Take your code to the next level, thanks to exceptions. Handle errors in a more graceful way within try and catch blocks."
Published at: 2022-11-20
Modified at: 2022-11-23
Categories: php
---

## What are exceptions?

Like in many other languages, exceptions in PHP are errors that can be caught and gracefully handled instead of crashing your app.

## When to use exceptions?

Use exceptions when you need to gracefully handle an unexpected event instead of just crashing like a fatal error.

## Throw exceptions

An exception can be thrown using the `throw` keyword on an instance of the `Exception` class. The first parameter is a string describing your error.

Here's an example where we need to validate the user's input:

```php
if (empty($_POST['age'])) {
    throw new Exception('Your age is missing!');
}
```

When you run this code, you will see:

```
Fatal error: Uncaught Exception: Your age is missing!
```

That's because we now need to catch our exception and do something with it.

## Try catch example to handle exceptions

In PHP, exceptions are handled inside a `try` and `catch` block.

In this example, we catch the exception thrown first and display it above our form.

```php
<?php

try {
    if (empty($_POST['age'])) {
        throw new Exception('We need to know how old you are.');
    }
    
    if ($_POST['age'] < 18) {
        throw new Exception('You are too young.');
    }
} catch (Exception $exception) {
    $error = $exception->getMessage();
}
?>

<?php if (! empty($error)) : ?>
    <p><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" action="./">
    <input type="number" name="age" />
    <button type="submit">Send</button>
</form>
```

*OK, but I can do the same without exceptions!*

True. But let's ramp up the difficulty here.

### Catch multiple exceptions

Imagine we have a custom exception for each type of error.

```php
<?php

// Defining custom exceptions is as simple as that.
class NotFoundException extends Exception {}

class ValidationException extends Exception {}

try {
    if (empty($_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] !== '/form') {
        throw new NotFoundException();
    }

    if (empty($_POST['age'])) {
        throw new ValidationException('We need to know how old you are.');
    }

    if ($_POST['age'] < 18) {
        throw new ValidationException('You are too young.');
    }

    header('Location: /secret-location');

    exit;
} catch (NotFoundException $exception) {
    http_response_code(404);

    exit('Page not found.');
} catch (ValidationException $exception) {
    $validationError = $exception->getMessage();
}
?>

<?php if (! empty($validationError)) : ?>
    <p><?php echo $validationError; ?></p>
<?php endif; ?>

<form method="POST" action="/form">
    <input type="number" name="age" />
    <button type="submit">Send</button>
</form>
```

1. If the URL in our browser is not */form*, we throw a `NotFoundException` and display a simple 'Page not found' in our `catch` block;
2. If our age is empty or below 18, we throw a `ValidationException` and display the error above the form;
3. Then, we can redirect the user to our secret page. If we get to this stage, no exception was thrown.

As you can see, the clarity of our code just got a step higher, thanks to exceptions.

If you still don't get why they're helpful, don't worry; it's okay. Time and practice will grant you the rank of master.

## The finally block

[Introduced with PHP 5.5](https://wiki.php.net/rfc/finally), the `finally` block contains code that will **always** be executed.

```php
try {
    // This exception won't be handled.
    throw new SomeException;
} finally {
    echo 'This code always runs.';
}

echo "But this code won't.";
```

In the example above:
1. Only one `finally` block is allowed;
2. Even when an exception thrown from the `try` block isn't handled, the `finally` block will be executed;
3. All code coming after the `finally` block won't run.

Some good use cases for using the finally block in PHP include:

- Ensuring that resources, such as database connections, are properly closed and released after the code in the try and catch blocks have been executed.
- Cleaning up or resetting any global state or variables that may have been changed during the execution of the try and catch blocks.
- Logging any errors or exceptions that occurred during the execution of the try and catch blocks to diagnose and fix issues in the code.
- Performing any necessary actions, such as sending an email notification or redirecting the user to a different page, after the code in the try and catch blocks has been executed.
- Wrapping up any complex or lengthy operations, such as image manipulation or file processing, by ensuring that all necessary steps have been completed and any intermediate results have been cleaned up.

## Catch all exceptions

To catch all exceptions in PHP, you must define a global exception handler with `set_exception_handler`.

`set_exception_handler` accepts a closure as an argument.

```php
set_exception_handler(function (Exception $exception) {
    // Do what you were doing in your catch block.
});
```

You can also use a class method (static or not, it doesn't matter) and tell `set_exception_handler` which one it is using a [callable](https://www.php.net/manual/en/language.types.callable.php).

Example:

```php
class ExceptionHandler {
    public static function handle(Exception $exception) {
        //
    }
}

set_exception_handler(ExceptionHandler::handle(...));
```

