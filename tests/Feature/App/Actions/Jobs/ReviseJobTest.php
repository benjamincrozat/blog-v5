<?php

use App\Models\Job;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Responses\CreateResponse;
use App\Actions\Jobs\ReviseJob as ReviseJobAction;

it('throws when job has no html content', function () {
    $job = Job::factory()->make([
        'html' => null,
    ]);

    expect(fn () => app(ReviseJobAction::class)->revise($job))
        ->toThrow(Exception::class, 'The job cannot be revised because it has no HTML content.');
});

it('updates or creates job from OpenAI structured output', function () {
    $existing = Job::factory()->create([
        'url' => 'https://example.com/jobs/abc',
        'title' => 'Old title',
        'min_salary' => 1,
    ]);

    $payload = json_encode([
        'url' => 'https://example.com/jobs/abc',
        'source' => 'ExampleBoard',
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

    $updated = app(ReviseJobAction::class)->revise($existing)->refresh();

    expect($updated->id)->toBe($existing->id)
        ->and($updated->title)->toBe('Senior PHP Developer')
        ->and($updated->min_salary)->toBe(100000)
        ->and($updated->max_salary)->toBe(150000)
        ->and($updated->currency)->toBe('USD')
        ->and($updated->equity)->toBeTrue()
        ->and($updated->technologies)->toMatchArray(['PHP', 'Laravel', 'MySQL'])
        ->and($updated->perks)->toMatchArray(['Remote stipend'])
        ->and($updated->locations->pluck('display_name')->all())->toBe(['San Francisco, California, United States'])
        ->and($updated->setting)->toBe('fully-remote')
        ->and($updated->interview_process)->toMatchArray(['Recruiter screen', 'Technical interview']);
});

it('updates existing job and applies defaults when fields are null', function () {
    $original = Job::factory()->create([
        'url' => 'https://example.com/jobs/original',
        'min_salary' => 1,
        'max_salary' => 2,
    ]);

    $payload = json_encode([
        'url' => 'https://example.com/jobs/original',
        'source' => 'AnotherBoard',
        'language' => 'en',
        'title' => 'PHP Engineer',
        'description' => 'Maintain Laravel applications.',
        'technologies' => ['PHP', 'Laravel'],
        'locations' => [],
        'location_entities' => [],
        'setting' => 'fully-remote',
        'equity' => null,
        'min_salary' => null,
        'max_salary' => null,
        'currency' => null,
        'perks' => [],
        'interview_process' => [],
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

    $updated = app(ReviseJobAction::class)->revise($original)->refresh();

    expect($updated->id)->toBe($original->id)
        ->and($updated->url)->toBe('https://example.com/jobs/original')
        ->and($updated->title)->toBe('PHP Engineer')
        ->and($updated->min_salary)->toBe(0) // Defaults to 0 when null.
        ->and($updated->max_salary)->toBe(0) // Defaults to 0 when null.
        ->and($updated->currency)->toBeNull()
        ->and($updated->equity)->toBeFalse()
        ->and($updated->technologies)->toMatchArray(['PHP', 'Laravel'])
        ->and($updated->perks)->toMatchArray([])
        ->and($updated->locations)->toHaveCount(0)
        ->and($updated->setting)->toBe('fully-remote')
        ->and($updated->interview_process)->toMatchArray([]);
});

it('passes additional instructions to the OpenAI request', function () {
    $job = Job::factory()->create([
        'url' => 'https://example.com/jobs/xyz',
        'title' => 'Title',
        'html' => '<p>Some HTML</p>',
    ]);

    $additional = 'Rewrite the title to be more specific.';

    $payload = json_encode([
        'url' => 'https://example.com/jobs/xyz',
        'source' => 'ExampleBoard',
        'language' => 'en',
        'title' => 'Title',
        'description' => 'Description',
        'technologies' => ['PHP'],
        'locations' => [],
        'location_entities' => [],
        'setting' => 'fully-remote',
        'equity' => false,
        'min_salary' => null,
        'max_salary' => null,
        'currency' => null,
        'perks' => [],
        'interview_process' => [],
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

    app(ReviseJobAction::class)->revise($job, $additional);

    OpenAI::responses()->assertSent(function ($method, $args) use ($job, $additional) {
        // Expect we hit the responses.create endpoint.
        if ('create' !== $method) {
            return false;
        }

        // Validate the structure used in the call and inclusion of additional instructions.
        expect($args['input'][0]['role'])->toBe('developer');
        expect($args['input'][1]['role'])->toBe('user');

        $userContent = $args['input'][1]['content'][0]['text'];

        expect($userContent)->toContain('URL: ' . $job->url)
            ->and($userContent)->toContain('Title: ' . $job->title)
            ->and($userContent)->toContain('Additional instructions: ' . $additional);

        return true;
    });
});
