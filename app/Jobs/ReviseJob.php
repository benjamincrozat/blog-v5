<?php

namespace App\Jobs;

use App\Models\Job;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReviseJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Job $jobModel,
        public ?string $additionalInstructions = null,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\Jobs\ReviseJob::class)->revise($this->jobModel, $this->additionalInstructions);
    }
}
