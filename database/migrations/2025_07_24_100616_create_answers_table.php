<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('questions');
            $table->string('answer');
            $table->boolean('is_correct');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('answers');
    }
};
