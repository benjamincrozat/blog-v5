<?php

use App\Models\Link;
use App\Models\Post;

use function Pest\Laravel\get;

it('lists the latest 50 posts that are not associated to a link and shows the description instead of the content', function () {
    $posts = Post::factory(30)->create();

    Link::factory(10)->withPost()->create();

    $response = get(route('feeds.main'))
        ->assertOk();

    expect(Post::count())->toBe(40);

    expect($posts)->toHaveCount(30);

    $posts->each(function (Post $post) use ($response) {
        $response->assertSee($post->slug);
        $response->assertSee($post->title, escape: false);
        $response->assertSee($post->description, escape: false);
        $response->assertSee(route('posts.show', $post));
        $response->assertSee($post->user->name, escape: false);
        $response->assertDontSee($post->content);
    });
});

it('lists the latest approved community links in the Atom feed', function () {
    $approvedLinks = Link::factory(30)->approved()->create();

    Link::factory()->create([
        'is_approved' => null,
        'is_declined' => null,
    ]);

    Link::factory()->declined()->create();

    $response = get(route('feeds.links'))
        ->assertOk();

    $approvedLinks->each(function (Link $link) use ($response) {
        $response->assertSee($link->title, escape: false);
        $response->assertSee($link->description, escape: false);
        $response->assertSee($link->url);
        $response->assertSee($link->user->name, escape: false);
    });
});
