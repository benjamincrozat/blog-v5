---
id: "01KKEW27MMAQ7ABS93YYYJEX4V"
title: "Prevent a Livewire component from re-rendering"
slug: "prevent-render-livewire"
author: "benjamincrozat"
description: "Improve the performances of your Laravel application by avoiding unnecessary re-renders of Livewire components."
categories:
  - "laravel"
  - "livewire"
published_at: 2024-01-10T00:00:00+01:00
modified_at: null
serp_title: null
serp_description: null
canonical_url: ""
is_commercial: false
image_disk: "cloudflare-images"
image_path: "images/posts/U03PUz5l5BRePoH.jpg"
sponsored_at: null
---
## Introduction to re-rendering prevention in Livewire

I previously talked about various ways to [re-render a Livewire component](/re-render-livewire-component). But now, let's do a 180° and talk about doing the opposite: preventing re-renders!

## Block re-renders in a Livewire component

Sometimes, you might want to run an action or listen to an event in a Livewire component. Problem is: this triggers a re-render. The solution? The new `Livewire\Attributes\Renderless` attribute!

```php
namespace App\Livewire;
 
use Livewire\Component;
use Livewire\Attributes\Renderless;
 
class Show extends Component
{
    #[Renderless] 
    public function incrementViewCount()
    {
        $this->model->incrementViewCount();
    }
}
```

This can be a huge win for the performances of your Laravel application.

Oh and by the way, if you still can’t deal with PHP’s new attributes, you can use the `skipRender()` method like so:

```php
namespace App\Livewire;
 
use Livewire\Component;
 
class Show extends Component
{
    public function incrementViewCount()
    {
        $this->model->incrementViewCount();

        $this->skipRender();
    }
}
```

If you are tuning how Livewire updates the page instead of merely making it work, these are the next reads I would open:

- [How to force re-render a Livewire v3 component](/re-render-livewire-component)
- [Laravel Volt: simplify how you write Livewire components](/laravel-volt)
- [Create a SPA in seconds using wire:navigate in Livewire v3](/livewire-spa-wire-navigate)
