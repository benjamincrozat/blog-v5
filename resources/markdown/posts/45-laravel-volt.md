---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://life-long-bunny.fra1.digitaloceanspaces.com/media-library/production/47/Volt_tn1jfd.jpg
Title: "Laravel Volt: simplify how you write Livewire components"
Description: "Laravel Volt is a great new addition to Laravel's extensive ecosystem that brings single-file components à la Vue.js to Livewire. Let me help you get started."
Published at: 2023-07-25
Modified at: 2023-08-12
Categories: laravel, livewire, packages
---

## Introduction

[Laravel Volt](https://github.com/laravel/volt) was introduced in Laracon US 2023. The package brings single-file components à la Vue.js to [Livewire v3](https://livewire.laravel.com).

This is a step-by-step tutorial to help you quickly get started with this elegant addition to the Laravel ecosystem.

**Please understand that to be able to follow this tutorial, being familiar with class-based Livewire v3 components is necessary.**

## What is Laravel Volt?

**Laravel Volt is a way to write single-file Livewire v3 components** by leveraging a new declarative API.

If you are familiar with JavaScript frameworks like Vue.js, you know what I'm talking about.

For the others, don't worry, you'll get it once you see it!

## How to install Laravel Volt

First, install Laravel Volt using the following command:

```bash
composer require livewire/volt
```

*Note that Volt is under the `livewire` namespace, not `laravel`.*

Then, install the necessary boilerplate to make Laravel Volt work:

```bash
php artisan volt:install
```

This will create *VoltServiceProvider.php* in *app/Providers* and register it in your *config/app.php* file.

## How to create a basic newsletter component

To create a component, use the `make:volt` command:

```bash
php artisan make:volt Newsletter 
```

This will spawn a new file in *./resources/views/livewire/newsletter.blade.php*.

Before we continue, did you know that you can also create tests simultaneously? Just pass the `--test` option. You can even ensure it's a Pest test by passing `--pest` instead of `--test`.

```bash
php artisan make:volt Newsletter --test --pest
```

A test will be created in *tests/Feature/Livewire/NewsletterTest.php*. But we'll get back to it shortly!

Now, let's take a look at our Volt component. Don't worry; I will detail every line of code.

```blade
<?php

use function Livewire\Volt\{rules,state};

state([
    'email' => '',
    'done' => false,
]);

rules([
    'email' => 'required|email',
]);

$submit = function () {
    $this->validate();

    // Subscribe the user.

    $this->done = true;
};

?>

<div>
    @if ($done)
        <p>Thank you for subscribing!</p>
    @else
        <form wire:submit="submit">
            <input type="email" wire:model="email" required />

            <button>
                Subscribe
            </button>
        </form>

        @error('email')
            <p>{{ $message }}</p>
        @enderror
    @endif
</div>
```

This Volt component aims to subscribe people to our newsletter without sending them to another page. We want the process to feel fast and modern.

1. **We create the form in a classic Laravel-fashion.**
2. **We display validation errors.**
3. **We intercept the submit action:** Instead of using `method="POST" action="/some-uri"`, we bind the submit action to a Livewire function we will define.
4. **We bind the value of our input field to an `email` state property** we will define.
5. **We conditionally display a confirmation message** thanks to another state property we will define.
6. **We define our state properties**: this is done in a PHP code block at the top of the file. The state properties are `email` and `done`.
7. **We validate the email property.** Our component must be secure and not store junk data in the database.
8. **We define a submit function** that will be called when the form is submitted. Inside, we validate the user's input based on the previously defined rules, subscribe the user, and set the `done` state property to `true`, which will trigger the confirmation message.

Make sure everything works in your browser, and let's see in the next section how to test that this Volt component behaves as expected.

## Keep using class-based components in a single file

If you have a big Laravel project that you want to slowly transition to Volt, you have the possibility of using your existing class-based Livewire v3 components inside your single-file component.

Here's the newsletter that we created in the previous section, but class-based:

```blade
<?php

use Livewire\Volt\Component;

new class extends Component {
    public $email = '';
	
	public $done = false;
	
    $rules = ['email' => 'required|email'];
	
	public function submit() {
		$this->validate();

		// Subscribe the user.

		$this->done = true;
	};
};

?>

<div>
    @if ($done)
        <p>Thank you for subscribing!</p>
    @else
        <form wire:submit="submit">
            <input type="email" wire:model="email" required />

            <button>
                Subscribe
            </button>
        </form>

        @error('email')
            <p>{{ $message }}</p>
        @enderror
    @endif
</div>
```

How cool is that? And people not happy with the new declarative API will certainly be happy about class-based single file Volt components.

## How to test our basic newsletter component

This hasn't changed much if you are already familiar with writing Livewire tests. Let's review how I wrote this test:

```php
use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('newsletter');
  
    $component
        // Test the initial state of the component.
        ->assertSet('email', '')
        ->assertSet('done', false)
        // Simulate the user entering an email address.
        ->set('email', 'homer@simpson.com')
        // Simulate the user submitting the form.
        ->call('submit')
        // Test the state properties have changed.
        ->assertSet('email', 'homer@simpson.com')
        ->assertSet('done', true)
        // Test the confirmation message is displayed.
        ->assertSee('Thank you for subscribing!');
  
    // There, you can also test that the user has been subscribed successfully.
});
```

1. **We render the component** using `Volt::test('newsletter')`.
2. **We test the initial state** for the expected values.
3. **We simulate the user's input and action.**
4. **We test for the expected state.**
5. **We test for the expected confirmation message** to be displayed.
6. **We test that the user has been successfully subscribed.**

All good? Now run `php artisan test` and enjoy the greenery and confidence tests give you!

Learn more about [how to test Volt components](https://livewire.laravel.com/docs/volt#testing-components) on the official documentation.

## Laravel Volt presented by Taylor Otwell

Oh, before I leave, I wanted to share with you the video from Laracon US where Taylor Otwell himself unveils and demos Laravel Volt (skip to 40:32). Enjoy!

<iframe src="https://www.youtube.com/embed/1P3wLy49t2c?si=-DCMEybpyBuUbJed&start=2432" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>

