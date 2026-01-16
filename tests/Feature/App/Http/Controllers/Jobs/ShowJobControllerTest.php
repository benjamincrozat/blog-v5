<?php

use App\Models\Job;
use App\Models\Location;

use function Pest\Laravel\get;

use Illuminate\Support\Carbon;
use Symfony\Component\DomCrawler\Crawler;

it('shows a job', function () {
    $job = Job::factory()->create();

    get(route('jobs.show', $job))
        ->assertOk()
        ->assertViewIs('jobs.show')
        ->assertViewHas('job', $job);
});

it('returns 404 for unknown job', function () {
    get(route('jobs.show', 'non-existent'))
        ->assertNotFound();
});

it('renders JobPosting JSON-LD on the job detail page', function () {
    Carbon::setTestNow(Carbon::create(2024, 1, 1, 0, 0, 0));

    $location = Location::factory()->create([
        'city' => 'Paris',
        'region' => null,
        'country' => 'France',
    ]);

    $job = Job::factory()->create([
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    $job->locations()->sync([$location->id]);

    $response = get(route('jobs.show', $job->slug));

    $response->assertOk();

    $response->assertSee('<script type="application/ld+json">', false);

    $schemas = collect(
        (new Crawler($response->getContent()))
            ->filter('script[type="application/ld+json"]')
            ->each(fn (Crawler $node) => json_decode(trim($node->text()), true, 512, JSON_THROW_ON_ERROR))
    );

    $schema = $schemas->firstWhere('@type', 'JobPosting');

    expect($schema)->not->toBeNull();

    expect($schema['@type'])->toBe('JobPosting');
    expect($schema['title'])->toBe($job->title);
    expect($schema['validThrough'])->toBe(Carbon::now()->addDays(30)->toIso8601String());
    expect($schema['applicantLocationRequirements'])
        ->toBe([
            '@type' => 'Country',
            'name' => 'France',
        ]);

    expect($schema['jobLocation'])
        ->toMatchArray([
            '@type' => 'Place',
            'name' => 'Paris, France',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Paris',
                'addressCountry' => 'France',
            ],
        ]);

    Carbon::setTestNow();
});
