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
 * Turns article and link Markdown into the site's full HTML format.
 *
 * It adds stable heading links, smart punctuation, highlighted code, and safe
 * behavior for outside links while blocking risky raw HTML. Posts, cards, feeds,
 * and table-of-contents labels share these rules so the same Markdown is not
 * rendered in several different ways.
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
