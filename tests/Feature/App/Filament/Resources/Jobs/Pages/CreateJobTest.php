<?php

use App\Models\User;
use App\Jobs\ScrapeJob;

use function Pest\Laravel\actingAs;

use Illuminate\Support\Facades\Bus;

use function Pest\Livewire\livewire;

use App\Filament\Resources\Jobs\Pages\CreateJob;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    Bus::fake(ScrapeJob::class);
});

it('dispatches scraping when fetching a job', function () {
    livewire(CreateJob::class)
        ->fillForm([
            'url' => 'https://example.com/jobs/1',
        ])
        ->call('fetch')
        ->assertHasNoFormErrors()
        ->assertNotified('Fetching the job');

    Bus::assertDispatched(ScrapeJob::class);
});

it('validates url on fetch', function () {
    livewire(CreateJob::class)
        ->fillForm([
            'url' => 'not-a-url',
        ])
        ->call('fetch')
        ->assertHasFormErrors([
            'url' => 'url',
        ]);
});
