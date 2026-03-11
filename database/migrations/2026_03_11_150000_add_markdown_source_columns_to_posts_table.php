<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->char('source_uuid', 26)->nullable()->unique()->after('slug');
            $table->string('source_path')->nullable()->after('source_uuid');
            $table->string('source_hash', 64)->nullable()->after('source_path');
        });
    }

    public function down() : void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropUnique(['source_uuid']);
            $table->dropColumn(['source_uuid', 'source_path', 'source_hash']);
        });
    }
};
