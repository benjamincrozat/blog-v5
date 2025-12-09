<?php

use App\Models\Post;
use App\Jobs\ReviewPost;
use Facades\App\Actions\ReviewPost as ReviewPostAction;

it('delegates the review job with additional instructions', function () {
    $post = Post::factory()->make();

    ReviewPostAction::shouldReceive('review')
        ->once()
        ->with($post, 'Add a TL;DR');

    (new ReviewPost($post, 'Add a TL;DR'))->handle();
});
