---
id: "01KKEW278JA8BERMSW9B104EPW"
title: "How to customize middleware in Laravel 11+"
slug: "customize-middleware-laravel-11"
author: "benjamincrozat"
description: "The new minimalist application skeleton in Laravel 11 comes without middleware classes. Here's how to customize them."
categories:
  - "laravel"
published_at: 2024-02-25T00:00:00+01:00
modified_at: 2025-07-11T07:00:00+02:00
serp_title: "How to customize middleware in Laravel 11+ (2025)"
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/Jioo0Z1aYBa1GWY.png"
sponsored_at: null
---
## Introduction

Starting from [Laravel 11](https://laravel.com/docs/11.x/releases), new projects get to experience a slimmer skeleton. Parts of the efforts to make it happen were to remove the default middleware classes.

But how do you customize them then? Easy! Just go into your *bootstrap/app.php* file and configure them however you want. Let me show you in more detail for the most common use cases.

## Customize Laravel's middleware

### Change where guests are redirected

To customize where guests are redirected, use the `redirectGuestsTo()` method in _bootstrap/app.php_:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo('/admin/login');
})
```

Previously, this was happening in the *Authenticated.php* middleware file.

### Change where users and guests are redirected

To customize where users and guests are redirected, use the `redirectTo()` method in _bootstrap/app.php_:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectTo(
        guests: '/admin/login',
        users: '/dashboard'
    );
})
```

Previously, this was happening in the *Authenticated.php* and *RedirectIfAuthenticated.php* middleware files.

### Exclude cookies from being encrypted

To customize which cookies must not be encrypted, use the `encryptCookies()` method in _bootstrap/app.php_:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->encryptCookies(except: [
        'foo',
        'bar',
    ]);
})
```

Previously, this was happening in the *EncryptCookies.php* middleware file.

### Exclude routes from CSRF protection

To customize which routes must be excluded from CSRF protection, use the `validateCsrfTokens()` method in _bootstrap/app.php_:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateCsrfTokens(except: [
        '/foo/*',
        '/bar',
    ]);
})
```

Previously, this was happening in the *VerifyCsrfToken.php* middleware file.

### Exclude routes from URL signature validation

To exclude routes from URL signature validation, use the `validateSignatures()` method in _bootstrap/app.php_:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->validateSignatures(except: [
        '/api/*',
    ]);
})
```

Previously, this was happening in the *ValidateSignature.php* middleware file.

### Prevent converting empty strings in requests

To configure the middleware that converts empty strings to null, use the `convertEmptyStringsToNull()` method in _bootstrap/app.php_:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->convertEmptyStringsToNull(except: [
        fn ($request) => $request->path() === 'foo/bar',
    ]);
})
```

Previously, you had to remove the `ConvertEmptyStringsToNull` middleware in the *app/Http/Kernel.php* file or do it on a per route basis.

### Prevent string trimming in requests

To configure the middleware responsible for trimming strings, use the `trimStrings()` method in _bootstrap/app.php_:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trimStrings(except: [
        '/foo',
    ]);
})
```

Previously, this was happening in the *TrimStrings.php* middleware file.

## Advanced middleware customization

In addition to the basic customizations we've covered, Laravel 11 offers more advanced options for middleware manipulation. These can be particularly useful for complex applications or when you need fine-grained control over your middleware stack.

### Manipulating the global middleware stack

You can add, remove, or replace middleware in the global stack:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->prepend(SomeMiddleware::class);
    $middleware->append(AnotherMiddleware::class);
    $middleware->remove(UnwantedMiddleware::class);
    $middleware->replace(OldMiddleware::class, NewMiddleware::class);
})
```

### Working with middleware groups

Laravel allows you to define and modify middleware groups:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->group('custom', [
        FirstMiddleware::class,
        SecondMiddleware::class,
    ]);
    
    $middleware->prependToGroup('web', NewWebMiddleware::class);
    $middleware->appendToGroup('api', NewApiMiddleware::class);
    $middleware->removeFromGroup('web', OldWebMiddleware::class);
    $middleware->replaceInGroup('api', OldApiMiddleware::class, NewApiMiddleware::class);
})
```

### API-specific configurations

For API-focused applications, Laravel provides specific methods:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->statefulApi(); // Enable Sanctum's frontend state middleware.
    $middleware->throttleApi('custom_limiter', true); // Configure API rate limiting.
})
```

### Configuring trusted proxies and hosts

For applications behind a proxy or load balancer:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->trustHosts(['example.com', '*.example.com']);
    $middleware->trustProxies(['192.168.1.1', '192.168.1.2']);
})
```

### Enabling authenticated sessions

To ensure sessions are authenticated in the "web" middleware group:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->authenticateSessions();
})
```

These advanced options provide more flexibility in customizing your application's middleware behavior, allowing you to fine-tune security, performance, and functionality as needed.

If you want a few more Laravel rabbit holes after this:

- [Laravel 11 is out! Here are every new big changes and features.](/laravel-11)
- [How to publish API and broadcasting routes in Laravel 11](/install-route-files-laravel)
- [25 Laravel best practices, tips, and tricks](/laravel-best-practices)

