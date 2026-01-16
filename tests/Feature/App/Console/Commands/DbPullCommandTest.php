<?php

use App\Console\Commands\DbPullCommand;

beforeEach(function () {
    $this->originalPath = (string) getenv('PATH');
});

afterEach(function () {
    if (isset($this->originalPath)) {
        putenv('PATH=' . $this->originalPath);
        $_SERVER['PATH'] = $this->originalPath;
    }
});

class TestableDbPullCommand extends DbPullCommand
{
    public bool $darwin = true;

    public string $versionOutput = '';

    public array $existingPaths = [];

    public array $messages = [];

    public function runEnsureCompatibleMysqlClient() : void
    {
        $this->ensureCompatibleMysqlClient();
    }

    public function info($string, $verbosity = null) : void
    {
        $this->messages[] = $string;
    }

    public function warn($string, $verbosity = null) : void
    {
        $this->messages[] = $string;
    }

    protected function isDarwin() : bool
    {
        return $this->darwin;
    }

    protected function mysqldumpVersionOutput() : string
    {
        return $this->versionOutput;
    }

    protected function mysqldumpExistsAt(string $binPath) : bool
    {
        return in_array($binPath, $this->existingPaths, true);
    }
}

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

it('does not change PATH when mysqldump is not v9', function () {
    $command = new TestableDbPullCommand;
    $command->darwin = true;
    $command->versionOutput = 'mysqldump  Ver 8.4.0';
    $command->existingPaths = ['/opt/homebrew/opt/mysql-client@8.4/bin'];

    $command->runEnsureCompatibleMysqlClient();

    expect((string) getenv('PATH'))->toBe($this->originalPath);
});
