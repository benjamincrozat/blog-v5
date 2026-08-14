<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<User>
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
                    'html_url' => fake()->url(),
                    'login' => fake()->userName(),
                ],
                'email' => $email,
            ],
            'email' => $email,
            'remember_token' => Str::random(10),
        ];
    }
}
