<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Builds the new comment notification.
 */
class NewComment extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Comment $comment
    ) {}

    public function via(User $user) : array
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        return (new MailMessage)
            ->subject('New comment posted')
            ->greeting("{$this->comment->user->name} commented on [{$this->comment->post->title}](" . route('posts.show', $this->comment->post) . ')')
            ->action('Check Comment', route('posts.show', $this->comment->post) . '#comments');
    }
}
