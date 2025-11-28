<?php

use App\Models\User;
use App\Listeners\NotifyOnQueueFailover;
use App\Notifications\QueueFailoverHappened;
use Illuminate\Queue\Events\QueueFailedOver;
use Illuminate\Support\Facades\Notification;

it('notifies Benjamin when a queue failover happens', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $event = new QueueFailedOver('redis', 'TestJob', new Exception('failover'));

    (new NotifyOnQueueFailover)->handle($event);

    Notification::assertSentTo(
        $admin,
        QueueFailoverHappened::class,
        fn (QueueFailoverHappened $notification) => $notification->event === $event
    );
});

it('does nothing when Benjamin cannot be found', function () {
    Notification::fake();

    $event = new QueueFailedOver('redis', 'TestJob', new Exception('failover'));

    (new NotifyOnQueueFailover)->handle($event);

    Notification::assertNothingSent();
});
