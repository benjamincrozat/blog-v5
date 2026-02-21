<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Defines the CategorySeeder implementation.
 */
class CategorySeeder extends Seeder
{
    public function run() : void
    {
        Category::factory(30)->create();
    }
}
