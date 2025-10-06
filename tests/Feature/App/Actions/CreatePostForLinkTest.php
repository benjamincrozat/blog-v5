<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use App\Jobs\RecommendPosts;
use App\Actions\CreatePostForLink;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Bus;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\assertDatabaseCount;

use OpenAI\Responses\Responses\CreateResponse;

it('creates a post for a pending link and soft-deletes previous post', function () {
    $admin = User::factory()->create(['github_login' => 'benjamincrozat']);
    $oldPost = Post::factory()->create(['user_id' => $admin->id]);
    $link = Link::factory()->create(['post_id' => $oldPost->id]);

    $payload = json_encode([
        'title' => 'Sample title',
        'content' => 'Sample content',
        'description' => 'Sample description',
    ]);

    OpenAI::fake([
        CreateResponse::fake([
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'blog_post',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'content' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                        ],
                        'required' => ['title', 'content', 'description'],
                        'additionalProperties' => false,
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
    Bus::fake();

    $post = app(CreatePostForLink::class)->create($link);

    expect($post->user_id)->toBe($admin->id);
    expect($post->published_at)->toBeNull();

    assertDatabaseHas('links', [
        'id' => $link->id,
        'post_id' => $post->id,
    ]);

    assertSoftDeleted('posts', ['id' => $oldPost->id]);

    Bus::assertDispatched(RecommendPosts::class, function ($job) use ($post) {
        return $job->post->is($post) && $job->afterCommit;
    });
});

it('creates a post for an approved link and uses approval date as published_at', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    $approvedAt = now()->subDay();
    $link = Link::factory()->approved()->create(['is_approved' => $approvedAt]);

    $payload = json_encode([
        'title' => 'Approved title',
        'content' => 'Approved content',
        'description' => 'Approved description',
    ]);

    OpenAI::fake([
        CreateResponse::fake([
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'blog_post',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'content' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                        ],
                        'required' => ['title', 'content', 'description'],
                        'additionalProperties' => false,
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
    Bus::fake();

    $post = app(CreatePostForLink::class)->create($link);

    expect($post->published_at)->not->toBeNull();
    expect($post->published_at->isSameSecond($approvedAt))->toBeTrue();
});

it('rolls back and throws on invalid model output', function () {
    $admin = User::factory()->create(['github_login' => 'benjamincrozat']);
    $oldPost = Post::factory()->create(['user_id' => $admin->id]);
    $link = Link::factory()->create(['post_id' => $oldPost->id]);

    $payload = 'not-json';

    OpenAI::fake([
        CreateResponse::fake([
            'text' => [
                'format' => [
                    'type' => 'json_schema',
                    'name' => 'blog_post',
                    'strict' => true,
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'title' => ['type' => 'string'],
                            'content' => ['type' => 'string'],
                            'description' => ['type' => 'string'],
                        ],
                        'required' => ['title', 'content', 'description'],
                        'additionalProperties' => false,
                    ],
                ],
            ],
            'output' => [[
                'type' => 'message',
                'status' => 'completed',
                'role' => 'assistant',
                'content' => [[
                    'type' => 'output_text',
                    'text' => $payload,
                    'annotations' => [],
                ]],
            ]],
        ]),
    ]);
    Bus::fake();

    expect(fn () => app(CreatePostForLink::class)->create($link))
        ->toThrow(RuntimeException::class);

    // No new posts created and link unchanged.
    assertDatabaseCount('posts', 1);
    assertDatabaseHas('links', [
        'id' => $link->id,
        'post_id' => $oldPost->id,
    ]);

    Bus::assertNotDispatched(RecommendPosts::class);
});
