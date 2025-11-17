<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            if (Schema::hasColumn('job_listings', 'how_to_apply')) {
                $table->dropColumn('how_to_apply');
            }
        });
    }

    public function down() : void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            if (! Schema::hasColumn('job_listings', 'how_to_apply')) {
                $table->json('how_to_apply')->nullable();
            }
        });
    }
};
