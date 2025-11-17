<?php

use App\Jobs\CreateJob;
use App\Scraper\Webpage;
use Facades\App\Actions\CreateJob as CreateJobAction;

it('invokes the action with provided data', function () {
    $webpage = new Webpage(
        'https://example.com/jobs/789',
        'https://example.com/jobs/789/logo.png',
        'Title',
        '<html><body><h1>Title</h1><p>Content</p></body></html>'
    );

    $data = (object) [
        'url' => 'https://example.com/jobs/789',
        'source' => 'Board',
        'language' => 'en',
        'title' => 'Title',
        'description' => 'Desc',
        'technologies' => ['PHP'],
        'perks' => [],
        'locations' => [],
        'setting' => 'fully-remote',
        'min_salary' => 0,
        'max_salary' => 0,
        'currency' => 'USD',
        'equity' => false,
        'interview_process' => [],
        'company' => (object) [
            'name' => 'Foo LLC',
            'url' => null,
            'logo' => null,
            'about' => 'About Foo.',
        ],
    ];

    CreateJobAction::shouldReceive('create')
        ->once()
        ->with($webpage, $data);

    (new CreateJob($webpage, $data))->handle();
});
