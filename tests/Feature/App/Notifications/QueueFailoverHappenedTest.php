<?php

use App\Models\User;
use App\Notifications\QueueFailoverHappened;
use Illuminate\Queue\Events\QueueFailedOver;

it('sends the queue failover notification via mail', function () {
    $event = new QueueFailedOver('redis', 'TestJob', new Exception('failover'));
    $notification = new QueueFailoverHappened($event);

    expect($notification->via(User::factory()->make()))->toBe(['mail']);

    $mail = $notification->toMail(User::factory()->make());

    expect($mail->subject)->toBe('A queue failover happened');
    expect($mail->introLines)->toContain(
        'The queue connection redis has failed to respond and the job TestJob has been redirected.'
    );
});
