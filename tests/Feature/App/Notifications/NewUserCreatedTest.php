<?php

use App\Models\User;
use Illuminate\Support\HtmlString;
use App\Notifications\NewUserCreated;

it('renders as an email', function () {
    $user = User::factory()->create();

    $rendered = (new NewUserCreated($user))
        ->toMail(User::factory()->create())
        ->render();

    expect($rendered)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, content, and action', function () {
    $user = User::factory()->create(['name' => 'Taylor Otwell']);

    $message = (new NewUserCreated($user))->toMail(User::factory()->create());

    expect($message->subject)->toBe('A new user was just created');
    expect($message->introLines)->toHaveCount(1);
    expect($message->introLines[0])->toContain('Taylor Otwell');
    expect($message->actionText)->toBe('Check Profile');
    expect($message->actionUrl)->toBe(route('authors.show', $user));
});

it('sends via the mail channel and is queueable', function () {
    $user = User::factory()->create();

    $notification = new NewUserCreated($user);

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
