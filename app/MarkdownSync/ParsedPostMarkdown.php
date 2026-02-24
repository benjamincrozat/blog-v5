<?php

namespace App\MarkdownSync;

use Carbon\CarbonImmutable;

/**
 * Represents a validated markdown post payload.
 */
class ParsedPostMarkdown
{
    /**
     * @param  array<int, string>  $categories
     */
    public function __construct(
        public readonly string $path,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $author,
        public readonly array $categories,
        public readonly string $content,
        public readonly ?string $description,
        public readonly ?string $serpTitle,
        public readonly ?string $serpDescription,
        public readonly ?string $canonicalUrl,
        public readonly ?CarbonImmutable $publishedAt,
        public readonly ?CarbonImmutable $modifiedAt,
        public readonly ?string $imagePath,
        public readonly ?string $imageDisk,
        public readonly bool $isCommercial,
        public readonly ?CarbonImmutable $sponsoredAt,
    ) {}
}
