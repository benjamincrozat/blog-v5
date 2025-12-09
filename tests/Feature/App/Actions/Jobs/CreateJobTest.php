<?php

use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use App\Scraper\Webpage;
use App\Notifications\JobFetched;
use Illuminate\Support\Facades\Notification;
use App\Actions\Jobs\CreateJob as CreateJobAction;

it('creates a job and company with the provided payload', function () {
    [$job, $data] = performCreateJobActionForTest();

    expect($job)->toBeInstanceOf(Job::class);
    expect($job->url)->toBe($data->url);
    expect($job->company->name)->toBe('Acme Inc');
    expect($job->locations)->toHaveCount(1);
    expect($job->locations->first()->display_name)->toBe('San Francisco, California, United States');
    expect($job->technologies)->toMatchArray(['PHP', 'Laravel', 'MySQL']);
    expect($job->perks)->toMatchArray(['Remote stipend', 'Wellness budget']);
    expect($job->equity)->toBeTrue();
});

it('notifies the admin when a job is created', function () {
    [, , $admin] = performCreateJobActionForTest();

    Notification::assertSentToTimes($admin, JobFetched::class, 1);
});

it('updates matching jobs when payloads reference an existing url', function () {
    [$updated, $existing] = updateExistingJobScenario();

    expect($updated->id)->toBe($existing->id);
    expect($updated->title)->toBe('New title');
    expect($updated->min_salary)->toBe(0);
    expect($updated->max_salary)->toBe(0);
    expect($updated->locations->pluck('display_name')->all())->toBe(['Paris, France']);
});

it('updates matching companies when payloads reference an existing name', function () {
    [$updated, , $company] = updateExistingJobScenario();

    expect($updated->company_id)->toBe($company->id);
    expect($updated->company->url)->toBe('https://acme.new');
    expect($updated->company->domain)->toBe('acme.new');
    expect($updated->company->logo)->toBe('https://cdn.test/acme-new.png');
    expect($updated->company->about)->toBe('Updated about.');
});

it('reuses company by normalized domain even when the provided name differs', function () {
    Notification::fake();

    $existing = Company::factory()->create([
        'name' => 'Old Name Co',
        'url' => 'https://www.example.org/about',
    ]);

    $webpage = new Webpage(
        'https://example.org/job',
        'https://example.org/image.jpg',
        'Title',
        '<html><body><h1>Title</h1><p>Content</p></body></html>'
    );

    $data = (object) array_merge(defaultJobPayload(), [
        'company' => (object) [
            'name' => 'New Name Inc',
            'url' => 'HTTPS://example.org/careers?utm=1',
            'logo' => 'https://cdn.example.org/logo.png',
            'about' => 'Updated about.',
        ],
    ]);

    $job = app(CreateJobAction::class)->create($webpage, $data)->refresh();

    expect($job->company_id)->toBe($existing->id)
        ->and($job->company->name)->toBe('New Name Inc')
        ->and($job->company->domain)->toBe('example.org')
        ->and($job->company->url)->toBe('https://example.org/careers');
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
        'location_entities' => [],
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

/**
 * @return array{0: \App\Models\Job, 1: object, 2: \App\Models\User}
 */
function performCreateJobActionForTest(array $overrides = []) : array
{
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

    $data = (object) array_merge(defaultJobPayload(), $overrides);

    $job = app(CreateJobAction::class)->create($webpage, $data);

    return [$job, $data, $admin];
}

/**
 * @return array{0: \App\Models\Job, 1: \App\Models\Job, 2: \App\Models\Company}
 */
function updateExistingJobScenario() : array
{
    Notification::fake();

    $company = Company::factory()->create([
        'name' => 'Acme Inc',
        'url' => 'https://acme.test',
        'domain' => 'acme.test',
    ]);

    $existing = Job::factory()->for($company)->create([
        'url' => 'https://example.com/jobs/dup',
        'title' => 'Old title',
        'min_salary' => 1,
        'equity' => false,
    ]);

    $webpage = new Webpage(
        'https://example.com/job',
        'https://example.com/image.jpg',
        'Title',
        '<html><body><h1>Title</h1><p>Content</p></body></html>'
    );

    $data = (object) array_merge(defaultJobPayload(), [
        'url' => 'https://example.com/jobs/dup',
        'title' => 'New title',
        'description' => 'New description',
        'technologies' => ['PHP'],
        'perks' => [],
        'locations' => ['Paris, France'],
        'location_entities' => [[
            'city' => 'Paris',
            'region' => null,
            'country' => 'France',
        ]],
        'min_salary' => null,
        'max_salary' => null,
        'company' => (object) [
            'name' => 'Acme Inc',
            'url' => 'https://acme.new',
            'logo' => 'https://cdn.test/acme-new.png',
            'about' => 'Updated about.',
        ],
    ]);

    $updated = app(CreateJobAction::class)->create($webpage, $data)->refresh();

    Notification::assertNothingSent();

    return [$updated, $existing, $company];
}

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
        'locations' => ['San Francisco, California, United States'],
        'location_entities' => [[
            'city' => 'San Francisco',
            'region' => 'California',
            'country' => 'United States',
        ]],
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
