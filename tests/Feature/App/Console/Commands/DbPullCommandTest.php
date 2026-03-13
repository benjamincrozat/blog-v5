<?php

use Tests\Feature\App\Console\Commands\TestableDbPullCommand;

$originalPath = null;

beforeEach(function () use (&$originalPath) {
    $originalPath = (string) getenv('PATH');
});

afterEach(function () use (&$originalPath) {
    if (null !== $originalPath) {
        putenv('PATH=' . $originalPath);
        $_SERVER['PATH'] = $originalPath;
    }
});

it('prepends a compatible mysql-client path when mysqldump is v9', function () {
    $command = new TestableDbPullCommand;
    $command->darwin = true;
    $command->versionOutput = 'mysqldump  Ver 9.0.0';
    $command->existingPaths = ['/opt/homebrew/opt/mysql-client@8.4/bin'];

    $command->runEnsureCompatibleMysqlClient();

    $updatedPath = (string) getenv('PATH');

    expect($updatedPath)
        ->toStartWith('/opt/homebrew/opt/mysql-client@8.4/bin' . PATH_SEPARATOR);
});

it('does not change PATH when mysqldump is not v9', function () use (&$originalPath) {
    $command = new TestableDbPullCommand;
    $command->darwin = true;
    $command->versionOutput = 'mysqldump  Ver 8.4.0';
    $command->existingPaths = ['/opt/homebrew/opt/mysql-client@8.4/bin'];

    $command->runEnsureCompatibleMysqlClient();

    expect((string) getenv('PATH'))->toBe($originalPath);
});
