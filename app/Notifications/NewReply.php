<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Defines the NewReply implementation.
 */
class NewReply extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $reply
    ) {}

    public function via(User $user) : array
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        return (new MailMessage)
            ->subject('Someone replied to your comment')
            ->greeting("{$this->reply->user->name} replied to your comment on [{$this->reply->post->title}](" . route('posts.show', $this->reply->post) . ')')
            ->action('Check Reply', route('posts.show', $this->reply->post) . '#comments');
    }
}
