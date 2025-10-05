<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\HtmlString;
use App\Notifications\LinkDeclined;

it('renders as an email', function () {
    $link = Link::factory()->create();

    $rendered = (new LinkDeclined($link, 'We already covered this topic.'))
        ->toMail(User::factory()->create())
        ->render();

    expect($rendered)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, greeting, and content', function () {
    $link = Link::factory()->create();
    $reason = 'We already covered this topic.';

    $message = (new LinkDeclined($link, $reason))->toMail(User::factory()->create());

    expect($message->subject)->toBe('Your link was declined');
    expect($message->greeting)->toBe('Thank you for submitting, but your link was declined.');
    expect($message->introLines)->toHaveCount(1);
    expect($message->introLines[0])->toBe($reason);
});

it('sends via the mail channel and is queueable', function () {
    $link = Link::factory()->create();

    $notification = new LinkDeclined($link, 'Duplicate content.');

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
