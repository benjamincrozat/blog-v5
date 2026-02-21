<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Defines the ReviewPost implementation.
 */
class ReviewPost implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Post $post,
        public ?string $additionalInstructions = null,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\ReviewPost::class)->review($this->post, $this->additionalInstructions);
    }
}
