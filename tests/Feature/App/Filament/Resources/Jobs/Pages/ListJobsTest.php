<?php

use App\Models\Job;
use App\Models\User;

use function Pest\Laravel\actingAs;
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
        ->assertSeeText('Fully remote')
        ->assertSeeText('Equity: Yes');
});

it('deletes jobs with the bulk action', function () {
    $jobs = Job::factory()->count(2)->create();

    livewire(ListJobs::class)
        ->selectTableRecords($jobs)
        ->callAction(TestAction::make(DeleteBulkAction::class)->table()->bulk())
        ->assertCountTableRecords(0);

    $jobs->each(fn (Job $job) => assertDatabaseMissing('job_listings', ['id' => $job->id]));
});
