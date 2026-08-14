<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::dropIfExists('short_urls');
    }

    public function down() : void
    {
        Schema::create('short_urls', function (Blueprint $table) : void {
            $table->id();
            $table->string('code')->unique();
            $table->string('url');
            $table->timestamps();
        });
    }
};
