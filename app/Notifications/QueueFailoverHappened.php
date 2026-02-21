<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Events\QueueFailedOver;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Defines the QueueFailoverHappened implementation.
 */
class QueueFailoverHappened extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public QueueFailedOver $event
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A queue failover happened')
            ->line("The queue connection {$this->event->connectionName} has failed to respond and the job {$this->event->command} has been redirected.")
            ->line("**Check what's happening to the connection.**");
    }
}
