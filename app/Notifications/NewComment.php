<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Emails the site owner after another user posts a comment.
 *
 * The Comments component sends this queued message after saving the comment and
 * skips it for the owner's own comments. The email names the user and article
 * and links to the comment section.
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
