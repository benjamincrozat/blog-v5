<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        if (! Schema::hasTable('tools') || Schema::hasColumn('tools', 'image_disk')) {
            return;
        }

        Schema::table('tools', function (Blueprint $table) {
            $table->string('image_disk')->nullable();
        });
    }

    public function down() : void
    {
        if (! Schema::hasTable('tools') || ! Schema::hasColumn('tools', 'image_disk')) {
            return;
        }

        Schema::table('tools', function (Blueprint $table) {
            $table->dropColumn('image_disk');
        });
    }
};
