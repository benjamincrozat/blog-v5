---
id: "01KKEW2790ZCVBRK942WNFQJ2R"
title: "Mastering error handling in Laravel's HTTP client"
slug: "error-handling-laravel-http-client"
author: "benjamincrozat"
description: "Learn how to use Laravel's HTTP client for efficient error handling and exception throwing in different scenarios with ease."
categories:
  - "laravel"
published_at: 2023-08-31T00:00:00+02:00
modified_at: 2023-09-19T00:00:00+02:00
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/F9RtgRPtCaMmCwr.jpg"
sponsored_at: null
---
## Introduction

Today, we're going to take a look at how you can utilise Laravel's HTTP client to manage and throw exceptions. Efficient error handling is absolutely crucial for a smooth user experience.

So, grab a cup of coffee, because we're about to make error-handling a piece of cake!

## How to throw when an error occurs

Laravel's HTTP client is a powerful tool that comes in handy when issuing HTTP requests to APIs and other resources. One of the key ways you'll interact with this client is by throwing exceptions when things go wrong.

Consider a scenario where we're making a POST request to an API endpoint, and we want to throw an exception if an error occurs. Here's a simple way you could achieve this:

```php
$response = Http::post('https://api.example.com/posts', [
    //
]);

$response->throw();
```

In the code snippet above, we're sending a POST request to an API and throwing an error if a client or server-side error occurs. The entire process is conveniently wrapped in Laravel's fluid interface!

## Conditionally throw exceptions when an error occurs

Now let's say you want to throw an exception based on a particular condition or error - Laravel's HTTP client has you covered!

Let's take a case where you want to throw an exception if a certain condition is true:

```php
$response->throwIf($conditionIsTrue);
```

Here `$conditionIsTrue` is a variable which could be a boolean based on the condition you are checking.

Laravel's HTTP Client also accommodates closures, providing a straightforward way to deal with more complex conditions:

```php
$response->throwIf(function (Response $response) {
    // Check using a more complex condition.
});
```

## Handling specific error status codes

Laravel's HTTP client also enables you to check the response's status code to determine whether to throw an exception. 

```php
// This will throw an exception if the response status code is 403.
$response->throwIfStatus(403);

// This will throw an exception unless the response status code is 200.
$response->throwUnlessStatus(200);
```

These options provide a very granular level of control.

In the first example, an exception will be thrown only when status is 403. Even 404 won't throw one. And in the second example, every status code will throw an exception besides 200.

That's way less flexible than the default behavior, but needed in some use cases.

## Taking action before throwing exceptions for errors

Depending on the kind of error, you might need to perform some operations before throwing an exception. Laravel's HTTP client is flexible in this sense - you can pass a closure to the `throw()` method to perform additional logic before the exception is thrown.

```php
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

return Http::post('https://api.example.com/posts', [
    //
])->throw(function (Response $response, RequestException $e) {
    // Perform other operations here.
})->json();
```

And there you have it, folks! With Laravel's HTTP client, handling exceptions has never been easier.

If you are building anything that talks to outside services, these are the next Laravel reads I would keep within reach:

- [Tighten the API decisions most Laravel apps get wrong](/laravel-restful-api-best-practices)
- [Make your Laravel tests more useful before the suite grows](/laravel-testing-best-practices)
- [Write validation rules with less guesswork](/laravel-validation)
- [Validate nested arrays in Laravel without losing your mind](/laravel-array-validation)
- [Protect your API with Laravel Sanctum before it gets exposed](/laravel-sanctum-api-tokens-authentication)
