<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Posts\SyncMarkdownPosts;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Syncs canonical Markdown post files into the database read model.
 */
#[AsCommand(
    name: 'blog:sync',
    description: 'Sync Markdown posts into the database.'
)]
class BlogSyncCommand extends Command
{
    public function handle(SyncMarkdownPosts $syncMarkdownPosts) : int
    {
        $result = $syncMarkdownPosts->handle();

        $this->info(
            "Synced posts: created={$result->createdCount}, updated={$result->updatedCount}, restored={$result->restoredCount}, deleted={$result->deletedCount}."
        );

        return self::SUCCESS;
    }
}
