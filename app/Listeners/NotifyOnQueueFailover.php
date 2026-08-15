<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\QueueFailoverHappened;
use Illuminate\Queue\Events\QueueFailedOver;

/**
 * Alerts the site owner when Laravel moves a job to a backup queue connection.
 *
 * It turns the queue event into an email with the failed connection and job
 * details. The message goes to the owner account found by its fixed GitHub login.
 * If that account does not exist, no notification is sent.
 */
class NotifyOnQueueFailover
{
    public function handle(QueueFailedOver $event) : void
    {
        User::query()
            ->where('github_login', 'benjamincrozat')
            ->first()
            ?->notify(new QueueFailoverHappened($event));
    }
}
