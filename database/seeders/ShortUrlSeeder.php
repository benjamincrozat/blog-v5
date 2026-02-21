<?php

namespace Database\Seeders;

use App\Models\ShortUrl;
use Illuminate\Database\Seeder;

/**
 * Defines the ShortUrlSeeder implementation.
 */
class ShortUrlSeeder extends Seeder
{
    public function run() : void
    {
        ShortUrl::factory(10)->create();
    }
}
