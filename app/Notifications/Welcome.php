<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Welcomes a first-time GitHub user and shows what they can do on the blog.
 *
 * The sign-in callback sends this queued email only when it creates an account.
 * It explains comments and link sharing, includes five popular published posts,
 * and links to the feed and social accounts. Returning users do not receive it.
 */
class Welcome extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(User $user) : array
    {
        return ['mail'];
    }

    public function toMail(User $user) : MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('Your welcome gifts')
            ->greeting('Thank you for signing up!')
            ->line('You can now **post comments** or [**submit links**](' . route('links.index') . ') to content you find useful or wrote.')
            ->line('If you want to keep reading, here are some popular articles:');

        Post::query()
            ->published()
            ->where('sessions_count', '>', 0)
            ->orderBy('sessions_count', 'desc')
            ->inRandomOrder()
            ->limit(5)
            ->get()
            ->each(fn (Post $post) => $mailMessage->line(
                "- [$post->title](" . route('posts.show', $post) . ')'
            ));

        return $mailMessage
            ->line('And if you are old school like me, subscribe to the [Atom feed](' . route('feeds.main') . ').')
            ->line('Find me on [X](https://x.com/benjamincrozat) and [LinkedIn](https://www.linkedin.com/in/benjamincrozat/).');
    }
}
