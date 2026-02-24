<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        if (Schema::hasTable('posts') && Schema::hasColumn('posts', 'recommendations')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('recommendations');
            });
        }

        Schema::dropIfExists('revisions');
        Schema::dropIfExists('reports');
    }

    public function down() : void
    {
        if (Schema::hasTable('posts') && ! Schema::hasColumn('posts', 'recommendations')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->json('recommendations')->nullable();
            });
        }

        if (! Schema::hasTable('reports')) {
            Schema::create('reports', function (Blueprint $table) {
                $table->id();
                $table->foreignId('post_id');
                $table->longText('content');
                $table->dateTime('completed_at')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('revisions')) {
            Schema::create('revisions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('report_id');
                $table->json('data');
                $table->dateTime('completed_at')->nullable();
                $table->timestamps();
            });
        }
    }
};
