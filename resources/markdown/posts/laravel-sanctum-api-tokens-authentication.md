---
id: "01KKEW27DR7SXVZV7VGE0ZJAF9"
title: "Laravel Sanctum API tokens: issue and revoke them"
slug: "laravel-sanctum-api-tokens-authentication"
author: "benjamincrozat"
description: "Use Laravel Sanctum to issue API tokens, protect routes with `auth:sanctum`, and revoke tokens when users no longer need them."
categories:
  - "laravel"
  - "packages"
  - "security"
published_at: 2024-01-16T00:00:00+01:00
modified_at: 2026-03-20T12:41:41Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/JbUCltgFbjrgpDm.jpg"
sponsored_at: null
---
## Introduction to Laravel Sanctum and how it helps securing REST APIs

[Laravel Sanctum](https://laravel.com/docs/sanctum) gives Laravel apps a lightweight way to handle SPAs, mobile apps, and simple token-based APIs. This guide focuses on the token side: issuing API tokens, protecting routes with `auth:sanctum`, and revoking tokens later.

If you only need one takeaway, it is this: Sanctum is simpler than OAuth when you want personal access tokens for your users.

## Install Laravel Sanctum via Composer

If Sanctum is not already installed in your project, install it with Composer:

```bash
composer require laravel/sanctum
```

Once done, publish Sanctum's configuration and migration files:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

Finally, run your database migrations:

```bash
php artisan migrate
```

## Issue API tokens to your users

You need to let your users generate tokens to consume your API.

Add the `Laravel\Sanctum\HasApiTokens` trait in your `User` model:

```php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
```

You can issue a token using the `createToken` method:

```php
$token = $user->createToken('token-name')->plainTextToken;
```

Make sure to tell the user that the plain-text token is only shown once. If they lose it, they will need to generate a new one.

## Protect your REST API routes with Sanctum's auth guard

To secure your API routes, use the `auth:sanctum` middleware. Sanctum will accept either a valid bearer token or a stateful request from your own SPA:

```php
Route::middleware('auth:sanctum')
    ->get('/api/user', function (Request $request) {
        return $request->user();
    });
```

That split is the useful part to remember. Sanctum can authenticate an SPA with cookies or a third-party client with a bearer token, so you do not need to reach for OAuth just to cover the common cases.

## Manage your users' API tokens

Managing tokens is crucial for security. To revoke them, use:

```php
// Revoke all tokens.
$user->tokens()->delete();

// Revoke a specific token.
$user->tokens()->where('id', $tokenId)->delete();
```

## Conclusion

Securing a Laravel API with Sanctum is a good fit when you want simple token auth without adding OAuth complexity.

There's a lot more to Laravel Sanctum and I encourage you to go read the [official documentation](https://laravel.com/docs/sanctum).

If you are hardening an API instead of just getting auth to work once, these are the Laravel reads I would open:

- [Close the Laravel security gaps that are easy to miss](/laravel-security-best-practices)
- [Fix the 419 error before it keeps breaking forms](/419-page-expired-laravel)
- [Tighten the API decisions most Laravel apps get wrong](/laravel-restful-api-best-practices)
- [Fix the missing app key error before anything else breaks](/laravel-no-application-key-specified)
- [Write validation rules with less guesswork](/laravel-validation)
