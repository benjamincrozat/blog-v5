<?php

namespace App\Markdown;

use Illuminate\Support\Str;
use League\CommonMark\Node\Node;
use App\Markdown\Extensions\CustomRenderersExtension;
use League\CommonMark\Node\Inline\AbstractStringContainer;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;

/**
 * Provides lightdown markdown behavior.
 */
class Lightdown
{
    public static function parse(string $string) : string
    {
        return Str::markdown(
            string: $string,
            options: [
                'disallowed_raw_html' => [
                    'disallowed_tags' => ['noembed', 'noframes', 'plaintext', 'script', 'style', 'textarea', 'title', 'xmp'],
                ],
                // Open external links in a new window.
                'external_link' => [
                    'internal_hosts' => [
                        preg_replace('/https?:\/\//', '', config('app.url')),
                    ],
                    'open_in_new_window' => true,
                ],
            ],
            extensions: [
                new ExternalLinkExtension,
                new CustomRenderersExtension,
            ]
        );
    }

    /**
     * This is a recursive method that will traverse the given
     * node and all of its children to get the text content.
     */
    protected static function childrenToText(Node $node) : string
    {
        return implode('', array_map(function (Node $child) {
            if ($child instanceof AbstractStringContainer) {
                return $child->getLiteral();
            }

            return static::childrenToText($child);
        }, iterator_to_array($node->children())));
    }
}
