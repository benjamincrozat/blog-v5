<?php

use App\Models\Job;
use App\Models\User;
use App\Jobs\ReviseJob;
use App\Jobs\ScrapeJob;

use function Pest\Laravel\actingAs;

use Illuminate\Support\Facades\Bus;

use function Pest\Livewire\livewire;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Testing\TestAction;
use App\Filament\Resources\Jobs\Pages\ListJobs;

use function Pest\Laravel\assertDatabaseMissing;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    Bus::fake([ScrapeJob::class, ReviseJob::class]);
});

it('renders jobs with columns and values', function () {
    $job = Job::factory()->create([
        'title' => 'Senior PHP Engineer',
        'setting' => 'fully-remote',
        'equity' => true,
        'min_salary' => 50000,
        'max_salary' => 100000,
        'currency' => 'USD',
        'html' => '<p>content</p>',
    ]);

    livewire(ListJobs::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$job])
        ->assertSeeText('Senior PHP Engineer')
        ->assertSeeText('Fully-remote')
        ->assertSeeText('Equity: Yes');
});

it('scrapes a job again from the table action', function () {
    $job = Job::factory()->create([
        'html' => '<p>content</p>',
    ]);

    livewire(ListJobs::class)
        ->callTableAction('scrape', $job)
        ->assertNotified('The job has been queued for scraping.');

    Bus::assertDispatched(ScrapeJob::class);
});

it('revises a job with additional instructions', function () {
    $job = Job::factory()->create([
        'html' => '<p>Has HTML</p>',
    ]);

    livewire(ListJobs::class)
        ->callTableAction('revise', $job, data: ['additional_instructions' => 'Tighten copy'])
        ->assertNotified('The job has been queued for revision.');

    Bus::assertDispatched(ReviseJob::class);
});

it('hides revise action when job lacks html', function () {
    $job = Job::factory()->create([
        'html' => '',
    ]);

    livewire(ListJobs::class)
        ->assertActionHidden(TestAction::make('revise')->table($job));
});

it('runs bulk revise only for jobs with html and warns otherwise', function () {
    $withHtml = Job::factory()->create([
        'html' => '<p>content</p>',
    ]);

    $withoutHtml = Job::factory()->create([
        'html' => '',
    ]);

    livewire(ListJobs::class)
        ->selectTableRecords([$withHtml, $withoutHtml])
        ->callAction(TestAction::make('revise')->table()->bulk())
        ->assertNotified();

    Bus::assertDispatched(ReviseJob::class, 1);
});

it('deletes jobs with the bulk action', function () {
    $jobs = Job::factory()->count(2)->create();

    livewire(ListJobs::class)
        ->selectTableRecords($jobs)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertCountTableRecords(0);

    $jobs->each(fn (Job $job) => assertDatabaseMissing('job_listings', ['id' => $job->id]));
});
