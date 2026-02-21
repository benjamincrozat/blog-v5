<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Report;
use Illuminate\Database\Seeder;

/**
 * Defines the ReportSeeder implementation.
 */
class ReportSeeder extends Seeder
{
    public function run() : void
    {
        Report::factory(10)
            ->recycle(Post::all())
            ->create();
    }
}
