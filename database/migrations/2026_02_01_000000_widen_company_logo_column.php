<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        if (! Schema::hasTable('companies') || ! Schema::hasColumn('companies', 'logo')) {
            return;
        }

        $driver = DB::getDriverName();

        if ('mysql' === $driver) {
            DB::statement('ALTER TABLE `companies` MODIFY `logo` TEXT NULL');
        }

        if ('pgsql' === $driver) {
            DB::statement('ALTER TABLE companies ALTER COLUMN logo TYPE TEXT');
        }
    }

    public function down() : void
    {
        if (! Schema::hasTable('companies') || ! Schema::hasColumn('companies', 'logo')) {
            return;
        }

        $driver = DB::getDriverName();

        if ('mysql' === $driver) {
            DB::statement('UPDATE `companies` SET `logo` = LEFT(`logo`, 255) WHERE `logo` IS NOT NULL');
            DB::statement('ALTER TABLE `companies` MODIFY `logo` VARCHAR(255) NULL');
        }

        if ('pgsql' === $driver) {
            DB::statement('UPDATE companies SET logo = LEFT(logo, 255) WHERE logo IS NOT NULL');
            DB::statement('ALTER TABLE companies ALTER COLUMN logo TYPE VARCHAR(255)');
        }
    }
};
