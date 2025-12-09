<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::create('job_listing_location', function (Blueprint $table) {
            $table->foreignId('job_listing_id')->constrained('job_listings')->cascadeOnDelete();
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete();
            $table->unique(['job_listing_id', 'location_id']);
        });

        Schema::table('job_listings', function (Blueprint $table) {
            if (Schema::hasColumn('job_listings', 'location_id')) {
                $table->dropConstrainedForeignId('location_id');
            }

            if (Schema::hasColumn('job_listings', 'locations')) {
                $table->dropColumn('locations');
            }
        });
    }

    public function down() : void
    {
        Schema::dropIfExists('job_listing_location');

        Schema::table('job_listings', function (Blueprint $table) {
            $table
                ->foreignId('location_id')
                ->nullable()
                ->after('company_id')
                ->constrained('locations')
                ->nullOnDelete();

            $table->json('locations')->nullable();
        });
    }
};
