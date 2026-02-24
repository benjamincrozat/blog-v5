<?php

namespace App\Markdown;

/**
 * Provides backward compatibility for the legacy markdown facade alias.
 */
class Markdown
{
    public static function parse(string $string) : string
    {
        return MarkdownRenderer::parse($string);
    }
}
