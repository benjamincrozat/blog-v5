<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Defines the NewReport implementation.
 */
class NewReport extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Report $report
    ) {}

    public function via(object $notifiable) : array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable) : MailMessage
    {
        return (new MailMessage)
            ->subject('A new report is available')
            ->line("Your AI-powered editor reviewed \"{$this->report->post->title}\".")
            ->action('Check Report', route('filament.admin.resources.reports.view', $this->report));
    }
}
