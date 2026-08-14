<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Defines the UserSeeder implementation.
 */
class UserSeeder extends Seeder
{
    public function run() : void
    {
        User::factory()->create([
            'name' => 'Benjamin Crozat',
            'email' => 'benjamincrozat@me.com',
            'github_login' => 'benjamincrozat',
        ]);

        User::factory(10)->create();
    }
}
