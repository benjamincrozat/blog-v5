<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Posts\ExportPostsToMarkdown;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Exports the current database posts into canonical Markdown source files.
 */
#[AsCommand(
    name: 'blog:export',
    description: 'Export posts from the local database to Markdown files.'
)]
class BlogExportCommand extends Command
{
    protected $signature = 'blog:export {--slug=* : Limit export to specific post slugs}';

    public function handle(ExportPostsToMarkdown $exportPostsToMarkdown) : int
    {
        $result = $exportPostsToMarkdown->handle(
            slugs: collect($this->option('slug'))->filter()->values()->all(),
        );

        $this->info("Exported {$result->exportedCount} posts to {$result->path}.");

        return self::SUCCESS;
    }
}
