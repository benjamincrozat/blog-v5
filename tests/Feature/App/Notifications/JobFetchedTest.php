<?php

use App\Models\Job;
use App\Models\User;
use App\Notifications\JobFetched;
use Illuminate\Support\HtmlString;
use Illuminate\Notifications\Messages\MailMessage;

it('renders as an email', function () {
    expect(jobFetchedMail()->render())->toBeInstanceOf(HtmlString::class);
});

it('uses a descriptive subject line', function () {
    expect(jobFetchedMail()->subject)->toBe('A new job was just fetched');
});

it('uses a single intro line', function () {
    expect(jobFetchedMail()->introLines)->toHaveCount(1);
});

it('mentions the job title in the intro line', function () {
    $job = Job::factory()->create(['title' => 'Senior Laravel Developer']);

    expect(jobFetchedMail($job)->introLines[0])->toContain('Senior Laravel Developer');
});

it('labels the call-to-action button', function () {
    expect(jobFetchedMail()->actionText)->toBe('Check Job');
});

it('links the call-to-action button to the job page', function () {
    $job = Job::factory()->create();

    expect(jobFetchedMail($job)->actionUrl)->toBe(route('jobs.show', $job));
});

it('sends via the mail channel', function () {
    $job = Job::factory()->create();

    $notification = new JobFetched($job);

    expect($notification->via(User::factory()->create()))->toBe(['mail']);
});

it('queues the notification for async sending', function () {
    $job = Job::factory()->create();

    expect(new JobFetched($job))->toBeInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class);
});

function jobFetchedMail(?Job $job = null) : MailMessage
{
    $job ??= Job::factory()->create();

    return (new JobFetched($job))->toMail(User::factory()->create());
}
