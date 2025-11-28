<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition() : array
    {
        $email = fake()->unique()->safeEmail();

        return [
            'name' => fake()->unique()->name(),
            'github_id' => fake()->unique()->randomNumber(),
            'github_login' => fake()->userName(),
            'avatar' => 'https://i.pravatar.cc/150?u=' . $email,
            'github_data' => [
                'id' => fake()->unique()->randomNumber(),
                'name' => fake()->name(),
                'user' => [
                    'bio' => fake()->paragraph(),
                    'blog' => fake()->url(),
                    'company' => fake()->company(),
                    'html_url' => fake()->url(),
                    'login' => fake()->userName(),
                ],
                'email' => $email,
            ],
            'email' => $email,
            'biography' => fake()->paragraph(),
            'remember_token' => Str::random(10),
        ];
    }
}
