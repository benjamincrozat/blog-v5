<?php

use App\Models\User;
use App\Models\Revision;
use App\Notifications\NewRevision;
use Illuminate\Support\HtmlString;

it('renders as an email', function () {
    $revision = Revision::factory()->create();

    $rendered = (new NewRevision($revision))
        ->toMail(User::factory()->create())
        ->render();

    expect($rendered)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, content, and action', function () {
    $revision = Revision::factory()->create();

    $message = (new NewRevision($revision))->toMail(User::factory()->create());

    expect($message->subject)->toBe('A new revision is available');
    expect($message->introLines)->toHaveCount(1);
    expect($message->introLines[0])->toContain($revision->report->post->title);
    expect($message->actionText)->toBe('Check Revision');
    expect($message->actionUrl)->not()->toBeEmpty();
});

it('sends via the mail channel and is queueable', function () {
    $revision = Revision::factory()->create();

    $notification = new NewRevision($revision);

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
