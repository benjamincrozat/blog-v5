<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Sitemaps\GenerateSitemap;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Actions\SearchConsole\CheckSearchConsoleConnection;
use App\Actions\SearchConsole\SubmitSitemapToSearchConsole;

/**
 * Regenerates the sitemap and optionally submits it to Google Search Console.
 */
#[AsCommand(
    name: 'app:sync-search-console-sitemap',
    description: 'Regenerate the sitemap and submit it to Google Search Console.'
)]
class SyncSearchConsoleSitemapCommand extends Command
{
    public function handle(
        GenerateSitemap $generateSitemap,
        CheckSearchConsoleConnection $checkSearchConsoleConnection,
        SubmitSitemapToSearchConsole $submitSitemapToSearchConsole,
    ) : int {
        $path = $generateSitemap->handle();

        $this->info("Sitemap generated successfully at {$path}");

        if (! app()->isProduction()) {
            foreach ($checkSearchConsoleConnection->handle() as $result) {
                $this->info("{$result['label']} reachable ({$result['status']}) at {$result['url']}");
            }

            $this->info('Search Console submission skipped outside production.');

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
