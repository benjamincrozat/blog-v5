<?php

namespace App\Actions\SearchConsole;

use RuntimeException;
use Illuminate\Http\Client\Factory as HttpFactory;

/**
 * Submits the public sitemap URL to the Google Search Console API.
 */
class SubmitSitemapToSearchConsole
{
    public function __construct(
        protected HttpFactory $http,
        protected FetchSearchConsoleAccessToken $fetchSearchConsoleAccessToken,
    ) {}

    public function enabled() : bool
    {
        return (bool) config('services.search_console.enabled');
    }

    public function handle(?string $sitemapUrl = null) : void
    {
        if (! app()->isProduction()) {
            throw new RuntimeException('Search Console sitemap submission is only allowed in production.');
        }

        if (! $this->enabled()) {
            return;
        }

        $property = $this->property();
        $sitemapUrl ??= $this->sitemapUrl();

        $this->http
            ->withToken($this->fetchSearchConsoleAccessToken->handle()->accessToken)
            ->put($this->submissionUrl($property, $sitemapUrl))
            ->throw();
    }

    protected function property() : string
    {
        $property = (string) config('services.search_console.property');

        if ('' === $property) {
            throw new RuntimeException('Search Console is enabled, but no property was configured.');
        }

        return $property;
    }

    protected function sitemapUrl() : string
    {
        return (string) (config('services.search_console.sitemap_url') ?: rtrim((string) config('app.url'), '/') . '/sitemap.xml');
    }

    protected function submissionUrl(string $property, string $sitemapUrl) : string
    {
        return 'https://www.googleapis.com/webmasters/v3/sites/' . rawurlencode($property) . '/sitemaps/' . rawurlencode($sitemapUrl);
    }
}
