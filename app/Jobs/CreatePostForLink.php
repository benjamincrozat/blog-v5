<?php

namespace App\Jobs;

use App\Models\Link;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Defines the CreatePostForLink implementation.
 */
class CreatePostForLink implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Link $link,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\CreatePostForLink::class)->create($this->link);
    }
}
