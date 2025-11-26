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

    fakeBlogPostResponse(blogPostPayload());
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

    fakeBlogPostResponse(blogPostPayload([
        'title' => 'Approved title',
        'content' => 'Approved content',
        'description' => 'Approved description',
    ]));
    Bus::fake();

    $post = app(CreatePostForLink::class)->create($link);

    expect($post->published_at)->not->toBeNull();
    expect($post->published_at->isSameSecond($approvedAt))->toBeTrue();
});

it('rolls back and throws on invalid model output', function () {
    $admin = User::factory()->create(['github_login' => 'benjamincrozat']);
    $oldPost = Post::factory()->create(['user_id' => $admin->id]);
    $link = Link::factory()->create(['post_id' => $oldPost->id]);

    fakeBlogPostResponse('not-json', includeSearch: false);
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

it('throws when Benjamin Crozat user is missing', function () {
    $link = Link::factory()->create();

    fakeBlogPostResponse(blogPostPayload([
        'title' => 'Missing owner title',
        'content' => 'Missing owner content',
        'description' => 'Missing owner description',
    ]), 'ws_missing_owner');
    Bus::fake();

    expect(fn () => app(CreatePostForLink::class)->create($link))
        ->toThrow(RuntimeException::class, 'Benjamin Crozat user not found.');

    assertDatabaseCount('posts', 0);

    assertDatabaseHas('links', [
        'id' => $link->id,
        'post_id' => null,
    ]);

    Bus::assertNotDispatched(RecommendPosts::class);
});

function blogPostPayload(array $overrides = []) : string
{
    return json_encode(array_merge([
        'title' => 'Sample title',
        'content' => 'Sample content',
        'description' => 'Sample description',
    ], $overrides));
}

function fakeBlogPostResponse(
    string $text, string $searchId = 'ws_dummy', bool $includeSearch = true
) : void {
    $output = [[
        'type' => 'message',
        'status' => 'completed',
        'role' => 'assistant',
        'content' => [[
            'type' => 'output_text',
            'text' => $text,
            'annotations' => [],
        ]],
    ]];

    if ($includeSearch) {
        $output[] = [
            'type' => 'web_search_call',
            'id' => $searchId,
            'status' => 'completed',
        ];
    }

    OpenAI::fake([
        CreateResponse::fake([
            'text' => [
                'format' => blogPostResponseFormat(),
            ],
            'output' => $output,
        ]),
    ]);
}

// Keeps the OpenAI schema definition in one place for the suite.
function blogPostResponseFormat() : array
{
    return [
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
    ];
}
