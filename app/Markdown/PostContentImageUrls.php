<?php

namespace App\Markdown;

use App\Exceptions\PostMarkdownException;
use League\CommonMark\Node\NodeWalkerEvent;
use League\CommonMark\Parser\MarkdownParser;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Block\HtmlBlock;
use League\CommonMark\Extension\CommonMark\Node\Inline\HtmlInline;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;

/**
 * Finds and validates rendered post-content image URLs.
 */
class PostContentImageUrls
{
    /**
     * @return array<int, string>
     */
    public static function nonCloudflare(string $markdown) : array
    {
        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);

        $document = (new MarkdownParser($environment))->parse($markdown);
        $urls = [];

        $walker = $document->walker();

        while ($event = $walker->next()) {
            if (! $event instanceof NodeWalkerEvent || ! $event->isEntering()) {
                continue;
            }

            $node = $event->getNode();

            if ($node instanceof Image) {
                $url = trim($node->getUrl());

                if ('' !== $url && ! static::isCloudflare($url)) {
                    $urls[] = $url;
                }

                continue;
            }

            if ($node instanceof HtmlInline || $node instanceof HtmlBlock) {
                foreach (static::extractHtmlImageUrls($node->getLiteral()) as $url) {
                    if (! static::isCloudflare($url)) {
                        $urls[] = $url;
                    }
                }
            }
        }

        return array_values(array_unique($urls));
    }

    public static function ensureCloudflare(string $markdown, string $relativePath) : void
    {
        $urls = static::nonCloudflare($markdown);

        if ([] === $urls) {
            return;
        }

        $preview = implode(', ', array_slice($urls, 0, 3));
        $remaining = count($urls) - min(count($urls), 3);

        if ($remaining > 0) {
            $preview .= " (+{$remaining} more)";
        }

        throw PostMarkdownException::forPath(
            $relativePath,
            "Post content images must use Cloudflare Images URLs. Found: {$preview}."
        );
    }

    public static function isCloudflare(string $url) : bool
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        return 'imagedelivery.net' === parse_url($url, PHP_URL_HOST);
    }

    /**
     * @return array<int, string>
     */
    protected static function extractHtmlImageUrls(string $html) : array
    {
        preg_match_all(
            '/<img\b[^>]*\bsrc\s*=\s*(?:"(?<double>[^"]+)"|\'(?<single>[^\']+)\'|(?<bare>[^\s>]+))/i',
            $html,
            $matches,
            PREG_SET_ORDER,
        );

        $urls = [];

        foreach ($matches as $match) {
            $url = $match['double'] ?: $match['single'] ?: $match['bare'] ?: null;

            if (null !== $url) {
                $urls[] = trim($url);
            }
        }

        return $urls;
    }
}
