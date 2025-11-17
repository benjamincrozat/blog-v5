<?php

use App\Jobs\CreateJob;
use App\Scraper\Webpage;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Queue;
use OpenAI\Responses\Responses\CreateResponse;
use App\Actions\FetchJobData as FetchJobDataAction;

it('dispatches CreateJob with parsed data from OpenAI', function () {
    Queue::fake();

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
        'locations' => ['Remote', 'US'],
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

    app(FetchJobDataAction::class)->fetch($webpage);

    Queue::assertPushed(CreateJob::class, function ($job) use ($webpage) {
        return isset($job->data) && is_object($job->data) && $job->data->url === $webpage->url;
    });
});
