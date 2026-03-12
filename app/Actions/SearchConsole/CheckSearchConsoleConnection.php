<?php

namespace App\Actions\SearchConsole;

use Illuminate\Http\Client\Factory as HttpFactory;

/**
 * Probes the configured Google endpoints without submitting a sitemap.
 */
class CheckSearchConsoleConnection
{
    public function __construct(
        protected HttpFactory $http,
    ) {}

    /**
     * @return array<int, array{label: string, status: int, url: string}>
     */
    public function handle(?string $sitemapUrl = null) : array
    {
        return [
            $this->probe('Token endpoint', $this->tokenUri()),
            $this->probe('Search Console endpoint', $this->submissionUrl($sitemapUrl)),
        ];
    }

    /**
     * @return array{label: string, status: int, url: string}
     */
    protected function probe(string $label, string $url) : array
    {
        $response = $this->http
            ->connectTimeout(5)
            ->timeout(5)
            ->withOptions(['allow_redirects' => false])
            ->head($url);

        return [
            'label' => $label,
            'status' => $response->status(),
            'url' => $url,
        ];
    }

    protected function submissionUrl(?string $sitemapUrl = null) : string
    {
        $property = (string) config('services.search_console.property');

        if (blank($property)) {
            return 'https://www.googleapis.com/webmasters/v3/sites';
        }

        $sitemapUrl ??= (string) (config('services.search_console.sitemap_url') ?: rtrim((string) config('app.url'), '/') . '/sitemap.xml');

        return 'https://www.googleapis.com/webmasters/v3/sites/' . rawurlencode($property) . '/sitemaps/' . rawurlencode($sitemapUrl);
    }

    protected function tokenUri() : string
    {
        return (string) config('services.search_console.token_uri');
    }
}
