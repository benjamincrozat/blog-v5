<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\LinkWaitingForValidation;

it('renders as an email', function () {
    $link = Link::factory()->create();

    $result = new LinkWaitingForValidation($link)
        ->toMail(User::factory()->create())
        ->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, greeting and action', function () {
    $link = Link::factory()->create(['url' => 'https://example.com/any']);

    $message = (new LinkWaitingForValidation($link))->toMail(User::factory()->create());

    expect($message->subject)->toBe('A link is waiting for validation');
    expect($message->greeting)->toBe('Heads up!');
    expect(implode("\n", $message->introLines))
        ->toContain('example.com')
        ->toContain($link->user->name);

    expect($message->actionText)->toBe('Check');
    expect($message->actionUrl)->toBe(url('/admin/links'));
});

it('sends via the mail channel and is queueable', function () {
    $link = Link::factory()->create();
    $user = User::factory()->create();

    $notification = new LinkWaitingForValidation($link);

    expect($notification->via($user))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(ShouldQueue::class);
});
