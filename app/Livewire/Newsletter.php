<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Illuminate\View\View;
use App\Models\Subscriber;
use Livewire\Attributes\Validate;
use Illuminate\Validation\ValidationException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Spatie\Honeypot\Http\Livewire\Concerns\HoneypotData;
use Spatie\Honeypot\Http\Livewire\Concerns\UsesSpamProtection;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;

class Newsletter extends Component
{
    use UsesSpamProtection, WithRateLimiting;

    #[Validate('required|email:filter|max:255')]
    public string $email = '';

    public HoneypotData $honeypot;

    public function mount() : void
    {
        $this->honeypot = new HoneypotData;
    }

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
        try {
            $this->rateLimit(5, 60);
        } catch (TooManyRequestsException $exception) {
            throw ValidationException::withMessages([
                'email' => "Please wait another {$exception->secondsUntilAvailable} seconds.",
            ]);
        }

        $this->protectAgainstSpam();

        $this->email = (string) str($this->email)->trim()->lower();

        $this->validate();

        $existingSubscriber = Subscriber::query()
            ->where('email', $this->email)
            ->first();

        if ($existingSubscriber) {
            if (! $existingSubscriber->needsConfirmation()) {
                session()->flash('status', 'You already are subscribed.');
                session()->flash('status_type', 'error');

                $this->redirectRoute('newsletter', navigate: true);

                return;
            }

            $existingSubscriber->sendConfirmationNotification();

            session()->flash('status', 'Another confirmation email is on its way!');
            session()->flash('status_type', 'success');

            $this->redirectRoute('newsletter', navigate: true);

            return;
        }

        $subscriber = Subscriber::query()->create([
            'email' => $this->email,
        ]);

        $subscriber->attachTag('general');
        $subscriber->sendConfirmationNotification();

        session()->flash('status', 'Thanks for subscribing! A confirmation email has been sent to your inbox.');
        session()->flash('status_type', 'success');

        $this->redirectRoute('newsletter', navigate: true);
    }
}
