<?php

use App\Models\User;
use App\Models\Report;
use App\Notifications\NewReport;
use Illuminate\Support\HtmlString;

it('renders as an email', function () {
    $report = Report::factory()->create();

    $rendered = (new NewReport($report))
        ->toMail(User::factory()->create())
        ->render();

    expect($rendered)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, content, and action', function () {
    $report = Report::factory()->create();

    $message = (new NewReport($report))->toMail(User::factory()->create());

    expect($message->subject)->toBe('A new report is available');
    expect($message->introLines)->toHaveCount(1);
    expect($message->introLines[0])->toContain($report->post->title);
    expect($message->actionText)->toBe('Check Report');
    expect($message->actionUrl)->not()->toBeEmpty();
});

it('sends via the mail channel and is queueable', function () {
    $report = Report::factory()->create();

    $notification = new NewReport($report);

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
