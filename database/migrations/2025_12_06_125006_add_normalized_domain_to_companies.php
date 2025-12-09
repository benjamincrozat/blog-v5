<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('normalized_domain')->nullable()->after('url');
            $table->string('normalized_url')->nullable()->after('normalized_domain');
            $table->unique(['normalized_domain']);
            $table->unique(['normalized_url']);
        });
    }

    public function down() : void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropUnique(['normalized_domain']);
            $table->dropUnique(['normalized_url']);
            $table->dropColumn(['normalized_domain', 'normalized_url']);
        });
    }
};
