<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Posts\SyncMarkdownPosts;
use App\Actions\Sitemaps\GenerateSitemap;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Actions\SearchConsole\SubmitSitemapToSearchConsole;

/**
 * Syncs canonical Markdown post files into the database read model.
 */
#[AsCommand(
    name: 'blog:sync',
    description: 'Sync Markdown posts into the database.'
)]
class BlogSyncCommand extends Command
{
    public function handle(
        SyncMarkdownPosts $syncMarkdownPosts,
        GenerateSitemap $generateSitemap,
        SubmitSitemapToSearchConsole $submitSitemapToSearchConsole,
    ) : int {
        $result = $syncMarkdownPosts->handle();

        $this->info(
            "Synced posts: created={$result->createdCount}, updated={$result->updatedCount}, restored={$result->restoredCount}, deleted={$result->deletedCount}."
        );

        if (! $result->hasChanges()) {
            $this->info('Search Console submission skipped because no content changes were detected.');

            return self::SUCCESS;
        }

        $path = $generateSitemap->handle();

        $this->info("Sitemap generated successfully at {$path}");

        if (! config('services.search_console.submit_on_sync')) {
            $this->info('Search Console submission skipped during blog sync because it is disabled for sync runs.');

            return self::SUCCESS;
        }

        if (! $submitSitemapToSearchConsole->enabled()) {
            $this->info('Search Console submission skipped because it is disabled.');

            return self::SUCCESS;
        }

        $submitSitemapToSearchConsole->handle();

        $this->info('Sitemap submitted to Google Search Console.');

        return self::SUCCESS;
    }
}
