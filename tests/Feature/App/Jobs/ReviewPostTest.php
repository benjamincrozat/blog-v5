<?php

use App\Models\Post;
use App\Jobs\ReviewPost;
use Mockery\MockInterface;
use App\Actions\ReviewPost as ReviewPostAction;

it('delegates the review job with additional instructions', function () {
    $post = Post::factory()->make();

    $action = mock(ReviewPostAction::class, function (MockInterface $mock) use ($post) {
        $mock->shouldReceive('review')
            ->once()
            ->with($post, 'Add a TL;DR');
    });

    app()->instance(ReviewPostAction::class, $action);

    (new ReviewPost($post, 'Add a TL;DR'))->handle();
});
