<?php

use App\Models\Post;
use App\Jobs\RecommendPosts;
use Facades\App\Actions\RecommendPosts as RecommendPostsAction;

it('delegates recommending posts for a post', function () {
    $post = Post::factory()->make();

    RecommendPostsAction::shouldReceive('recommend')
        ->once()
        ->with($post);

    (new RecommendPosts($post))->handle();
});
