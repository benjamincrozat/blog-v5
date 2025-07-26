<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    public function run() : void
    {
        Question::factory(50)
            ->recycle(Quiz::all())
            ->create();
    }
}
