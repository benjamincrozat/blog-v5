<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Tool;
use App\Enums\ToolPricingModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tool>
 */
class ToolFactory extends Factory
{
    public function definition() : array
    {
        $slug = fake()->slug();

        return [
            'source_uuid' => (string) fake()->unique()->regexify('[0-9A-HJKMNP-TV-Z]{26}'),
            'source_path' => "{$slug}.md",
            'source_hash' => hash('sha256', $slug),
            'slug' => $slug,
            'name' => fake()->company(),
            'description' => fake()->sentence(),
            'content' => fake()->paragraph(),
            'website_url' => fake()->url(),
            'outbound_url' => fake()->url(),
            'pricing_model' => fake()->randomElement(ToolPricingModel::cases()),
            'has_free_plan' => fake()->boolean(),
            'has_free_trial' => fake()->boolean(),
            'is_open_source' => fake()->boolean(),
            'categories' => [fake()->word()],
            'image_path' => null,
            'review_post_id' => null,
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function withReview(?Post $post = null) : self
    {
        return $this->state(fn () => [
            'review_post_id' => $post?->getKey() ?? Post::factory(),
        ]);
    }
}
