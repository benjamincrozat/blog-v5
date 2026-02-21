<?php

namespace App\Livewire\LinkWizard;

use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Client\ConnectionException;
use Spatie\LivewireWizard\Components\StepComponent;

/**
 * Defines the FirstStep implementation.
 */
class FirstStep extends StepComponent
{
    #[Validate('required|url|unique:links,url', message: [
        'unique' => 'This URL was already shared.',
    ])]
    #[Url(history: true)]
    public string $url = '';

    public function stepInfo() : array
    {
        return [
            'label' => 'Your link',
        ];
    }

    public function mount() : void
    {
        if ($this->url) {
            $this->prepareForNextStep();
        }
    }

    public function render() : View
    {
        return view('livewire.link-wizard.first-step');
    }

    public function submit() : void
    {
        $this->prepareForNextStep();
    }

    protected function prepareForNextStep() : void
    {
        try {
            $this->validate();

            Http::head($this->url)->throw();

            $this->nextStep();
        } catch (ConnectionException|RequestException $e) {
            throw ValidationException::withMessages([
                'url' => 'Your URL is invalid.',
            ]);
        }
    }
}
