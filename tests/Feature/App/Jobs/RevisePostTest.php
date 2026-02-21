<?php

use App\Models\Post;
use App\Models\Report;
use App\Jobs\RevisePost;
use Mockery\MockInterface;
use App\Actions\RevisePost as RevisePostAction;

it('delegates the revise post job with report and instructions', function () {
    $post = Post::factory()->make();
    $report = Report::factory()->make();

    $action = mock(RevisePostAction::class, function (MockInterface $mock) use ($post, $report) {
        $mock->shouldReceive('revise')
            ->once()
            ->with($post, $report, 'Tighten copy');
    });

    app()->instance(RevisePostAction::class, $action);

    (new RevisePost($post, $report, 'Tighten copy'))->handle();
});
