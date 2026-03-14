---
id: "01KKEW27BN44P89RAXD3Q7B8W8"
title: "Laravel array validation: rules for nested input"
slug: "laravel-array-validation"
author: "benjamincrozat"
description: "Validate arrays in Laravel with array rules, dot notation, and wildcards so nested input and repeated fields stay predictable."
categories:
  - "laravel"
published_at: 2023-12-09T00:00:00+01:00
modified_at: 2026-03-14T10:22:32Z
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/83gbtJw7ibUGsGD.jpg"
sponsored_at: null
---
## Introduction to array validation in Laravel

Laravel array validation lets you validate nested input, repeated form fields, and structured payloads with rules like `contacts.*.email` and `required|array`.

If you have ever handled dynamic form rows or JSON-like request data, this is the feature that keeps the structure predictable.

Let’s walk through the rules and patterns that make validating arrays in Laravel feel straightforward instead of fragile.

## Understanding array validation in Laravel

Whether you're dealing with simple key-value pairs or more intricate nested data, Laravel's array validation capabilities are both robust and intuitive.

The beauty of Laravel's validation lies in its simplicity and the peace of mind it brings, knowing that your data structures are handled correctly.

Imagine you're collecting data where users can list multiple contact details. To start off, Laravel makes defining validation rules for these array inputs a breeze. Here's an example:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'contacts' => 'required|array',
        'contacts.*.phone' => 'required|numeric',
        'contacts.*.email' => 'required|email',
    ]);

    // Do something with the validated data.
}
```

Notice how the `*` wildcard helps us apply the rules to each element within the contacts array. Laravel validation seamlessly takes care of these scenarios, ensuring that each piece of the array adheres to the rules we've set out.

## Validate the structure of your array

You can go a step further to ensure you receive the expected data format by validating the structure of arrays. Use the `array` rules again, but this time, specify the expected keys as a parameter:

```php
$request->validate([
    'contacts' => 'required|array:phone,email',
]);
```

Here's what will happen:
1. Laravel's validator expects the phone and email keys and won't pass if they're not present.
2. The validation will also fail if any additional key if passed. Talk about strictness!

## Custom error messages for array validation in Laravel

There's more to a great user experience than just robust validation. Custom error messages play a key role. Laravel allows us to define specific messages that are both helpful and user-friendly. Here's how you can customize error messages for array validation:

```php
public function store(Request $request)
{
    $validated = $request->validate([
        'contacts' => 'required|array',
        'contacts.*.phone' => 'required|numeric',
        'contacts.*.email' => 'required|email',
    ], [
        'contacts.*.email.required' => 'Please provide an email for each contact.',
    ]);

    // Do something with the validated data.
}
```

By using Laravel's validation, we can make sure our users receive feedback that's not only informative but also doesn't pull them out of their workflow.

If you are validating nested data and want the rest of that workflow to stay clean, these are the next Laravel reads I would open:

- [Write validation rules with less guesswork](/laravel-validation)
- [Pick up Laravel habits that keep projects easier to maintain](/laravel-best-practices)
- [Tighten the API decisions most Laravel apps get wrong](/laravel-restful-api-best-practices)
- [Get a clearer mental model of how Laravel fits together](/how-laravel-works)
