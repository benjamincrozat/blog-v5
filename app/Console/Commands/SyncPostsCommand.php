<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Posts\SyncMarkdownPosts;
use App\Exceptions\PostMarkdownException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Synchronizes markdown post files into the posts database read model.
 */
#[AsCommand(
    name: 'app:sync-posts',
    description: 'Sync markdown source posts into the database'
)]
class SyncPostsCommand extends Command
{
    public function handle(SyncMarkdownPosts $syncMarkdownPosts) : int
    {
        $directory = $this->resolveDirectory();

        try {
            $result = $syncMarkdownPosts->handle($directory);
        } catch (PostMarkdownException $exception) {
            foreach (explode("\n", $exception->getMessage()) as $line) {
                $this->error($line);
            }

            return self::FAILURE;
        }

        $this->info(
            "Synced posts: created={$result->createdCount}, updated={$result->updatedCount}, restored={$result->restoredCount}, deleted={$result->deletedCount}."
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
