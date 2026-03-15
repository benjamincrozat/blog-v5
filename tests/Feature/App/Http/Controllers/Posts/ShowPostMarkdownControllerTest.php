<?php

use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('returns markdown for admins', function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $post = Post::factory()->for(User::factory())->create([
        'source_uuid' => '01KKR5KX7EQNNA8HD94TAF8ZRD',
    ]);

    actingAs($admin);

    get(route('admin.posts.markdown', $post))
        ->assertOk()
        ->assertHeader('content-type', 'text/plain; charset=UTF-8')
        ->assertContent($post->toMarkdown());
});

it('forbids non-admin users', function () {
    $user = User::factory()->create([
        'github_login' => 'someone-else',
    ]);

    $post = Post::factory()->for(User::factory())->create();

    actingAs($user);

    get(route('admin.posts.markdown', $post))
        ->assertForbidden();
});
