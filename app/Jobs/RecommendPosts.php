<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Defines the RecommendPosts implementation.
 */
class RecommendPosts implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Post $post,
    ) {}

    public function handle() : void
    {
        app(\App\Actions\RecommendPosts::class)->recommend($this->post);
    }
}
