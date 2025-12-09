<?php

use App\Models\Job;
use App\Jobs\ReviseJob;
use Facades\App\Actions\Jobs\ReviseJob as ReviseJobAction;

it('delegates the revise job with optional instructions', function () {
    $job = Job::factory()->make();

    ReviseJobAction::shouldReceive('revise')
        ->once()
        ->with($job, null);

    (new ReviseJob($job))->handle();
});
