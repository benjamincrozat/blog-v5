<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Subscribe;
use Illuminate\View\View;
use Livewire\Attributes\Validate;

class Newsletter extends Component
{
    #[Validate('required|email:filter|max:255|unique:subscribes,email')]
    public string $email = '';

    public bool $subscribed = false;

    public function render() : View
    {
        return view('livewire.newsletter', [
            'aboutUser' => User::query()
                ->where('github_login', 'benjamincrozat')
                ->first(),
        ]);
    }

    public function subscribe() : void
    {
        $this->email = (string) str($this->email)->trim()->lower();

        $this->validate();

        Subscribe::query()->create([
            'email' => $this->email,
        ]);

        $this->subscribed = true;

        $this->reset('email');
    }
}
