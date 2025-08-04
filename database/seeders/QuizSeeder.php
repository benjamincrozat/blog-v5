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
        Quiz::factory(50)
            ->recycle(Post::all())
            ->create()
            ->each(function (Quiz $quiz) {
                $quiz->questions()->saveMany(
                    Question::factory(random_int(5, 10))
                        ->hasAnswers(5)
                        ->create()
                );
            });
    }
}
