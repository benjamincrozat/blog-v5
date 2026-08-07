<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

// This removes the crap I've done in the previous
// migrations and builds new indexes properly.
return new class extends Migration
{
    public function up() : void
    {
        if ('sqlite' === Schema::getConnection()->getDriverName()) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->fullText(
                ['title', 'slug', 'content', 'description'],
                'posts_fulltext_all'
            );
        });

        Schema::table('links', function (Blueprint $table) {
            $table->fullText(
                ['url', 'title', 'description'],
                'links_fulltext_all'
            );
        });
    }

    public function down() : void
    {
        if ('sqlite' === Schema::getConnection()->getDriverName()) {
            return;
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->dropFullText('posts_fulltext_all');
        });

        Schema::table('links', function (Blueprint $table) {
            $table->dropFullText('links_fulltext_all');
        });
    }
};
