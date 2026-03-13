<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    public function definition() : array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'content' => fake()->paragraphs(random_int(3, 10), true),
            'serp_title' => fake()->sentence(),
            'serp_description' => fake()->sentences(random_int(1, 2), true),
            'description' => fake()->sentences(random_int(1, 2), true),
            'canonical_url' => fake()->url(),
            'is_commercial' => fake()->boolean(),
            'published_at' => fake()->dateTimeBetween('-1 year', 'now'),
            'sessions_count' => fake()->numberBetween(0, 1000),
        ];
    }

    public function configure() : self
    {
        return $this->afterCreating(function (Post $post) {
            $post->categories()->attach(
                Category::factory(random_int(1, 3))->create()
            );
        });
    }
}
