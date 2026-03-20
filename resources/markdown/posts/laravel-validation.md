---
id: "01KKEW27E8F6VWDYEH2RG5FC1M"
title: "Laravel validation: rules, form requests, and custom rules"
slug: "laravel-validation"
author: "benjamincrozat"
description: "Validate incoming data in Laravel with `validate()`, Form Requests, custom rules, nested arrays, and clear error messages."
categories:
  - "laravel"
published_at: 2024-02-01T00:00:00+01:00
modified_at: 2026-03-20T12:41:41Z
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/abZxAj9dlqBjeKf.jpg"
sponsored_at: null
---
## Introduction to validation in Laravel

Laravel validation is the first filter for incoming request data. In most apps, you will either use `$request->validate()` for a quick check or move the rules into a Form Request when they deserve their own class.

This guide keeps the examples practical: start with the fast path, then add custom rules only when Laravel's built-in rules are not enough.

## The basics of Laravel validation

Imagine you're building a form on your website where visitors can sign up for a newsletter. You want a name and a valid email address, and you want Laravel to reject anything else before your controller does more work.

In Laravel, you can validate incoming data very easily. Let's say you have a route for submitting the newsletter form:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/newsletter', function (Request $request) {
    $validatedData = $request->validate([
        'name' => 'required|min:3',
        'email' => 'required|email',
    ]);

    // Process the validated data…
});
```

In this piece of magic, `$request->validate()` checks that:
- The name is there (`required`) and it's at least 3 characters long (`min:3`).
- The email is present and formatted like an actual email.

If the data doesn't pass muster, Laravel automatically redirects the user back to the form, flashing the error details (accessible in your Blade templates via the `$errors` variable). If it passes, your validated data is good to go.

## Extracting the validation logic

You've seen validation rules within your routes, but sometimes, especially for complex forms, you might want to separate concerns a bit more. That's where **Form Request Validation** comes into play, a more organized way to handle validation logic.

First, create a custom form request:

```shell
php artisan make:request StoreNewsletterRequest
```

This command scaffolds a class where you can define your validation rules:

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Anyone can submit the form
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:3',
            'email' => 'required|email',
        ];
    }
}
```

Then, use it in your controller method:

```php
public function store(StoreNewsletterRequest $request)
{
    // Data has been validated, you can now access it.
    $validated = $request->validated();
}
```

By extracting to a Form Request, your controller stays clean and your validation logic stays in one place.

## More validation rules provided by Laravel

Laravel offers a wide variety of built-in validation rules for different scenarios: checking for numbers, ensuring uniqueness in the database, validating file uploads, and more.

For instance, verifying a user's age could be as simple as:

```php
'age' => 'required|integer|min:18',
```

This ensures the age is provided (`required`), is a number (`integer`), and is at least 18 (`min:18`).

You can [check out every validation rule](https://laravel.com/docs/13.x/validation#available-validation-rules) Laravel provides in the official documentation.

## Custom error messages

Sometimes, you want to provide specific feedback when validation fails. Laravel lets you customize error messages for each rule easily:

```php
$request->validate([
    'email' => 'required|email',
], [
    'email.required' => 'We definitely need your email address!',
    'email.email' => "Hmm, that doesn't look like a valid email.",
]);
```

This way, you make your app not just more user-friendly, but also more unique and tailored to your audience.

## Advanced validation concepts

As you delve deeper, you might encounter scenarios needing more than just basic validation rules. Here, Laravel’s ability to define **custom validation rules** comes to the rescue.

Creating a custom rule is straightforward. For example, let's create a rule ensuring a string is uppercase (that would be dumb in a real-world project, but let's keep it simple for now). First, generate the rule:

```shell
php artisan make:rule Uppercase
```

Then, define its behavior:

Laravel 13 uses the `ValidationRule` contract. That means a custom rule should define `validate()` instead of the older `passes()` / `message()` shape:

```php
<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Uppercase implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (strtoupper((string) $value) !== $value) {
            $fail('The :attribute must be uppercase.');
        }
    }
}
```

And finally, you can use it wherever you need it:

```php
use App\Rules\Uppercase;

$request->validate([
    'last_name' => ['required', new Uppercase],
]);
```

Using custom rules keeps your validation expressive while still letting Laravel handle the boring parts.

## Validating nested data (or arrays)

Dealing with arrays or JSON payloads? Laravel's got your back with dot notation and the `*` wildcard for array data:

```php
'person' => 'required|array',
'person.*.email' => 'email|unique:users',
```

This rule checks that each `email` in the required `person` array is unique in the `users` table.

Want to learn more about validating arrays? Here a dedicated article: [Easy data integrity with array validation in Laravel](https://benjamincrozat.com/laravel-array-validation)

## Displaying error messages and custom responses

Laravel makes handling validation errors straightforward. They are flashed to the session, making them available on redirection. For AJAX requests, Laravel responds with a JSON payload containing the errors.

In your Blade templates, displaying errors is easy peasy. For instance, you might want to display them at the top of your form like so:

```blade
@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
```

Or display them below your fields:

```blade
<div>
    <label for="name">
        Name
    </label>
    
    <input 
        type="text" 
        id="name" 
        name="name" 
        value="{{ old('name') }}" 
        placeholder="Ned Flanders"
    />
    
    @error('name')
        <p>{{ $message }}</p>
    @enderror
</div>
```

## Become a true expert thanks to Mastering Laravel Validation Rules

[![Mastering Laravel Validation Rules by Aaron Saray and Joel Clermont](https://imagedelivery.net/hYERsDhHaFG137wdGnWeuA/images/posts/imported/best-laravel-books-ad06f8b6afb0eba301f6.webp/public)](/recommends/mastering-laravel-validation-rules)

Let me tell you: [Mastering Laravel Validation Rules](/recommends/mastering-laravel-validation-rules) is a game-changer. Beginner or knee-deep in Laravel development, this book has something for everyone. I’ve been using Laravel for more than 8 years, and I still learned a ton.

The real-world examples help understand concepts faster and better (in Laravel’s official documentation, this often is an issue). The authors, Aaron and Joel, walk you through scenarios like validating addresses, phone numbers, transferring digital assets, and so much more. It’s clear they've been there and are now handing you the solutions on a silver platter.

If you're working with Laravel, do yourself a favor and get your hands on this book. Open it when you’re looking at a rule you don’t understand and when you’re not sure how to handle a certain type of value.

[Check Mastering Laravel Validation Rules](/recommends/mastering-laravel-validation-rules)

## Conclusion

Validation is a crucial part of any web application, and Laravel offers one of the most powerful and flexible systems to ensure your data integrity.

The key to mastering Laravel validation is practice:
- Experiment with different rules.
- Use them in real projects.
- Try out custom rules whenever you have unconventional needs.

You can do this!

If validation is becoming the backbone of how you keep data clean, these are the Laravel reads I would open next:

- [Validate nested arrays in Laravel without losing your mind](/laravel-array-validation)
- [Pick up Laravel habits that keep projects easier to maintain](/laravel-best-practices)
- [Tighten the API decisions most Laravel apps get wrong](/laravel-restful-api-best-practices)
- [Use database transactions when partial writes would hurt](/database-transactions-laravel)
- [Protect your API with Laravel Sanctum before it gets exposed](/laravel-sanctum-api-tokens-authentication)
