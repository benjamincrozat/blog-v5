<?php

use App\Scraper\Webpage;
use App\Jobs\FetchJobData;
use Facades\App\Actions\Jobs\FetchJobData as FetchJobDataAction;

it('delegates the fetch job data job to its action', function () {
    $webpage = new Webpage('https://example.com', null, 'Title', '<p>Content</p>');

    FetchJobDataAction::shouldReceive('fetch')
        ->once()
        ->with($webpage)
        ->andReturn([
            'url' => $webpage->url,
            'language' => 'en',
            'title' => 'Title',
            'description' => 'Desc',
            'technologies' => ['PHP'],
            'location_entities' => [[
                'city' => 'Paris',
                'region' => null,
                'country' => 'France',
            ]],
            'setting' => 'fully-remote',
            'employment_status' => null,
            'seniority' => null,
            'equity' => false,
            'min_salary' => null,
            'max_salary' => null,
            'currency' => null,
            'perks' => [],
            'interview_process' => [],
            'company' => [
                'name' => 'Acme Inc',
                'url' => 'https://acme.test',
                'logo' => 'https://cdn.test/logo.png',
                'about' => 'About.',
            ],
            'source' => 'ExampleBoard',
        ]);

    (new FetchJobData($webpage))->handle();
});
