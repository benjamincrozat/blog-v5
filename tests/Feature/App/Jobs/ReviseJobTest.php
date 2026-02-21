<?php

use App\Models\Job;
use App\Jobs\ReviseJob;
use Mockery\MockInterface;
use App\Actions\Jobs\ReviseJob as ReviseJobAction;

it('delegates the revise job with optional instructions', function () {
    $job = Job::factory()->make();

    $action = mock(ReviseJobAction::class, function (MockInterface $mock) use ($job) {
        $mock->shouldReceive('revise')
            ->once()
            ->with($job, null);
    });

    app()->instance(ReviseJobAction::class, $action);

    (new ReviseJob($job))->handle();
});
