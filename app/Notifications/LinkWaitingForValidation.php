<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Emails the site owner when a new community link needs review.
 *
 * The last wizard step sends this queued message after saving the pending link.
 * It names the submitter and destination and links to the submitted URL. It does
 * not approve or decline the link.
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
            ->action('Open link', $this->link->url);
    }
}
