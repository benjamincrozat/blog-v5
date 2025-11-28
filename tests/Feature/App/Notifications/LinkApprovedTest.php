<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Support\HtmlString;
use App\Notifications\LinkApproved;

it('renders as an email', function () {
    $link = Link::factory()->create();

    $result = new LinkApproved($link)
        ->toMail(User::factory()->create())
        ->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});

it('uses a congratulatory subject line', function () {
    $link = Link::factory()->create(['url' => 'https://example.com/some-page']);

    $message = (new LinkApproved($link))->toMail(User::factory()->create());

    expect($message->subject)->toBe('Your link was approved');
});

it('thanks the submitter in the greeting', function () {
    $link = Link::factory()->create();

    $message = (new LinkApproved($link))->toMail(User::factory()->create());

    expect($message->greeting)->toBe('Thank you for submitting!');
});

it('mentions the approved domain in the intro', function () {
    $link = Link::factory()->create(['url' => 'https://example.com/path']);

    $message = (new LinkApproved($link))->toMail(User::factory()->create());

    expect(implode("\n", $message->introLines))
        ->toContain('example.com');
});

it('reminds users to share their post on social platforms', function () {
    $link = Link::factory()->create();

    $message = (new LinkApproved($link))->toMail(User::factory()->create());

    expect(implode("\n", $message->introLines))
        ->toContain('LinkedIn')
        ->toContain('X');
});

it('sends via the mail channel', function () {
    $link = Link::factory()->create();
    $user = User::factory()->create();

    $notification = new LinkApproved($link);

    expect($notification->via($user))->toBe(['mail']);
});

it('queues the notification for asynchronous delivery', function () {
    $link = Link::factory()->create();

    expect(new LinkApproved($link))->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
