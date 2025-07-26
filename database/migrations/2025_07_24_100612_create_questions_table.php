<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id');
            $table->string('question');
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('questions');
    }
};
