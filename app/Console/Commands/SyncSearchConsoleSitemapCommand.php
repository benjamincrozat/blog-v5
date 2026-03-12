<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Sitemaps\GenerateSitemap;
use Symfony\Component\Console\Attribute\AsCommand;
use App\Actions\SearchConsole\CheckSearchConsoleConnection;
use App\Actions\SearchConsole\SubmitSitemapToSearchConsole;
use App\Actions\SearchConsole\VerifySearchConsoleCredentials;

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
        VerifySearchConsoleCredentials $verifySearchConsoleCredentials,
        SubmitSitemapToSearchConsole $submitSitemapToSearchConsole,
    ) : int {
        $path = $generateSitemap->handle();

        $this->info("Sitemap generated successfully at {$path}");

        if (! app()->isProduction()) {
            $results = [
                ...$checkSearchConsoleConnection->handle(),
                ...$verifySearchConsoleCredentials->handle(),
            ];

            $this->table(
                ['Check', 'Result', 'Details', 'Reference'],
                array_map(fn (array $result) : array => [
                    $result['check'],
                    $result['result'],
                    $result['details'],
                    $result['reference'],
                ], $results),
            );

            $this->info('Non-production mode does not submit sitemaps. It only checks connectivity and validates credentials read-only.');
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
