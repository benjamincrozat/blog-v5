<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Events\QueueFailedOver;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Emails the site owner after Laravel moves a job to a backup queue.
 *
 * NotifyOnQueueFailover chooses the recipient. This queued message includes the
 * failed connection and moved job. It reports the event but does not repair the
 * queue or run the job.
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
