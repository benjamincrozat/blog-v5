<?php

use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use App\Scraper\Webpage;
use App\Notifications\JobFetched;
use Illuminate\Support\Facades\Notification;
use App\Actions\CreateJob as CreateJobAction;

it('creates or updates company and job and notifies admin', function () {
    Notification::fake();

    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $webpage = new Webpage(
        'https://example.com/job',
        'https://example.com/image.jpg',
        'Title',
        '<html><body><h1>Title</h1><p>Content</p></body></html>'
    );

    $data = (object) defaultJobPayload();

    $job = app(CreateJobAction::class)->create($webpage, $data);

    expect($job)->toBeInstanceOf(Job::class)
        ->and($job->url)->toBe($data->url)
        ->and($job->company->name)->toBe('Acme Inc')
        ->and($job->technologies)->toMatchArray(['PHP', 'Laravel', 'MySQL'])
        ->and($job->perks)->toMatchArray(['Remote stipend', 'Wellness budget'])
        ->and($job->equity)->toBeTrue();

    Notification::assertSentToTimes($admin, JobFetched::class, 1);
});

it('updates existing company and job when matching identifiers', function () {
    Notification::fake();

    $company = Company::factory()->create([
        'name' => 'Acme Inc',
    ]);

    $existing = Job::factory()->for($company)->create([
        'url' => 'https://example.com/jobs/dup',
        'title' => 'Old title',
        'min_salary' => 1,
    ]);

    $webpage = new Webpage(
        'https://example.com/job',
        'https://example.com/image.jpg',
        'Title',
        '<html><body><h1>Title</h1><p>Content</p></body></html>'
    );

    $data = (object) array_merge(defaultJobPayload(), [
        'url' => 'https://example.com/jobs/dup', // triggers update for job
        'source' => 'ExampleBoard',
        'title' => 'New title',
        'description' => 'New description',
        'technologies' => ['PHP'],
        'perks' => [],
        'locations' => ['Remote'],
        'min_salary' => null, // Should default to 0.
        'max_salary' => null, // Should default to 0.
        'company' => (object) [
            'name' => 'Acme Inc', // Triggers update for company.
            'url' => 'https://acme.new',
            'logo' => 'https://cdn.test/acme-new.png',
            'about' => 'Updated about.',
        ],
    ]);

    $updated = app(CreateJobAction::class)->create($webpage, $data)->refresh();

    expect($updated->id)->toBe($existing->id)
        ->and($updated->title)->toBe('New title')
        ->and($updated->min_salary)->toBe(0)
        ->and($updated->max_salary)->toBe(0)
        ->and($updated->company_id)->toBe($company->id)
        ->and($updated->company->url)->toBe('https://acme.new')
        ->and($updated->company->logo)->toBe('https://cdn.test/acme-new.png')
        ->and($updated->company->about)->toBe('Updated about.');

    Notification::assertNothingSent();
});

it('does not error if admin user is missing', function () {
    Notification::fake();

    $webpage = new Webpage(
        'https://example.com/job',
        'https://example.com/image.jpg',
        'Title',
        '<html><body><h1>Title</h1><p>Content</p></body></html>'
    );

    $data = (object) array_merge(defaultJobPayload(), [
        'url' => 'https://example.com/jobs/456',
        'source' => 'Board',
        'title' => 'Role',
        'description' => 'Desc',
        'technologies' => ['PHP'],
        'perks' => [],
        'locations' => [],
        'min_salary' => 0,
        'max_salary' => 0,
        'equity' => false,
        'interview_process' => [],
        'company' => (object) [
            'name' => 'Foo LLC',
            'url' => null,
            'logo' => null,
            'about' => 'About Foo.',
        ],
    ]);

    $job = app(CreateJobAction::class)->create($webpage, $data);

    expect($job)->toBeInstanceOf(Job::class);
    Notification::assertNothingSent();
});

function defaultJobPayload() : array
{
    return [
        'url' => 'https://example.com/jobs/123',
        'source' => 'ExampleBoard',
        'language' => 'en',
        'title' => 'Senior PHP Developer',
        'description' => 'Build and maintain Laravel apps.',
        'technologies' => ['PHP', 'Laravel', 'MySQL'],
        'perks' => ['Remote stipend', 'Wellness budget'],
        'locations' => ['Remote', 'US'],
        'setting' => 'fully-remote',
        'employment_status' => 'full-time',
        'seniority' => 'senior',
        'min_salary' => 100000,
        'max_salary' => 150000,
        'currency' => 'USD',
        'equity' => true,
        'interview_process' => ['Recruiter screen', 'Technical interview'],
        'company' => (object) [
            'name' => 'Acme Inc',
            'url' => 'https://acme.test',
            'logo' => 'https://cdn.test/acme.png',
            'about' => 'Acme builds tools for developers.',
        ],
    ];
}
