<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories.Factory<\App\Models\Subscriber>
 */
class SubscriberFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition() : array
    {
        return [
            'email' => fake()->unique()->safeEmail(),
            'confirmation_token' => null,
            'confirmed_at' => null,
            'confirmation_sent_at' => null,
        ];
    }
}
