<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Jobs\RecommendPosts;
use Illuminate\Console\Command;

/**
 * Defines the GenerateRecommendationsCommand implementation.
 */
class GenerateRecommendationsCommand extends Command
{
    protected $signature = 'app:generate-recommendations {slug? : The slug of the post to generate recommendations for}';

    protected $description = 'Generate recommendations for posts';

    public function handle() : void
    {
        if ($slug = $this->argument('slug')) {
            $post = Post::query()->where('slug', $slug)->firstOrFail();

            RecommendPosts::dispatch($post);

            $this->info("Queued recommendation generation for \"$post->title\"…");

            return;
        }

        Post::query()
            ->published()
            ->where('is_commercial', false)
            ->cursor()
            ->each(function (Post $post) {
                RecommendPosts::dispatch($post);

                $this->info("Queued recommendation generation for \"$post->title\"…");
            });
    }
}
