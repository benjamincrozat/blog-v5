<?php

use App\Scraper\Webpage;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Responses\CreateResponse;
use App\Actions\Jobs\FetchJobData as FetchJobDataAction;

it('returns parsed data from OpenAI as an array', function () {

    $webpage = new Webpage(
        url: 'https://example.com/job/123',
        imageUrl: null,
        title: 'Senior PHP Developer',
        content: 'Build and maintain Laravel apps.',
    );

    $payload = json_encode([
        'url' => $webpage->url,
        'language' => 'en',
        'title' => 'Senior PHP Developer',
        'description' => 'Build and maintain Laravel apps.',
        'technologies' => ['PHP', 'Laravel', 'MySQL'],
        'locations' => ['San Francisco, California, United States'],
        'location_entities' => [[
            'city' => 'San Francisco',
            'region' => 'California',
            'country' => 'United States',
        ]],
        'setting' => 'fully-remote',
        'employment_status' => 'full-time',
        'seniority' => 'senior',
        'equity' => true,
        'min_salary' => 100000,
        'max_salary' => 150000,
        'currency' => 'USD',
        'perks' => ['Remote stipend'],
        'interview_process' => ['Recruiter screen', 'Technical interview'],
        'company' => [
            'name' => 'Acme Inc',
            'url' => 'https://acme.test',
            'logo' => 'https://cdn.test/acme.png',
            'about' => 'Acme builds tools for developers.',
        ],
        'source' => 'ExampleBoard',
    ]);

    OpenAI::fake([
        CreateResponse::fake([
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'job',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'url' => ['type' => 'string'],
                        ],
                    ],
                ],
            ],
            'output' => [
                [
                    'type' => 'message',
                    'status' => 'completed',
                    'role' => 'assistant',
                    'content' => [[
                        'type' => 'output_text',
                        'text' => $payload,
                        'annotations' => [],
                    ]],
                ],
                [
                    'type' => 'web_search_call',
                    'id' => 'ws_dummy',
                    'status' => 'completed',
                ],
            ],
        ]),
    ]);

    $data = app(FetchJobDataAction::class)->fetch($webpage);

    expect($data)->toBeArray()
        ->and($data['url'])->toBe($webpage->url)
        ->and($data['company']['url'])->toBe('https://acme.test')
        ->and($data['location_entities'][0]['city'])->toBe('San Francisco');
});
