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

    public function hasChanges() : bool
    {
        return $this->createdCount > 0 ||
            $this->updatedCount > 0 ||
            $this->restoredCount > 0 ||
            $this->deletedCount > 0;
    }
}
