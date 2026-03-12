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

            $this->line($this->nonProductionSummary($results));

            foreach ($this->nonProductionFixes($results) as $fix) {
                $this->warn($fix);
            }

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

    /**
     * @param  array<int, array{check: string, result: string, details: string, reference: string}>  $results
     */
    protected function nonProductionSummary(array $results) : string
    {
        $credentials = $this->resultFor($results, 'Credentials');
        $propertyAccess = $this->resultFor($results, 'Property access');

        if ('OK' === ($credentials['result'] ?? null) && ! str_starts_with((string) ($propertyAccess['result'] ?? ''), 'HTTP ')) {
            return 'Your credentials work, this property is accessible, and this environment is only skipping the final sitemap submission because it is not production.';
        }

        if ('Failed' === ($credentials['result'] ?? null)) {
            return 'Google is reachable, but the credentials in your .env file did not work.';
        }

        if ('Skipped' === ($credentials['result'] ?? null)) {
            return 'Google is reachable, but this command could not verify your credentials yet.';
        }

        if (str_starts_with((string) ($propertyAccess['result'] ?? ''), 'HTTP ')) {
            return 'Your credentials work, but they cannot access the Search Console property you configured.';
        }

        return 'Google responded, and this environment is only doing read-only checks.';
    }

    /**
     * @param  array<int, array{check: string, result: string, details: string, reference: string}>  $results
     * @return array<int, string>
     */
    protected function nonProductionFixes(array $results) : array
    {
        $credentials = $this->resultFor($results, 'Credentials');
        $propertyAccess = $this->resultFor($results, 'Property access');
        $fixes = [];

        if ('Skipped' === ($credentials['result'] ?? null)) {
            $fixes[] = 'Fix: set SEARCH_CONSOLE_ENABLED=true in .env to let this command verify your credentials locally.';
        }

        if ('Failed' === ($credentials['result'] ?? null)) {
            $fixes[] = 'Fix: check your Search Console values in .env. For OAuth, verify the client ID, client secret, and refresh token. For a service account, verify the email and private key.';
        }

        if ('Skipped' === ($propertyAccess['result'] ?? null) && 'SEARCH_CONSOLE_PROPERTY' === ($propertyAccess['reference'] ?? null)) {
            $fixes[] = 'Fix: set SEARCH_CONSOLE_PROPERTY to your real Search Console property, for example sc-domain:example.com.';
        }

        if (str_starts_with((string) ($propertyAccess['result'] ?? ''), 'HTTP ')) {
            $fixes[] = 'Fix: check SEARCH_CONSOLE_PROPERTY and make sure this Google account or service account has access to that property in Search Console.';
        }

        if ([] === $fixes && 'OK' === ($credentials['result'] ?? null)) {
            $fixes[] = 'Next step: run this command in production when you want to submit the sitemap for real.';
        }

        return $fixes;
    }

    /**
     * @param  array<int, array{check: string, result: string, details: string, reference: string}>  $results
     * @return array{check: string, result: string, details: string, reference: string}|null
     */
    protected function resultFor(array $results, string $check) : ?array
    {
        foreach ($results as $result) {
            if ($check === $result['check']) {
                return $result;
            }
        }

        return null;
    }
}
