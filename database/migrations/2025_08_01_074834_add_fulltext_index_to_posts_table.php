<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        if ('sqlite' === Schema::getConnection()->getDriverName()) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->fullText('title');
            $table->fullText('slug');
        });
    }

    public function down() : void
    {
        if ('sqlite' === Schema::getConnection()->getDriverName()) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropFullText('title');
            $table->dropFullText('slug');
        });
    }
};
