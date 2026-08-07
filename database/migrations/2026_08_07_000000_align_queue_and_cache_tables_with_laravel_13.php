<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up() : void
    {
        if ('bigint' !== Schema::getColumnType('cache', 'expiration', true)) {
            Schema::table('cache', function (Blueprint $table) {
                $table->bigInteger('expiration')->change();
            });
        }

        if (! Schema::hasIndex('cache', ['expiration'])) {
            Schema::table('cache', function (Blueprint $table) {
                $table->index('expiration');
            });
        }

        if ('bigint' !== Schema::getColumnType('cache_locks', 'expiration', true)) {
            Schema::table('cache_locks', function (Blueprint $table) {
                $table->bigInteger('expiration')->change();
            });
        }

        if (! Schema::hasIndex('cache_locks', ['expiration'])) {
            Schema::table('cache_locks', function (Blueprint $table) {
                $table->index('expiration');
            });
        }

        if ('smallint unsigned' !== Schema::getColumnType('jobs', 'attempts', true)) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->unsignedSmallInteger('attempts')->change();
            });
        }

        if ('varchar' !== Schema::getColumnType('failed_jobs', 'connection', true)) {
            Schema::table('failed_jobs', function (Blueprint $table) {
                $table->string('connection')->change();
                $table->string('queue')->change();
            });
        }

        if (! Schema::hasIndex('failed_jobs', ['connection', 'queue', 'failed_at'])) {
            Schema::table('failed_jobs', function (Blueprint $table) {
                $table->index(['connection', 'queue', 'failed_at']);
            });
        }
    }

    public function down() : void
    {
        if (Schema::hasIndex('failed_jobs', ['connection', 'queue', 'failed_at'])) {
            Schema::table('failed_jobs', function (Blueprint $table) {
                $table->dropIndex(['connection', 'queue', 'failed_at']);
            });
        }

        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->text('connection')->change();
            $table->text('queue')->change();
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedTinyInteger('attempts')->change();
        });

        foreach (['cache', 'cache_locks'] as $tableName) {
            if (Schema::hasIndex($tableName, ['expiration'])) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropIndex(['expiration']);
                });
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->integer('expiration')->change();
            });
        }
    }
};
