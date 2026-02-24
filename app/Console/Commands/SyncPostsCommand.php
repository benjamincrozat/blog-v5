<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MarkdownSync\SyncPosts;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * Synchronizes markdown post files into the posts database read model.
 */
#[AsCommand(
    name: 'app:sync-posts',
    description: 'Sync markdown source posts into the database'
)]
class SyncPostsCommand extends Command
{
    public function handle(SyncPosts $syncPosts) : int
    {
        $directory = (string) ($this->option('directory') ?: 'resources/markdown/posts');
        $result = $syncPosts->handle($directory);

        $this->line('Posts sync summary');
        $this->table(
            ['Scanned', 'Created', 'Updated', 'Soft deleted', 'Skipped', 'Errors'],
            [[
                $result->scanned,
                $result->created,
                $result->updated,
                $result->softDeleted,
                $result->skipped,
                count($result->errors),
            ]]
        );

        if ($result->hasErrors()) {
            foreach ($result->errors as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $this->info('Markdown posts have been synchronized.');

        return self::SUCCESS;
    }

    protected function configure() : void
    {
        $this->addOption(
            'directory',
            null,
            InputOption::VALUE_OPTIONAL,
            'The markdown directory to synchronize',
            'resources/markdown/posts',
        );
    }
}
