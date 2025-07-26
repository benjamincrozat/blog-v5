<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run() : void
    {
        Quiz::factory(10)
            ->recycle(Post::all())
            ->create();
    }
}
