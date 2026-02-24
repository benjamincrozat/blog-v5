<?php

namespace App\Markdown;

use Illuminate\Support\Str;
use League\CommonMark\Node\Node;
use App\Markdown\Extensions\CustomRenderersExtension;
use League\CommonMark\Node\Inline\AbstractStringContainer;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;

/**
 * Renders rich markdown content for published pages.
 */
class MarkdownRenderer
{
    public static function parse(string $string) : string
    {
        return Str::markdown(
            string: $string,
            options: [
                'default_attributes' => [
                    Heading::class => [
                        'id' => fn (Heading $heading) => Str::slug(
                            static::childrenToText($heading)
                        ),
                    ],
                ],
                'disallowed_raw_html' => [
                    'disallowed_tags' => ['noembed', 'noframes', 'plaintext', 'script', 'style', 'textarea', 'title', 'xmp'],
                ],
                'external_link' => [
                    'internal_hosts' => [
                        preg_replace('/https?:\/\//', '', config('app.url')),
                    ],
                    'open_in_new_window' => true,
                ],
            ],
            extensions: [
                new DefaultAttributesExtension,
                new ExternalLinkExtension,
                new SmartPunctExtension,
                new CustomRenderersExtension,
            ]
        );
    }

    /**
     * Walks heading nodes recursively to build stable anchor text.
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
