<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Builds the welcome notification.
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
            ->line('I also have a selection of [great tools](' . route('tools.index') . ') for developers:')
            ->line('- [Unlock the power of Git on Mac and Windows](' . route('merchants.show', 'tower') . ')')
            ->line('- [Know who visits your site](' . route('merchants.show', 'fathom-analytics') . ')')
            ->line('- [Easily deploy PHP web apps](' . route('merchants.show', 'cloudways-php') . ')')
            ->line('- [Send emails to your users](' . route('merchants.show', 'mailcoach') . ')')
            ->line('- [Rank higher on Google](' . route('merchants.show', 'wincher') . ')')
            ->line('- [Monitor your site\'s uptime, speed, and SSL](' . route('merchants.show', 'uptimia') . ')')
            ->line('And if you are old school like me, subscribe to the [Atom feed](' . route('feeds.main') . ').')
            ->line('Find me on [X](https://x.com/benjamincrozat) and [LinkedIn](https://www.linkedin.com/in/benjamincrozat/).');
    }
}
