<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->unique(['city', 'region', 'country']);
            $table->timestamps();
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('locations');
    }
};
