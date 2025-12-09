<?php

use App\Models\Job;
use App\Models\User;
use App\Models\Location;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Jobs\Pages\EditJob;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    Model::preventAccessingMissingAttributes(false);

    if (! Schema::hasColumn('job_listings', 'location_id')) {
        Schema::table('job_listings', function ($table) {
            $table->foreignId('location_id')->nullable()->after('company_id');
        });
    }

    if (! Schema::hasColumn('locations', 'display_name')) {
        Schema::table('locations', function ($table) {
            $table->string('display_name')->nullable();
        });
    }

    Job::resolveRelationUsing('location', function (Job $job) : BelongsTo {
        return $job->belongsTo(Location::class, 'location_id');
    });

    Job::creating(function (Job $job) {
        if (blank($job->html)) {
            $job->html = '<p>placeholder</p>';
        }
    });
});

it('loads existing job data', function () {
    $location = Location::factory()->create([
        'display_name' => 'Paris, FR',
        'city' => 'Paris',
        'region' => 'IDF',
        'country' => 'FR',
    ]);

    $job = Job::factory()->create([
        'title' => 'Existing Job',
        'slug' => 'existing-job',
        'description' => 'Old description',
        'location_id' => $location->getKey(),
    ]);

    livewire(EditJob::class, ['record' => $job->getKey()])
        ->assertFormSet([
            'title' => 'Existing Job',
        ]);
});

it('saves changes to a job', function () {
    $location = Location::factory()->create([
        'display_name' => 'Paris, FR',
        'city' => 'Paris',
        'region' => 'IDF',
        'country' => 'FR',
    ]);

    $job = Job::factory()->create([
        'title' => 'Existing Job',
        'slug' => 'existing-job',
        'description' => 'Old description',
        'location_id' => $location->getKey(),
    ]);

    livewire(EditJob::class, ['record' => $job->getKey()])
        ->fillForm([
            'title' => 'Updated Job',
            'slug' => 'updated-job',
            'description' => 'Updated description',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($job->refresh()->title)->toBe('Updated Job');
    expect($job->refresh()->slug)->toContain('updated-job');
});
