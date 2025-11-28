<?php

use App\Models\User;
use Exception as BaseException;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\QueueFailoverHappened;
use Illuminate\Queue\Events\QueueFailedOver;

it('renders the queue failover notification as HTML mail', function () {
    $rendered = queueFailoverNotification()
        ->toMail(User::factory()->create())
        ->render();

    expect($rendered)->toBeInstanceOf(HtmlString::class);
});

it('sets a descriptive queue failover subject line', function () {
    $message = queueFailoverNotification()->toMail(User::factory()->create());

    expect($message->subject)->toBe('A queue failover happened');
});

it('lists two intro lines to describe the failover', function () {
    $message = queueFailoverNotification()->toMail(User::factory()->create());

    expect($message->introLines)->toHaveCount(2);
});

it('explains which connection and job failed', function () {
    $message = queueFailoverNotification()->toMail(User::factory()->create());

    expect($message->introLines[0])->toBe(
        'The queue connection redis has failed to respond and the job TestJob has been redirected.'
    );
});

it('instructs administrators to check the connection', function () {
    $message = queueFailoverNotification()->toMail(User::factory()->create());

    expect($message->introLines[1])->toBe("**Check what's happening to the connection.**");
});

it('delivers the failover notification via mail channel only', function () {
    expect(queueFailoverNotification()->via(User::factory()->create()))->toBe(['mail']);
});

it('queues the failover notification for delivery', function () {
    expect(queueFailoverNotification())->toBeInstanceOf(ShouldQueue::class);
});

function queueFailoverNotification() : QueueFailoverHappened
{
    return new QueueFailoverHappened(queueFailoverEvent());
}

function queueFailoverEvent() : QueueFailedOver
{
    return new QueueFailedOver('redis', 'TestJob', new BaseException('failover'));
}
