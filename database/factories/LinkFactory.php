<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    public function definition() : array
    {
        return [
            'user_id' => User::factory(),
            'post_id' => Post::factory(),
            'url' => fake()->url(),
            'image_url' => 'https://picsum.photos/' . random_int(1024, 1280) . '/' . random_int(640, 720),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
        ];
    }

    public function approved() : static
    {
        return $this->state([
            'is_approved' => now(),
            'is_declined' => null,
        ]);
    }

    public function declined() : static
    {
        return $this->state([
            'is_declined' => now(),
            'is_approved' => null,
        ]);
    }
}
