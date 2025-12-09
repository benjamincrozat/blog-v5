<?php

use App\Models\Post;
use App\Models\Report;
use App\Jobs\RevisePost;
use Facades\App\Actions\RevisePost as RevisePostAction;

it('delegates the revise post job with report and instructions', function () {
    $post = Post::factory()->make();
    $report = Report::factory()->make();

    RevisePostAction::shouldReceive('revise')
        ->once()
        ->with($post, $report, 'Tighten copy');

    (new RevisePost($post, $report, 'Tighten copy'))->handle();
});
