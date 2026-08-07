<?php

use App\Models\User;

use function Pest\Laravel\seed;

use Database\Seeders\UserSeeder;

it('seeds Benjamin with a three-paragraph homepage biography', function () {
    seed(UserSeeder::class);

    $biography = User::query()
        ->where('github_login', 'benjamincrozat')
        ->sole()
        ->biography;

    expect(preg_split('/\R{2,}/', trim($biography)))
        ->toHaveCount(3);
});
