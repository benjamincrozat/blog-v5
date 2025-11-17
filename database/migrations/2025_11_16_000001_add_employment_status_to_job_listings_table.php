<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            if (! Schema::hasColumn('job_listings', 'employment_status')) {
                $table->string('employment_status')->nullable()->after('setting');
            }
        });
    }

    public function down() : void
    {
        Schema::table('job_listings', function (Blueprint $table) {
            if (Schema::hasColumn('job_listings', 'employment_status')) {
                $table->dropColumn('employment_status');
            }
        });
    }
};
