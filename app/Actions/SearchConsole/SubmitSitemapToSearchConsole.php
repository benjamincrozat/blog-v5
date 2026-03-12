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

    public function configured() : bool
    {
        return $this->fetchSearchConsoleAccessToken->hasCredentials() &&
            filled(config('services.search_console.property'));
    }

    public function handle(?string $sitemapUrl = null) : void
    {
        if (! app()->isProduction()) {
            throw new RuntimeException('Search Console sitemap submission is only allowed in production.');
        }

        if (! $this->configured()) {
            return;
        }

        $property = $this->property();
        $sitemapUrl ??= $this->sitemapUrl();

        $response = $this->http
            ->withToken($this->fetchSearchConsoleAccessToken->handle()->accessToken)
            ->send('PUT', $this->submissionUrl($property, $sitemapUrl));

        if ($response->failed()) {
            $message = "Google Search Console sitemap submission failed with HTTP {$response->status()}.";
            $body = trim($response->body());

            if ('' !== $body) {
                $message .= PHP_EOL . $body;
            }

            throw new RuntimeException($message);
        }
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
