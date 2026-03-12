<?php

namespace App\Actions\Posts;

/**
 * Carries the counters returned by a Markdown-to-post sync run.
 */
class SyncMarkdownPostsResult
{
    public function __construct(
        public readonly int $createdCount,
        public readonly int $updatedCount,
        public readonly int $restoredCount,
        public readonly int $deletedCount,
    ) {}
}
