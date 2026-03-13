<?php

namespace Database\Factories;

use App\Models\Metric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Metric>
 */
class MetricFactory extends Factory
{
    public function definition() : array
    {
        return [
            'key' => fake()->word(),
            'value' => fake()->numberBetween(1, 100),
        ];
    }
}
