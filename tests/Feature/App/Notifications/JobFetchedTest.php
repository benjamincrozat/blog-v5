<?php

use App\Models\Job;
use App\Models\User;
use App\Notifications\JobFetched;
use Illuminate\Support\HtmlString;

it('renders as an email', function () {
    $job = Job::factory()->create();

    $rendered = (new JobFetched($job))
        ->toMail(User::factory()->create())
        ->render();

    expect($rendered)->toBeInstanceOf(HtmlString::class);
});

it('has the expected subject, content, and action', function () {
    $job = Job::factory()->create(['title' => 'Senior Laravel Developer']);

    $message = (new JobFetched($job))->toMail(User::factory()->create());

    expect($message->subject)->toBe('A new job was just fetched');
    expect($message->introLines)->toHaveCount(1);
    expect($message->introLines[0])->toContain('Senior Laravel Developer');
    expect($message->actionText)->toBe('Check Job');
    expect($message->actionUrl)->toBe(route('jobs.show', $job));
});

it('sends via the mail channel and is queueable', function () {
    $job = Job::factory()->create();

    $notification = new JobFetched($job);

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
    expect($notification)->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});
