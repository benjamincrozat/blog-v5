<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Removes fields that only supported the deleted public author pages.
 *
 * Rolling back recreates empty nullable columns; removed profile data cannot be restored.
 */
return new class extends Migration
{
    public function up() : void
    {
        $columns = array_values(array_filter(
            ['slug', 'biography'],
            fn (string $column) => Schema::hasColumn('users', $column),
        ));

        if ([] === $columns) {
            return;
        }

        Schema::table('users', function (Blueprint $table) use ($columns) : void {
            $table->dropColumn($columns);
        });
    }

    public function down() : void
    {
        Schema::table('users', function (Blueprint $table) : void {
            $table->string('slug')->nullable()->unique();
            $table->text('biography')->nullable();
        });
    }
};
