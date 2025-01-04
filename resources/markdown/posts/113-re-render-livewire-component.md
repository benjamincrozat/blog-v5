---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/51920738-2d51-4fe5-9437-44e5360959e7
Title: "How to force re-render a Livewire v3 component"
Description: "Stop pulling your hair. Here's a solution to your reactivity issues in Livewire."
Published at: 2024-01-10
Categories: laravel, livewire
---

## Introduction to re-renderings in Livewire v3

Forcing components to re-render in Livewire is the secret for a better user experience. Keeping lists in sync by defering their management to the top component is the easiest way to do it. But sometimes, that’s just not enough and that where this article comes in handy.

## Create an empty listener method

Let’s say that for some reasons, you have a child component that handles creating new resources and therefore, prevents the parent component from refreshing the list.

**Well, I have a solution for you: create an empty listener method in your parent component.**

Here’s the child component’s class:

```php
namespace App\Livewire;

use Livewire\Component;

class Item extends Component
{
    public function create()
    {
        // Create the resource.

        $this->dispatch(‘created’);
    }
}
```

And here’s your parent component’s class, using the `Livewire\Attributes\On` attribute to let Livewire know it's waiting for a given event:

```php
namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Listing extends Component
{
    #[On(’created’)]
    public function refresh()
    {
    }
}
```

You can [learn more about listeners](https://livewire.laravel.com/docs/events#listening-for-events) in Livewire on the official documentation.

## Use the secret $refresh method

Alternatively, you can listen for the “created” we made up for this article right in DOM. It’s a matter of preference, because both methods will work and produce the exact same result.

Here’s the parent component’s Blade view:

```blade
<div @created=“$refresh”>
    @foreach ($items as $item)
        <livewire:item :$item />
    @endforeach
</div>
```

You can also call it form Alpine.js using `$wire.$render`.
