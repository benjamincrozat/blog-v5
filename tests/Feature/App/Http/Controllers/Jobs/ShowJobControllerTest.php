<?php

use App\Models\Job;

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

    $job = Job::factory()->create([
        'locations' => ['Paris, France'],
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ]);

    $schema = extractJobPostingSchema($job);

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

it('adds remote defaults when the job setting uses an alternate remote label', function () {
    $job = Job::factory()->create([
        'locations' => [],
        'setting' => 'Remote',
    ]);

    $schema = extractJobPostingSchema($job);

    expect($schema['jobLocationType'])->toBe('TELECOMMUTE');
    expect($schema['jobLocation'])
        ->toMatchArray([
            '@type' => 'Place',
            'name' => 'Remote',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'Worldwide',
            ],
        ]);

    expect($schema['applicantLocationRequirements'])
        ->toMatchArray([
            '@type' => 'Country',
            'name' => 'Worldwide',
        ]);
});

it('removes remote keywords from location strings while keeping address data', function () {
    $job = Job::factory()->create([
        'locations' => ['Berlin, Germany (Remote)'],
        'setting' => 'fully-remote',
    ]);

    $schema = extractJobPostingSchema($job);

    expect($schema['jobLocation'])
        ->toMatchArray([
            '@type' => 'Place',
            'name' => 'Berlin, Germany (Remote)',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Berlin',
                'addressCountry' => 'Germany',
            ],
        ]);

    expect($schema['applicantLocationRequirements'])
        ->toMatchArray([
            '@type' => 'Country',
            'name' => 'Germany',
        ]);
});

/**
 * @return array<string, mixed>
 */
function extractJobPostingSchema(Job $job) : array
{
    $response = get(route('jobs.show', $job->slug));

    $response->assertOk();

    $response->assertSee('<script type="application/ld+json">', false);

    $jsonLd = (new Crawler($response->getContent()))
        ->filter('script[type="application/ld+json"]')
        ->first()
        ->text();

    return json_decode(trim($jsonLd), true, 512, JSON_THROW_ON_ERROR);
}
