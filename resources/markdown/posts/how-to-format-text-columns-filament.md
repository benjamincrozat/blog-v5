---
id: "01KKEW27A7QMAB6X08XHFK57CY"
title: "How to Format Text Columns in Filament"
slug: "how-to-format-text-columns-filament"
author: "benjamincrozat"
description: "Learn how to format text columns in Filament to improve readability and make your admin panel look more professional."
categories:
  - "filament"
published_at: 2024-06-20T00:00:00+02:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/k6yG7xK0vU6bGkl.png"
sponsored_at: null
---
## Introduction

Have you ever looked at your Filament admin panel and thought, "These text columns could use a bit of sprucing up"? Well, you're in luck! Today, that's what I want to write about. Properly formatted text columns can dramatically improve readability and make your admin panel look more professional.

## Setting Up Your First Formatted Text Column

Let's start with the basics. Creating a text column in Filament is pretty straightforward:

```php
use Filament\Tables\Columns\TextColumn;

TextColumn::make('title')
```

This will display the "title" field as is. But we want to add some swag, right? That's where the `formatStateUsing()` method comes in handy:

```php
use Illuminate\Support\Str;

TextColumn::make('title')
    ->formatStateUsing(fn (string $state) => Str::title($state))
```

Now we're talking! This will display the title in title case. But hold on, there's something important we need to discuss...

## The $state Variable: A Crucial Detail for Formatting

Here's a little gotcha that might trip you up: when using `formatStateUsing()`, always use `$state` as your variable name. It might be tempting to use something like `$title`, but resist that urge! If you use any other name, Filament will throw a fit (technically, a `Illuminate\Contracts\Container\BindingResolutionException`). 

For example, this will cause an error:

```php
// Don't do this!
->formatStateUsing(fn (string $title) => strtoupper($title))
```

Always stick with `$state`.

## Practical Examples: Formatting Text Columns in Filament

Now that we've got the basics down, let's look at some practical ways to format your text columns:

1. Capitalizing the first letter:
   ```php
   ->formatStateUsing(fn (string $state) => ucfirst($state))
   ```

2. Truncating long strings:
   ```php
   ->formatStateUsing(fn (string $state) => Str::limit($state, 50))
   ```

3. Adding a prefix:
   ```php
   ->formatStateUsing(fn (string $state) => "Article: {$state}")
   ```

4. Formatting dates:
   ```php
   ->formatStateUsing(fn (string $state) => Carbon::parse($state)->format('M d, Y'))
   ```

## Best Practices for Formatting Text Columns in Filament

While formatting in Filament is powerful, remember that it's applied on the fly. For data that's always displayed in a certain format, consider storing it that way in the database instead. It can be more efficient, especially for large datasets.

## Conclusion

And there you have it! You're now equipped to turn those plain text columns into formatted masterpieces. Remember, the key is to use `$state`, get creative with your formatting, and always consider the end-user experience.

If you are making a Filament panel feel less raw and more polished, these are the next reads I would open:

- [How to Create a Stats Widget in Filament](/how-to-create-stats-widget-filament)
- [Laravel Nova VS. Filament: which one is best?](/laravel-nova-vs-filament)
- [Laravel Pulse: monitor your apps for free](/laravel-pulse)
