<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;

class AnswerSeeder extends Seeder
{
    public function run() : void
    {
        Answer::factory(100)
            ->recycle(Question::all())
            ->create();
    }
}
