<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'name' => fake()->unique()->company(),
            'url' => fake()->url(),
            'logo' => fake()->imageUrl(),
            'about' => fake()->paragraphs(random_int(1, 3), true),
        ];
    }
}
