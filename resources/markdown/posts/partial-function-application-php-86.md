---
id: "01KKEW27HY58Y3MYEJ6GSR51BD"
title: "Partial Function Application in PHP 8.6, made easy"
slug: "partial-function-application-php-86"
author: "benjamincrozat"
description: "Partial function application in PHP 8.6 explained in plain language, with simple real examples for JSON responses, logging, DB connections, and jobs."
categories:
  - "php"
published_at: 2025-12-06T23:38:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/01KBTZ16GMQZ0E92DY6H98A2EK.jpeg"
sponsored_at: null
---
## Introduction to Partial Function Application

PHP 8.6 adds **partial function application** (PFA).
The RFC is here: [Partial Function Application (v2)](https://wiki.php.net/rfc/partial_function_application_v2).

Short idea: you write a *normal-looking* function call, leave some arguments as placeholders, and PHP gives you back a **new function** that waits for the missing arguments.

Two placeholders:

* `?` → “this one argument comes later”
* `...` → “all the remaining arguments come later”

Any call that contains `?` or `...` returns a closure instead of running immediately.

The rest of this post is just:

* how it works,
* then a few dead-simple but actually useful examples.

## The basic idea in plain PHP

Take this function:

```php
function price_with_vat(float $amount, float $vatRate): float
{
    return $amount * (1 + $vatRate);
}
```

Today, to “freeze” the VAT rate, you’d do:

```php
$withFrenchVat = fn (float $amount) => price_with_vat($amount, 0.20);
```

With PFA:

```php
$withFrenchVat = price_with_vat(?, 0.20);

$withFrenchVat(100); // 120
$withFrenchVat(59);  // 70.8
```

What happens:

* `price_with_vat(?, 0.20)` contains a `?`, so PHP does **not** call `price_with_vat`.
* Instead, it returns a closure that takes **one** argument (the `?`).
* Inside, that closure calls `price_with_vat($amount, 0.20)`.

Mental model: write the call you want, replace the “unknowns” with `?`. That gives you a function waiting for those unknowns.

## The syntax: `?` and `...`

There are only two new pieces to remember.

### `?` = one missing argument

Each `?` becomes one parameter on the new closure, in order.

```php
function format_name(string $first, string $last, string $title): string
{
    return $title . ' ' . $first . ' ' . $last;
}

$mrDoe = format_name(?, 'Doe', 'Mr.');

$mrDoe('John'); // "Mr. John Doe"
$mrDoe('Bob');  // "Mr. Bob Doe"
```

Here the closure has one parameter: `$first`.

Two placeholders → two parameters:

```php
$doctor = format_name(?, ?, 'Dr.');

$doctor('Jane', 'Smith'); // "Dr. Jane Smith"
```

### `...` = the rest of the arguments

`...` means “I’ll give you **all remaining arguments** later, in order”.

```php
function log_message(
    string $channel,
    string $level,
    string $message,
    array $context = [],
): void {
    // ...
}

$infoLog = log_message('app', 'info', ...);
```

Now `$infoLog` behaves like:

```php
$infoLog = function (string $message, array $context = []): void {
    return log_message('app', 'info', $message, $context);
};
```

You fixed `channel` and `level`, and left `message` + `context` for later.

You can mix `?` and `...` too:

```php
// Level comes later, then "the rest"
$flexLog = log_message('app', ?, ...);

// behaves like:
// fn (string $level, string $message, array $context = []) => log_message('app', $level, $message, $context);
```

## Example #1: JSON HTTP responses

This is something that shows up in basically every app.

```php
function json_response(
    array $data,
    int $status = 200,
    array $headers = [],
): void {
    http_response_code($status);

    foreach ($headers as $name => $value) {
        header($name . ': ' . $value);
    }

    header('Content-Type: application/json');
    echo json_encode($data);
}
```

### Pre-PFA

```php
$okJson = fn (array $data) => json_response($data, 200);
$createdJson = fn (array $data) => json_response($data, 201);
```

### With PFA

```php
$okJson      = json_response(?, 200);
$createdJson = json_response(?, 201);

// Later:
$okJson(['status' => 'ok']);
$createdJson(['id' => 123]);
```

Want CORS headers always on?

```php
$apiJson = json_response(
    ?,
    200,
    ['Access-Control-Allow-Origin' => '*'],
);

$apiJson(['data' => 'hello']);
```

Clear, short, and reusable.

## Example #2: logger shortcuts

Say you have one central logger:

```php
function log_message(
    string $channel,
    string $level,
    string $message,
    array $context = [],
): void {
    // send to log storage…
}
```

Without PFA, tiny wrappers pile up:

```php
$info = fn (string $message, array $context = []) =>
    log_message('app', 'info', $message, $context);

$error = fn (string $message, array $context = []) =>
    log_message('app', 'error', $message, $context);
```

With PFA:

```php
$appInfo  = log_message('app', 'info', ...);
$appError = log_message('app', 'error', ...);

$appInfo('User logged in', ['user_id' => $id]);
$appError('Payment failed', ['charge_id' => $chargeId]);
```

Or even channel-specific:

```php
$securityInfo = log_message('security', 'info', ...);

$securityInfo('Suspicious login', ['ip' => $ip]);
```

You essentially turn “configuration decisions” into little functions.

## Example #3: database connection presets (named arguments)

Named arguments make PFA even more readable.

```php
function connect_db(
    string $host,
    int $port,
    string $user,
    string $password,
    bool $persistent = false,
): PDO {
    // ...
}
```

A local development connection:

```php
$connectLocal = connect_db(
    host: '127.0.0.1',
    port: 3306,
    user: ?,
    password: ?,
);

$pdo = $connectLocal('root', 'secret');
```

Here the closure takes two arguments: `$user`, then `$password`.

A production read-only preset:

```php
$connectProdRead = connect_db(
    host: '10.0.0.5',
    port: 3306,
    user: 'app_read',
    password: ?,
);

$pdo = $connectProdRead($_ENV['DB_READ_PASSWORD']);
```

Key point: placeholders (`?`) define the parameter order of the resulting closure. In the example above, the closure only has one parameter: the password.

## Example #4: “ready-made job” for a queue with `...`

Sometimes the goal is: “package this exact call and run it later”.

```php
function send_report(int $userId, string $period): void
{
    // generate report and email it
}
```

### Pre-PFA

```php
$job = fn () => send_report($userId, '2024-Q4');
$queue->push($job);
```

### With PFA

```php
$job = send_report($userId, '2024-Q4', ...);

$queue->push($job);
```

Because of the trailing `...`:

* All arguments are already provided.
* PHP returns a **zero-argument** closure that just calls `send_report($userId, '2024-Q4')` when executed.

Later:

```php
$job(); // sends the report
```

Same behavior, one less wrapper function.

## Rules and gotchas to keep in mind

A few important details so this doesn’t bite later.

### When does PHP actually call the original function?

If the call contains **no** `?` and **no** `...`, it runs immediately:

```php
price_with_vat(100, 0.20); // normal call
```

If the call contains **any** `?` or `...`, PHP returns a closure instead:

```php
$fn = price_with_vat(?, 0.20); // closure, not a float
```

So be careful not to accidentally use `?` where you didn’t mean it; you’ll get a function instead of a value.

### Each `?` becomes one parameter

This is the rule that drives everything:

* Placeholders are processed from left to right.
* Each `?` becomes a parameter on the generated closure, in that order.

If the function has more parameters than you cover, you can use `...` to sweep up the rest.

### Fixed arguments are evaluated immediately

Partial application stores **values**, not expressions.

```php
$partial = json_response(
    ['env' => getenv('APP_ENV')],
    200,
    ...,
);
```

`getenv('APP_ENV')` runs when `$partial` is created, not when the closure is called.

If the value must always be fresh, use a normal closure instead.

### No PFA on `new`

This is not allowed:

```php
$userFactory = new User(?, 'guest'); // invalid
```

Use a static factory (or normal function) and apply that:

```php
class User
{
    public function __construct(
        public string $name,
        public string $role,
    ) {}

    public static function make(string $name, string $role): self
    {
        return new self($name, $role);
    }
}

$userFactory = User::make(?, 'guest');

$bob = $userFactory('Bob');
```

## Summary

Partial function application in PHP 8.6 is basically:

* Write a normal call.
* Replace unknown arguments with `?` or `...`.
* Get back a closure that waits for those unknowns.

It’s boring in the best way possible:

* Less callback boilerplate.
* Cleaner configuration helpers (VAT, DB connections, loggers, responses).
* Easy “run this exact call later” closures using trailing `...`.

If you want a few more PHP rabbit holes after this:

- [Making sense of PHP's array_map() function](/php-array-map)
- [PHP 8.6: release date and confirmed features](/php-86)
- [Understanding array_filter() in PHP](/php-array-filter)

