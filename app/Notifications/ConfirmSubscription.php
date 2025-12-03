<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmSubscription extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected string $token,
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        $url = URL::temporarySignedRoute(
            name: 'subscribers.confirm',
            expiration: now()->addDays(2),
            parameters: [
                'subscriber' => $notifiable->getKey(),
                'token' => $this->token,
            ],
        );

        return (new MailMessage)
            ->subject('Are you a bot?')
            ->greeting('Thanks for subscribing to the newsletter!')
            ->line('Please confirm the human nature of your actions by clicking the button below.')
            ->action("Beep bo… I'm a human!", $url);
    }
}
