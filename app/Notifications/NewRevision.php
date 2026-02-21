<?php

namespace App\Notifications;

use App\Models\Revision;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Defines the NewRevision implementation.
 */
class NewRevision extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Revision $revision
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A new revision is available')
            ->line("Your AI-powered writer revised \"{$this->revision->report->post->title}\".")
            ->action('Check Revision', route('filament.admin.resources.revisions.view', $this->revision));
    }
}
