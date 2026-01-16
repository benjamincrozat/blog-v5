<?php

namespace App\Feed;

use DOMXPath;
use DOMDocument;
use Carbon\CarbonImmutable;

/**
 * Parses Atom and RSS feeds into FeedItem value objects.
 *
 * Extracted to keep feed parsing rules centralized and reusable across actions.
 * Callers can rely on invalid XML returning an empty array.
 */
class FeedReader
{
    /**
     * @return array<FeedItem>
     */
    public function read(string $xml, string $baseUrl) : array
    {
        $document = new DOMDocument('1.0', 'UTF-8');
        $document->preserveWhiteSpace = false;
        $previous = libxml_use_internal_errors(true);
        libxml_clear_errors();
        $loaded = $document->loadXML($xml, LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        if (! $loaded) {
            return [];
        }

        $xpath = new DOMXPath($document);
        $xpath->registerNamespace('atom', 'http://www.w3.org/2005/Atom');
        $xpath->registerNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
        $xpath->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');

        $items = [];

        // Try Atom first.
        if (($entries = $xpath->query('//atom:entry')) && $entries->length > 0) {
            foreach ($entries as $entry) {
                $titleNode = $xpath->query('./atom:title', $entry)?->item(0);
                $title = trim(html_entity_decode($titleNode?->textContent ?? ''));

                $url = $this->firstAttr($xpath, './atom:link[@rel="alternate" and (@type="text/html" or not(@type))]/@href', $entry)
                    ?? $this->firstAttr($xpath, './atom:link/@href', $entry)
                    ?? trim($xpath->query('./atom:id', $entry)?->item(0)?->textContent ?? '');

                $published = trim($xpath->query('./atom:published', $entry)?->item(0)?->textContent ?? '')
                    ?: trim($xpath->query('./atom:updated', $entry)?->item(0)?->textContent ?? '');

                $url = $this->normalizeUrl($url, $baseUrl);
                $publishedAt = $this->parseDate($published);

                if (null !== $url && '' !== $title) {
                    $items[] = new FeedItem($url, $publishedAt, $title);
                }
            }

            return $items;
        }

        // Fallback to RSS 2.0.
        if ($rssItems = $xpath->query('//channel/item')) {
            foreach ($rssItems as $rssItemNode) {
                $titleNode = $xpath->query('./title', $rssItemNode)?->item(0);
                $title = trim(html_entity_decode($titleNode?->textContent ?? ''));

                $link = trim($xpath->query('./link', $rssItemNode)?->item(0)?->textContent ?? '');

                if ('' === $link) {
                    $guid = $xpath->query('./guid[not(@isPermaLink="false")]', $rssItemNode)?->item(0)?->textContent ?? '';
                    $link = trim($guid);
                }

                if ('' === $link) {
                    // Last resort: try to extract first anchor href from content:encoded CDATA.
                    $contentNode = $xpath->query('./content:encoded', $rssItemNode)?->item(0);

                    if ($contentNode) {
                        $contentHtml = (string) $contentNode->textContent;

                        if (preg_match('/href\s*=\s*\"([^\"]+)/i', $contentHtml, $match) ||
                            preg_match("/href\s*=\s*\'([^\']+)/i", $contentHtml, $match)
                        ) {
                            $link = trim($match[1] ?? '');
                        }
                    }
                }

                $publishedDateRaw = trim($xpath->query('./pubDate', $rssItemNode)?->item(0)?->textContent ?? '')
                    ?: trim($xpath->query('./dc:date', $rssItemNode)?->item(0)?->textContent ?? '');

                $url = $this->normalizeUrl($link, $baseUrl);
                $publishedAt = $this->parseDate($publishedDateRaw);

                if (null !== $url && '' !== $title) {
                    $items[] = new FeedItem($url, $publishedAt, $title);
                }
            }
        }

        return $items;
    }

    protected function firstAttr(DOMXPath $xpath, string $query, \DOMNode $contextNode) : ?string
    {
        $nodes = $xpath->query($query, $contextNode);

        if (! $nodes || 0 === $nodes->length) {
            return null;
        }

        $value = trim($nodes->item(0)?->nodeValue ?? '');

        return '' !== $value ? $value : null;
    }

    protected function parseDate(?string $value) : ?CarbonImmutable
    {
        $value = trim((string) ($value ?? ''));

        if ('' === $value) {
            return null;
        }

        try {
            return new CarbonImmutable($value);
        } catch (\Throwable) {
            try {
                return CarbonImmutable::parse($value);
            } catch (\Throwable) {
                return null;
            }
        }
    }

    protected function normalizeUrl(?string $url, string $baseUrl) : ?string
    {
        $url = trim((string) ($url ?? ''));
        if ('' === $url) {
            return null;
        }

        // Resolve relative URLs.
        if (! preg_match('~^https?://~i', $url)) {
            $url = $this->resolveRelativeUrl($url, $baseUrl);
        }

        // Strip fragment.
        $urlParts = parse_url($url);
        if (false === $urlParts) {
            return null;
        }
        unset($urlParts['fragment']);

        // Optionally strip common tracking params like utm_*.
        if (isset($urlParts['query'])) {
            parse_str($urlParts['query'], $queryParameters);
            $queryParameters = array_filter($queryParameters, fn ($parameterKey) => ! preg_match('/^utm_/i', (string) $parameterKey), ARRAY_FILTER_USE_KEY);
            $urlParts['query'] = http_build_query($queryParameters);
            if ('' === $urlParts['query']) {
                unset($urlParts['query']);
            }
        }

        $rebuilt = $this->buildUrl($urlParts);

        return '' !== $rebuilt ? $rebuilt : null;
    }

    protected function resolveRelativeUrl(string $relative, string $base) : string
    {
        // Very small resolver; good enough for typical feed links.
        $baseUrlParts = parse_url($base) ?: [];

        if (str_starts_with($relative, '//')) {
            return ($baseUrlParts['scheme'] ?? 'https') . ':' . $relative;
        }

        if (str_starts_with($relative, '/')) {
            return ($baseUrlParts['scheme'] ?? 'https') . '://' . ($baseUrlParts['host'] ?? '') . $relative;
        }

        $path = $baseUrlParts['path'] ?? '/';
        $path = preg_replace('~[^/]+$~', '', $path) ?: '/';

        return ($baseUrlParts['scheme'] ?? 'https') . '://' . ($baseUrlParts['host'] ?? '') . $path . $relative;
    }

    protected function buildUrl(array $parts) : string
    {
        $scheme = isset($parts['scheme']) ? $parts['scheme'] . '://' : '';
        $host = $parts['host'] ?? '';
        $port = isset($parts['port']) ? ':' . $parts['port'] : '';
        $user = $parts['user'] ?? '';
        $pass = isset($parts['pass']) ? ':' . $parts['pass'] : '';
        $pass = ($user || $pass) ? "$pass@" : '';
        $path = $parts['path'] ?? '';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        return "$scheme$user$pass$host$port$path$query";
    }
}
