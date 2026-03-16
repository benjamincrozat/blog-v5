<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Tools\SyncMarkdownTools;
use App\Exceptions\ToolMarkdownException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Synchronizes markdown tool files into the tools database read model.
 */
#[AsCommand(
    name: 'app:sync-tools',
    description: 'Sync markdown source tools into the database'
)]
class SyncToolsCommand extends Command
{
    public function handle(SyncMarkdownTools $syncMarkdownTools) : int
    {
        $directory = $this->resolveDirectory();

        try {
            $result = $syncMarkdownTools->handle($directory);
        } catch (ToolMarkdownException $exception) {
            foreach (explode("\n", $exception->getMessage()) as $line) {
                $this->error($line);
            }

            return self::FAILURE;
        }

        $this->info(
            "Synced tools: created={$result->createdCount}, updated={$result->updatedCount}, deleted={$result->deletedCount}."
        );

        return self::SUCCESS;
    }

    protected function resolveDirectory() : ?string
    {
        $directory = trim((string) $this->option('directory'));

        return '' === $directory ? null : $directory;
    }

    protected function configure() : void
    {
        $this->addOption(
            'directory',
            null,
            InputOption::VALUE_OPTIONAL,
            'The markdown directory to synchronize',
        );
    }
}
