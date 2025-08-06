<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run() : void
    {
        Quiz::factory(30)
            ->recycle(
                Post::query()
                    ->published()
                    ->whereDoesntHave('link')
                    ->inRandomOrder()
                    ->get()
            )
            ->has(
                Question::factory(random_int(3, 10))
                    ->hasAnswers(random_int(2, 5))
            )
            ->create();
    }
}
