<?php

namespace App\Actions;

use DOMXPath;
use DOMDocument;
use App\Feed\FeedItem;
use App\Feed\FeedReader;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class DiscoverFeedItems
{
    public function __construct(
        protected FeedReader $reader,
    ) {}

    /**
     * @return Collection<FeedItem>
     */
    public function discover(string $feedUrl) : Collection
    {
        $response = Http::withHeaders([
            'Accept' => 'application/rss+xml, application/atom+xml, application/xml;q=0.9, text/xml;q=0.8, */*;q=0.5',
            'User-Agent' => 'benjamincrozat.com feed discovery bot',
        ])
            ->timeout(10)
            ->get($feedUrl);

        if (! $response->successful()) {
            return collect();
        }

        $xml = (string) $response->body();

        // Prefer self link as base URL when available.
        $baseUrl = $this->detectBaseUrl($xml, $feedUrl);

        $items = $this->reader->read($xml, $baseUrl);

        return collect($items);
    }

    protected function detectBaseUrl(string $xml, string $fallback) : string
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        @$document->loadXML($xml, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_NONET);

        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('atom', 'http://www.w3.org/2005/Atom');

        $selfHref = $xpath->query('//atom:link[@rel="self"]/@href')?->item(0)?->nodeValue ?? '';

        return '' !== $selfHref ? $selfHref : $fallback;
    }
}
