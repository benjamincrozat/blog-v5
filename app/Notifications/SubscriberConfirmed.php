<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriberConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Subscriber $subscriber
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('New confirmed subscriber!')
            ->greeting('Hooray!')
            ->line("{$this->subscriber->email} just confirmed their newsletter subscription.")
            ->action('View subscribers', route('filament.admin.resources.subscribers.index'));
    }
}
