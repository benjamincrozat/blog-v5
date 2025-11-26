<?php

use function Pest\Laravel\artisan;

use App\Console\Commands\DbPullCommand;

it('dumps from production and restores into local', function () {
    artisan(DbPullCommand::class, ['--dry-run' => true])
        ->expectsOutputToContain('Dry run: snapshot:create --connection=production')
        ->expectsOutputToContain('Dry run: snapshot:load')
        ->assertOk();
});
