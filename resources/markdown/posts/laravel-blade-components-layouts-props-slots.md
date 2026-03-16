---
id: "01KKVJ4ZDNY02PHRV7A3AKF7HJ"
title: "How to use Blade components, layouts, props, and slots in Laravel"
slug: "laravel-blade-components-layouts-props-slots"
author: "benjamincrozat"
description: "Learn how to use Blade components in Laravel with layouts, props, and slots so your templates stay reusable instead of turning into copy-paste markup."
categories:
  - "laravel"
published_at: 2026-03-16T14:52:57+00:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/generated/laravel-blade-components-layouts-props-slots.png"
sponsored_at: null
---
## Introduction

**Blade is Laravel’s templating engine, but the part that matters most in day-to-day work is usually components, layouts, props, and slots.**

That is where Blade stops being “just PHP in HTML” and starts helping you keep views reusable.

If you only remember one practical rule from this guide, make it this:

> use layouts for page structure, and use Blade components for repeated UI pieces

Everything else becomes easier once that line is clear.

## What Blade is good at

Blade is a server-side templating layer for Laravel views.

It is a good fit for:

- page layouts
- reusable UI fragments
- small conditional logic in templates
- looping over server-side data

What it is not for:

- large chunks of business logic
- complex state management
- replacing proper frontend interactivity when you actually need Livewire, Alpine, or JavaScript

## Start with a layout

Layouts are for the outer structure of a page.

Example `resources/views/components/layout.blade.php`:

```blade
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'My app' }}</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-900">
    <header class="border-b bg-white">
        <div class="mx-auto max-w-5xl px-6 py-4">
            <h1 class="text-xl font-semibold">My app</h1>
        </div>
    </header>

    <main class="mx-auto max-w-5xl px-6 py-10">
        {{ $slot }}
    </main>
</body>
</html>
```

Then use it like this:

```blade
<x-layout>
    <h2 class="text-2xl font-bold">Dashboard</h2>
</x-layout>
```

That `{{ $slot }}` line is the key. It is where the child view content gets injected.

## Create a Blade component

Use a Blade component when the same markup keeps appearing in several places.

Example: a simple alert box.

Create `resources/views/components/alert.blade.php`:

```blade
@props([
    'type' => 'info',
])

@php
    $classes = match ($type) {
        'success' => 'bg-green-100 text-green-800',
        'error' => 'bg-red-100 text-red-800',
        default => 'bg-blue-100 text-blue-800',
    };
@endphp

<div {{ $attributes->merge(['class' => "rounded-lg px-4 py-3 {$classes}"]) }}>
    {{ $slot }}
</div>
```

Use it like this:

```blade
<x-alert type="success">
    Profile updated successfully.
</x-alert>
```

That is already more maintainable than copying the same classes and wrapper markup into multiple views.

## Blade props

Props are the values you pass into a component.

In the example above:

```blade
<x-alert type="success">
```

`type` is a prop.

The component reads it here:

```blade
@props([
    'type' => 'info',
])
```

That gives you:

- a default value
- a clear list of expected inputs
- better readability inside the component

If a component has a few configurable values, props are usually enough. If it starts accumulating too much logic, that is often a sign you should move to a class-based component or rethink the component boundary.

## Blade slots

The slot is the inner content passed to the component.

Example:

```blade
<x-alert type="error">
    Something went wrong while saving the invoice.
</x-alert>
```

Inside the component:

```blade
{{ $slot }}
```

That is the default slot.

Blade also supports named slots.

Example:

```blade
<x-card>
    <x-slot:heading>
        Account settings
    </x-slot:heading>

    <p>Update your profile and security preferences here.</p>
</x-card>
```

Component:

```blade
<div class="rounded-xl border bg-white p-6 shadow-sm">
    <div class="mb-4 text-lg font-semibold">
        {{ $heading }}
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
```

Named slots are useful when a component has a few clearly distinct content regions.

## Pass extra HTML attributes cleanly

One of Blade’s best quality-of-life features is the attribute bag.

This line:

```blade
<div {{ $attributes->merge(['class' => 'rounded-lg px-4 py-3']) }}>
```

lets the component accept additional attributes from the caller:

```blade
<x-alert class="mb-6" data-test="profile-alert">
    Saved.
</x-alert>
```

That keeps components flexible without forcing every possible option into explicit props.

## When to use a class-based component

Anonymous Blade components are enough for many cases.

Use a class-based component when:

- you need computed data
- the view logic is becoming awkward
- you want typed constructor arguments

Generate one with Artisan:

```bash
php artisan make:component Button
```

Laravel creates:

- a component class
- a Blade view

Simple example:

```php
namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class PageHeading extends Component
{
    public function __construct(
        public string $title,
        public ?string $subtitle = null,
    ) {}

    public function render(): View
    {
        return view('components.page-heading');
    }
}
```

That gives you a cleaner place for component-specific data than stuffing everything into Blade conditionals.

## A practical page example

Here is what this looks like together in a real page:

```blade
<x-layout>
    <x-slot:title>
        Dashboard
    </x-slot:title>

    <x-page-heading
        title="Dashboard"
        subtitle="Overview of your latest activity"
    />

    <x-alert type="success" class="mb-6">
        Welcome back.
    </x-alert>

    <x-card>
        <x-slot:heading>
            Recent invoices
        </x-slot:heading>

        <p>No invoices yet.</p>
    </x-card>
</x-layout>
```

That is the shape you want: layout for page shell, components for repeated UI, props for configuration, and slots for content regions.

## Common Blade mistakes

### Putting too much logic in views

Small conditionals are fine. Big branching logic, query building, or transformation work should stay out of Blade.

If the template is doing data shaping, move that into the controller, view model, or component class.

### Reusing layouts for everything

Layouts should provide page structure. Repeated UI chunks should become components.

If a layout starts acting like a mega-component system, split responsibilities more clearly.

### Building components too early

Not every repeated div needs a component. If a pattern is not actually reused or not stable yet, keep it simple until the repetition is obvious.

### Ignoring attribute merging

Hard-coded component markup is often less reusable than people think. `$attributes->merge()` is one of the easiest ways to avoid that trap.

## Blade vs Livewire vs Alpine

A simple rule:

- use Blade for server-rendered templates
- use Alpine for lightweight frontend behavior
- use Livewire when the UI needs richer server-driven interactivity

Do not force Blade alone to solve interactive problems it was not designed for.

If that line is the real question you are dealing with, [this Livewire re-render guide](/re-render-livewire-component) is one useful next step.

## Conclusion

Blade gets much easier once you stop treating it as one big feature and break it into roles:

- layouts for page structure
- components for repeated markup
- props for configuration
- slots for content injection

That is the practical center of Blade in a Laravel app. Once those pieces click, your views stop turning into copy-paste templates and start feeling intentional.

If you are still tightening the Laravel view layer after this, these are the next reads I would keep open:

- [Add Tailwind to Laravel before styling your components by hand](/tailwind-css-laravel)
- [Use Livewire when the UI needs more than server-rendered templates](/re-render-livewire-component)
- [Keep Laravel architecture decisions sane as the app grows](/laravel-architecture-best-practices)
