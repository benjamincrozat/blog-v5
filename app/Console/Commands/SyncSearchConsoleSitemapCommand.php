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
            $results = $checkSearchConsoleConnection->handle();

            $this->table(
                ['Probe', 'HTTP', 'Meaning', 'URL'],
                array_map(fn (array $result) : array => [
                    $result['label'],
                    (string) $result['status'],
                    $this->probeMeaning($result['label']),
                    $result['url'],
                ], $results),
            );

            $this->info('Non-production mode only checks that Google responds on the configured network path.');
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

    protected function probeMeaning(string $label) : string
    {
        return match ($label) {
            'Token endpoint' => 'OAuth endpoint responded; no token exchange was attempted.',
            'Search Console endpoint' => 'Search Console endpoint responded; no sitemap was submitted.',
            default => 'Google responded on the configured network path.',
        };
    }
}
