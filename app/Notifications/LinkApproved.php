<?php

namespace App\Notifications;

use App\Models\Link;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Builds the link approved notification.
 */
class LinkApproved extends Notification implements ShouldQueue
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
            ->subject('Your link was approved')
            ->greeting('Thank you for submitting!')
            ->line("Your link to {$this->link->domain} is live on the blog and ready to be seen by my visitors.")
            ->line('By the way, if you want to do me a favor, follow me on [X](https://x.com/benjamincrozat) and [LinkedIn](https://www.linkedin.com/in/benjamincrozat/)!');
    }
}
