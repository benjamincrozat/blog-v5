---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/a2cb9c42-0576-4a4e-9dc5-a83c06e9e484
Title: "Validation in Laravel made easy"
Description: "Learn how to validate incoming data in your Laravel applications, from the basics to more advanced concepts."
Published at: 2024-02-01
Categories: laravel
---

## Introduction to validation in Laravel

Validation is like the bouncer at the door of your web application. It ensures that only the good data gets in, keeping the messy, unwanted stuff out. (I really love this analogy! 😅)

Laravel, being the friendly and powerful PHP framework it is, offers a robust suite of tools to make validating data a breeze (pun intended).

Let's dive into validation with Laravel, starting simple and gradually exploring more complex scenarios.

## The basics of Laravel validation

Imagine you're building a form on your website where visitors can sign up for a newsletter. You'll want to make sure they provide a name and a valid email address. Here's where Laravel's validation shines.

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
    public function authorize()
    {
        return true; // Anyone can submit the form
    }

    public function rules()
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
    $validated = $request→validated();
}
```

By extracting to a form request, your controller stays clean, and your validation logic remains neatly encapsulated.

## More validation rules provided by Laravel

Laravel offers a wide variety of built-in validation rules for different scenarios: checking for numbers, ensuring uniqueness in the database, validating file uploads, and more.

For instance, verifying a user's age could be as simple as:

```php
'age' => 'required|integer|min:18',
```

This ensures the age is provided (`required`), is a number (`integer`), and is at least 18 (`min:18`).

You can [check out every validation rules](https://laravel.com/docs/validation#available-validation-rules) Laravel provides on the official documentation. 

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

```php
namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Uppercase implements ValidationRule
{
    public function passes(string $attribute, mixed $value, Closure $fail)
    {
        if (strtoupper($value) !== $value) {
            return $fail(':attribute must be uppercase.');
        }
    }
}
```

And finally, you can use it wherever you like:

```php
use App\Rules\Uppercase;

$request->validate([
    'last_name' => ['required', new Uppercase],
]);
```

Using custom rules makes your application logic remain expressive while addressing specific requirements.

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

[![Mastering Laravel Validation Rules by Aaron Saray and Joel Clermont](https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/49cfa0c3-237d-4967-b7b7-0a93dca71d1a)](/recommends/mastering-laravel-validation-rules)

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
