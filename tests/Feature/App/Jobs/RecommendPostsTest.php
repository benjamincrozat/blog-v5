<?php

use App\Models\Post;
use Mockery\MockInterface;
use App\Jobs\RecommendPosts;
use App\Actions\RecommendPosts as RecommendPostsAction;

it('delegates recommending posts for a post', function () {
    $post = Post::factory()->make();

    $action = mock(RecommendPostsAction::class, function (MockInterface $mock) use ($post) {
        $mock->shouldReceive('recommend')
            ->once()
            ->with($post);
    });

    app()->instance(RecommendPostsAction::class, $action);

    (new RecommendPosts($post))->handle();
});
