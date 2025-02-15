---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/b5ab883f-6840-4cb7-8c8f-e36d3c30c533
Title: "The ultimate guide to Laravel's new Number helper"
Description: "Working with numbers in your web applications just got a whole lot easier! Discover what's possible using Laravel's new Number helper."
Published at: 2023-12-06
Categories: laravel
---

## Introduction to the Laravel Number helper class

Working with numbers in your web applications just got a whole lot easier! 🔥

The Laravel number helper is a new utility class designed to make number-related tasks a breeze. Think formatting, currencies, and even converting bytes into human-readable file sizes.

This handy tool comes packed with methods that cater to different aspects of number manipulation, so let's dive into some of the key methods that you can use right away.

## Set Laravel's Number helper locale

When dealing with international applications, setting the correct locale is crucial for number formatting. The `useLocale` method allows you to set the default locale which will influence how numbers are presented.

To set it up, add the following code in the `boot()` method of your `AppServiceProvider` class:

```php
use Illuminate\Support\Number;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Number::useLocale($this->app->getLocale());
    }
}
```

Need to execute a function with a specific locale temporarily? No worries! Use the `withLocale` method to take care of that. Here's how to proceed:

```php
use Illuminate\Support\Number;

// Temporarily use German locale for a closure execution.
$germanNumber = Number::withLocale('de', fn () => Number::format(1234567.89));

echo $germanNumber; // "1.234.567,89"
```

In the above snippet, you see how smoothly "laravel number helper" can adapt to different locales, making your numbers relatable to a global audience.

## Format numbers

The `format` method is your go-to for getting numbers to look their best. It takes care of things like decimal points and digit grouping according to your specified locale preferences. Check this out:

```php
$formattedNumber = Number::format(1234.56, 2);

echo $formattedNumber; // "1,234.56"
```

This example showcases the "laravel number helper" with a precision of two decimal places. Simple and straight to the point!

## Convert numbers to words

Ever needed to spell out a number in full text? The `spell` method of the "laravel number helper" transforms numbers into words, perfect for checks or formal documents.

```php
$numberInWords = Number::spell(102);

echo $numberInWords; // "one hundred and two"
```

## Generate ordinal numbers ("1st", "2nd", "3rd", etc.)

The `ordinal` method helps express numbers in their ordinal form. Whether it's 1st, 2nd, 3rd, or beyond, see what it looks like here:

```php
$ordinalNumber = Number::ordinal(21);

echo $ordinalNumber; // "21st"
```

Laravel's Number helper gracefully handles this conversion, so you don't have to.

## Format percentages

Percentages are everywhere – discounts, statistics, you name it. With Laravel's Number helper, the `percentage` method renders percentages neatly.

```php
$percentage = Number::percentage(75.125, 1);

echo $percentage; // "75.1%"
```

The example shows how the method factors in precision for your percentage values.

## Format currencies

Currency formatting is crucial for any financial application. The `currency` method in "laravel number helper" ensures your monetary values are readable and professional.

```php
$currencyValue = Number::currency(5000, 'EUR');

echo $currencyValue; // "€5,000.00"
```

Global currencies are no hurdle for Laravel's Number helper.

## Determine file sizes

Understanding file sizes in bytes can be baffling. The `fileSize` method converts bytes into a file size that makes sense to anyone. Let's say you have 2048 bytes:

```php
$fileSize = Number::fileSize(2048);

echo $fileSize; // "2 KB"
```

Laravel's Number helper simplifies file size presentation.

## Abbreviate large numbers (Laravel 10.35)

The `abbreviate` method is perfect for large numbers that need simplifying. It's useful for social media stats or any place where space is at a premium.

```php
$abbreviatedNumber = Number::abbreviate(2500000);

echo $abbreviatedNumber; // "2.5M"
```

This is the Laravel's Number helper at its most concise.

## Show large numbers in a human-friendly format

The `forHumans` method expands on abbreviating by offering you the choice to represent large numbers in full words for even greater clarity.

```php
$humanReadableNumber = Number::forHumans(123456789);

echo $humanReadableNumber; // "123 million"
```

And with that, the Laravel's Number helper makes large numbers a lot less intimidating.

## Conclusion

Blazing through number formatting challenges just got easier with Laravel's Number helper.

Each method is designed to handle different nuances associated with number representation, from locales to human-readable formats

It's time to explore these methods and put them to work in your Laravel applications! 💪
