<?php

namespace App\Livewire\LinkWizard;

use Embed\Embed;
use App\Models\Link;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use App\Notifications\LinkWaitingForValidation;
use Spatie\LivewireWizard\Components\StepComponent;

class SecondStep extends StepComponent
{
    #[Locked]
    #[Url(history: true)]
    public string $url;

    #[Locked]
    public ?string $imageUrl = null;

    #[Validate('required|string|min:3|max:255')]
    public string $title;

    #[Validate('nullable|string|max:255')]
    public ?string $description = null;

    public function stepInfo() : array
    {
        return [
            'label' => 'Details',
        ];
    }

    public function mount() : void
    {
        if (! $this->url) {
            $this->previousStep();
        }

        $this->dispatch('fetch')->self();
    }

    public function render() : View
    {
        return view('livewire.link-wizard.second-step');
    }

    #[On('fetch')]
    public function fetch() : void
    {
        /**
         * @var array{
         *     image_url: string,
         *     title: string,
         *     description: string,
         * }
         */
        $embed = cache()->remember(
            key: 'embed_' . Str::slug($this->url, '_'),
            ttl: now()->addHour(),
            callback: function () {
                $embed = app(Embed::class)->get($this->url);

                return [
                    'image_url' => $embed->image,
                    'title' => $embed->title,
                    'description' => $embed->description,
                ];
            }
        );

        $this->imageUrl = $embed['image_url'];
        $this->title = $embed['title'];
        $this->description = $embed['description'];
    }

    public function submit() : void
    {
        $this->validate();

        $link = Link::query()->create([
            'user_id' => auth()->id(),
            'url' => $this->url,
            'image_url' => $this->imageUrl,
            'title' => $this->title,
            'description' => $this->description,
        ]);

        User::query()
            ->where('github_login', 'benjamincrozat')
            ->first()
            ->notify(new LinkWaitingForValidation($link));

        $this->redirectRoute('links.index', ['submitted' => true], navigate: true);
    }
}
