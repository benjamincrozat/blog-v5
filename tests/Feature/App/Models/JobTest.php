<?php

use App\Models\Job;
use App\Models\Company;
use App\Models\Redirect;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseCount;

it('generates a randomized slug on create based on the title', function () {
    $company = Company::factory()->create(['name' => 'Acme Inc']);

    $job = Job::factory()->for($company)->create([
        'title' => 'Senior PHP Developer',
    ]);

    expect($job->slug)
        ->toMatch('/^[a-z0-9]{10}-senior-php-developer$/');
});

it('updates the slug when the title changes and creates a redirect', function () {
    $company = Company::factory()->create(['name' => 'Acme Inc']);

    $job = Job::factory()->for($company)->create([
        'title' => 'Senior PHP Developer',
    ]);

    $old = $job->slug;

    assertDatabaseCount(Redirect::class, 0);

    $job->update(['title' => 'Lead PHP Engineer']);

    $new = $job->slug;

    expect($new)
        ->not->toBe($old)
        ->and($new)->toMatch('/^[a-z0-9]{10}-lead-php-engineer$/');

    assertDatabaseHas(Redirect::class, [
        'from' => 'jobs/' . $old,
        'to' => 'jobs/' . $new,
    ]);
});

it('casts attributes correctly', function () {
    $job = Job::factory()->create();

    expect($job->technologies)->toBeArray()
        ->and($job->locations)->toBeIterable()
        ->and($job->perks)->toBeArray()
        ->and($job->interview_process)->toBeArray()
        ->and($job->equity)->toBeBool();
});

it('belongs to a company', function () {
    $job = Job::factory()->create();

    expect($job->company)->toBeInstanceOf(Company::class);
});
