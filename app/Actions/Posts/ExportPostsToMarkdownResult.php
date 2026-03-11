<?php

namespace App\Actions\Posts;

/**
 * Carries the counters returned by a post export run.
 */
class ExportPostsToMarkdownResult
{
    public function __construct(
        public readonly int $exportedCount,
        public readonly string $path,
    ) {}
}
