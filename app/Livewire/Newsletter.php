<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\View\View;
use App\Models\Subscriber;
use Livewire\Attributes\Validate;

class Newsletter extends Component
{
    #[Validate('required|email:filter|max:255|unique:subscribers,email')]
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

        Subscriber::query()->create([
            'email' => $this->email,
        ]);

        $this->subscribed = true;

        $this->reset('email');
    }
}
