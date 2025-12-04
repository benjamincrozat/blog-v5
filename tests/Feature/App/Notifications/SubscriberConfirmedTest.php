<?php

use App\Models\User;
use App\Models\Subscriber;
use Illuminate\Support\HtmlString;
use App\Notifications\SubscriberConfirmed;

it('renders as an email', function () {
    $subscriber = Subscriber::factory()->create();

    $rendered = (new SubscriberConfirmed($subscriber))
        ->toMail(User::factory()->create())
        ->render();

    expect($rendered)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, content, and action', function () {
    $subscriber = Subscriber::factory()->create([
        'email' => 'subscriber@example.com',
    ]);

    $message = (new SubscriberConfirmed($subscriber))->toMail(User::factory()->create());

    expect($message->subject)->toBe('A subscriber just confirmed');
    expect($message->introLines)->toHaveCount(1);
    expect($message->introLines[0])->toContain('subscriber@example.com');
    expect($message->actionText)->toBe('View subscribers');
    expect($message->actionUrl)->toBe(url('/admin/subscribers'));
});

it('sends via the mail channel and is queueable', function () {
    $subscriber = Subscriber::factory()->create();

    $notification = new SubscriberConfirmed($subscriber);

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
