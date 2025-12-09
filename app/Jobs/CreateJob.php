<?php

namespace App\Jobs;

use App\Scraper\Webpage;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Webpage $webpage,
        public object $data,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\Jobs\CreateJob::class)
            ->create($this->webpage, $this->data);
    }
}
