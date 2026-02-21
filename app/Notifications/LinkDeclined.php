<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Defines the LinkDeclined implementation.
 */
class LinkDeclined extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Link $link,
        public string $reason,
    ) {}

    public function via(User $user) : array
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        return (new MailMessage)
            ->subject('Your link was declined')
            ->greeting('Thank you for submitting, but your link was declined.')
            ->line($this->reason);
    }
}
