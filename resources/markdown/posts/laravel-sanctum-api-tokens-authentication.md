---
id: "01KKEW27DR7SXVZV7VGE0ZJAF9"
title: "Secure your REST API in 5 minutes with Laravel Sanctum"
slug: "laravel-sanctum-api-tokens-authentication"
author: "benjamincrozat"
description: "Quickly secure a REST API using Laravel Sanctum by letting your users generate tokens."
categories:
  - "laravel"
  - "packages"
  - "security"
published_at: 2024-01-16T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/JbUCltgFbjrgpDm.jpg"
sponsored_at: null
---
## Introduction to Laravel Sanctum and how it helps securing REST APIs

[Laravel Sanctum](https://laravel.com/docs/sanctum) is a package for Laravel that provides a simple way to secure your REST API. For instance, in case you want your users to be able to build services top of your application.

That being said, the official documentation is extensive and you probably don't have that kind of time. So I hope my quick guide will serve you well.

## Install Laravel Sanctum via Composer

The package now comes installed by default in any new Laravel application.

If for some reason you don't have Laravel Sanctum in your project, install it using Composer:

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

use Laravel\Sanctum\HasApiTokens; // [tl! ++]

class User extends Authenticatable
{
    use HasApiTokens; // [tl! ++]
}
```

You can issue a token using the `createToken` method:

```php
$token = $user->createToken('token-name')->plainTextToken;
```

Make sure to let the user know that the token is only shown once. If they lose it, they'll have to generate a new one.

## Protect your REST API routes with Sanctum's auth guard

To secure your API routes, use the `sanctum` guard. This ensures that all incoming requests are authenticated:

```php
Route::middleware('auth:sanctum')
    ->get('/api/user', function (Request $request) {
        return $request->user();
    });
```

## Manage your users' API tokens

Managing tokens is crucial for security. To revoke them, use:

```php
// Revoke all tokens.
$user->tokens()->delete();

// Revoke a specific token.
$user->tokens()->where('id', $tokenId)->delete();
```

## Conclusion

Securing your REST API with Laravel Sanctum is an effective way to manage authentication and prevent misuses without overcomplicating everything.

There's a lot more to Laravel Sanctum and I encourage you to go read the [official documentation](https://laravel.com/docs/sanctum).

If you are hardening an API instead of just getting auth to work once, these are the Laravel reads I would open:

- [Close the Laravel security gaps that are easy to miss](/laravel-security-best-practices)
- [Fix the 419 error before it keeps breaking forms](/419-page-expired-laravel)
- [Tighten the API decisions most Laravel apps get wrong](/laravel-restful-api-best-practices)
- [Fix "No application encryption key has been specified." in Laravel](/laravel-no-application-key-specified)
- [Write validation rules with less guesswork](/laravel-validation)
