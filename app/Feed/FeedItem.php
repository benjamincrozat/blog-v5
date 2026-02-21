<?php

namespace App\Feed;

use Carbon\CarbonImmutable;

/**
 * Defines the FeedItem implementation.
 */
class FeedItem
{
    public function __construct(
        public string $url,
        public ?CarbonImmutable $publishedAt,
        public string $title,
    ) {}
}
