<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    public function definition() : array
    {
        return [
            'name' => ucfirst(fake()->word()),
            'slug' => fake()->slug(),
        ];
    }
}
