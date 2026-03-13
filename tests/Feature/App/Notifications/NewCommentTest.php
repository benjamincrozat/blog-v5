<?php

use App\Models\User;
use App\Models\Comment;
use App\Notifications\NewComment;
use Illuminate\Support\HtmlString;
use Illuminate\Contracts\Queue\ShouldQueue;

it('renders as an email', function () {
    $comment = Comment::factory()->create();

    $result = new NewComment($comment)
        ->toMail(User::factory()->create())
        ->render();

    expect($result)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, greeting and action', function () {
    $comment = Comment::factory()->create();

    $message = (new NewComment($comment))->toMail(User::factory()->create());

    expect($message->subject)->toBe('New comment posted');

    expect($message->greeting)
        ->toContain($comment->user->name)
        ->toContain($comment->post->title)
        ->toContain(route('posts.show', $comment->post));

    expect($message->actionText)->toBe('Check Comment');
    expect($message->actionUrl)->toBe(route('posts.show', $comment->post) . '#comments');
});

it('sends via the mail channel and is queueable', function () {
    $comment = Comment::factory()->create();
    $user = User::factory()->create();

    $notification = new NewComment($comment);

    expect($notification->via($user))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(ShouldQueue::class);
});
