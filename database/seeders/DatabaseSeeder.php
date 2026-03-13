<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Defines the DatabaseSeeder implementation.
 */
class DatabaseSeeder extends Seeder
{
    public function run() : void
    {
        Storage::disk('public')->deleteDirectory('images/posts');

        cache()->flush();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            PostSeeder::class,
            CommentSeeder::class,
            LinkSeeder::class,
            ShortUrlSeeder::class,
        ]);
    }
}
