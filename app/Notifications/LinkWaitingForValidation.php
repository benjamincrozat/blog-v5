<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Defines the LinkWaitingForValidation implementation.
 */
class LinkWaitingForValidation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Link $link) {}

    public function via(User $user) : array
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        return (new MailMessage)
            ->subject('A link is waiting for validation')
            ->greeting('Heads up!')
            ->line("A link to {$this->link->domain} from {$this->link->user->name} is waiting for validation.")
            ->action('Check', route('filament.admin.resources.links.index'));
    }
}
