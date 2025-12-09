<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Drop existing unique indexes on normalized columns if they exist.
            $table->dropUnique(['normalized_domain']);
            $table->dropUnique(['normalized_url']);

            // Remove legacy raw url column.
            if (Schema::hasColumn('companies', 'url')) {
                $table->dropColumn('url');
            }

            // Rename normalized columns to canonical names.
            $table->renameColumn('normalized_domain', 'domain');
            $table->renameColumn('normalized_url', 'url');

            // Recreate uniqueness on the canonical columns.
            $table->unique(['domain']);
            $table->unique(['url']);
        });
    }

    public function down() : void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Roll back uniqueness on canonical columns.
            $table->dropUnique(['domain']);
            $table->dropUnique(['url']);

            // Rename canonical columns back to normalized names.
            $table->renameColumn('domain', 'normalized_domain');
            $table->renameColumn('url', 'normalized_url');

            // Restore legacy raw url column.
            $table->string('url')->nullable()->after('normalized_url');

            // Restore uniqueness on normalized columns.
            $table->unique(['normalized_domain']);
            $table->unique(['normalized_url']);
        });
    }
};
