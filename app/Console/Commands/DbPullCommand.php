<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Pulls a remote database snapshot into a local connection.
 *
 * Extracted to keep snapshot orchestration and safety checks in one place.
 * Callers can rely on compatible client handling before snapshot operations.
 */
#[AsCommand(
    name: 'app:db:pull',
    description: 'Dump a remote database connection and restore it into the local database.'
)]
class DbPullCommand extends Command
{
    public function handle() : int
    {
        $from = (string) $this->option('from') ?: 'production';
        $to = (string) $this->option('to') ?: config('database.default');
        $dryRun = (bool) ($this->option('dry-run') ?? false);

        // Prefer a compatible MySQL client when running on macOS with MySQL 9.x in PATH.
        $this->ensureCompatibleMysqlClient();

        $this->info("Preparing to pull database from \"$from\" into \"$to\".");

        $fromConfig = config("database.connections.$from");
        $toConfig = config("database.connections.$to");

        if (! is_array($fromConfig) || ! is_array($toConfig)) {
            $this->error('Invalid from/to connections.');

            return self::INVALID;
        }

        if (($fromConfig['driver'] ?? null) !== ($toConfig['driver'] ?? null)) {
            $this->error('Source and target database drivers must match.');

            return self::INVALID;
        }

        // Ensure snapshots directory exists.
        $snapshotsPath = config('filesystems.disks.snapshots.root');
        if ($snapshotsPath && ! File::exists($snapshotsPath)) {
            File::makeDirectory($snapshotsPath, 0755, true);
        }

        // Create a timestamped name for the snapshot for traceability.
        $snapshotName = $from . '_' . now()->format('Y-m-d_H-i-s');

        // Create snapshot from source connection.
        $this->info("Creating snapshot '$snapshotName' from '$from'…");
        if (! $dryRun) {
            Artisan::call('snapshot:create', [
                '--connection' => $from,
                'name' => $snapshotName,
            ]);
        } else {
            $this->line("Dry run: snapshot:create --connection=$from name=$snapshotName");
        }

        // Load snapshot into target connection (usually local default).
        $this->info("Loading snapshot '$snapshotName' into '$to'…");
        if (! $dryRun) {
            Artisan::call('snapshot:load', [
                'name' => $snapshotName,
                '--connection' => $to,
                '--drop-tables' => 1,
            ]);
        } else {
            $this->line("Dry run: snapshot:load name=$snapshotName --connection=$to --drop-tables=1");
        }

        $this->info('Database successfully pulled.');

        return self::SUCCESS;
    }

    protected function ensureCompatibleMysqlClient() : void
    {
        if (! $this->isDarwin()) {
            return;
        }

        $versionOutput = $this->mysqldumpVersionOutput();

        if (str_contains($versionOutput, 'Ver 9')) {
            $candidatePaths = [
                '/opt/homebrew/opt/mysql-client@8.4/bin',
                '/usr/local/opt/mysql-client@8.4/bin',
            ];

            foreach ($candidatePaths as $binPath) {
                if ($this->mysqldumpExistsAt($binPath)) {
                    $currentPath = (string) getenv('PATH');
                    $newPath = $binPath . PATH_SEPARATOR . $currentPath;
                    putenv('PATH=' . $newPath);
                    $_SERVER['PATH'] = $newPath;

                    $this->info("Using mysqldump from $binPath.");

                    return;
                }
            }

            $this->warn('mysqldump 9.x detected and mysql-client@8.4 not found. Install it with: brew install mysql-client@8.4');
        }
    }

    protected function isDarwin() : bool
    {
        return PHP_OS_FAMILY === 'Darwin';
    }

    protected function mysqldumpVersionOutput() : string
    {
        $output = [];
        $exitCode = 0;

        exec('mysqldump --version 2>&1', $output, $exitCode);

        return trim(implode("\n", $output));
    }

    protected function mysqldumpExistsAt(string $binPath) : bool
    {
        return is_file($binPath . '/mysqldump');
    }

    protected function configure() : void
    {
        $this->addOption('from');
        $this->addOption('to');
        $this->addOption('dry-run');
    }
}
