---
Image: https://res.cloudinary.com/benjamincrozat-com/image/fetch/c_scale,f_webp,q_auto,w_1200/https://github.com/benjamincrozat/content/assets/3613731/049d07f7-6fae-4a69-993d-410dbd3440d3
Title: "Prevent a Livewire component from re-rendering"
Description: "Improve the performances of your Laravel application by avoiding unnecessary re-renders of Livewire components."
Published at: 2024-01-10
Categories: laravel, livewire
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
