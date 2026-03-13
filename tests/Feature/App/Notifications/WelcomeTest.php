<?php

use App\Models\User;
use App\Notifications\Welcome;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Queue\ShouldQueue;

it('renders as an email', function () {
    $result = new Welcome()->toMail(User::factory()->create())->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject and static content', function () {
    $message = (new Welcome)->toMail(User::factory()->create());

    expect($message->subject)->toBe('Your welcome gifts');
    expect($message->greeting)->toBe('Thank you for signing up!');
    expect(implode("\n", $message->introLines))
        ->toContain('post comments')
        ->toContain('submit links')
        ->toContain(route('links.index'))
        ->toContain('popular articles:');

    expect(implode("\n", array_merge($message->introLines, $message->outroLines)))
        ->toContain(route('tools.index'))
        ->toContain(route('merchants.show', 'tower'))
        ->toContain(route('merchants.show', 'fathom-analytics'))
        ->toContain(route('merchants.show', 'cloudways-php'))
        ->toContain(route('merchants.show', 'mailcoach'))
        ->toContain(route('merchants.show', 'wincher'))
        ->toContain(route('merchants.show', 'uptimia'))
        ->toContain(route('feeds.main'));
});

it('sends via the mail channel and is queueable', function () {
    $user = User::factory()->create();
    $notification = new Welcome;

    expect($notification->via($user))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(ShouldQueue::class);
});
