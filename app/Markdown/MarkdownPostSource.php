<?php

namespace App\Markdown;

/**
 * Represents a resolved file-managed post source.
 */
class MarkdownPostSource
{
    public function __construct(
        public readonly string $absolutePath,
        public readonly string $relativePath,
        public readonly PostMarkdownDocument $document,
    ) {}
}
