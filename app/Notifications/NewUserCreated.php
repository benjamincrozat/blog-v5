<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Emails the site owner when GitHub sign-in creates a new local user.
 *
 * The sign-in callback sends this queued message only for a first-time account.
 * Returning users do not trigger it. The email reports the new user's name and
 * does not change their account or session.
 */
class NewUserCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A new user was just created')
            ->line("{$this->user->name} has just joined the platform.");
    }
}
