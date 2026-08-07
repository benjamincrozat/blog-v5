<?php

use Illuminate\Support\Facades\Schema;

it('uses the current Laravel queue and cache column shapes', function () {
    expect(Schema::getColumnType('cache', 'expiration', true))->toBe('bigint')
        ->and(Schema::hasIndex('cache', ['expiration']))->toBeTrue()
        ->and(Schema::getColumnType('cache_locks', 'expiration', true))->toBe('bigint')
        ->and(Schema::hasIndex('cache_locks', ['expiration']))->toBeTrue()
        ->and(Schema::getColumnType('jobs', 'attempts', true))->toBe('smallint unsigned')
        ->and(Schema::getColumnType('failed_jobs', 'connection', true))->toBe('varchar(255)')
        ->and(Schema::getColumnType('failed_jobs', 'queue', true))->toBe('varchar(255)')
        ->and(Schema::hasIndex('failed_jobs', ['connection', 'queue', 'failed_at']))->toBeTrue();
});
