<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['is_highlighted', 'extra_attributes']);
        });
    }

    public function down() : void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('is_highlighted')->default(false);
            $table->text('extra_attributes')->nullable();
        });
    }
};
