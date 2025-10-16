<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\QueueFailoverHappened;
use Illuminate\Queue\Events\QueueFailedOver;

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
