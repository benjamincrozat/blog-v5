<?php

namespace Tests\Feature\App\Console\Commands;

use App\Console\Commands\DbPullCommand;

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
