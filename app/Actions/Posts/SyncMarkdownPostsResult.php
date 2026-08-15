<?php

namespace App\Actions\Posts;

/**
 * Holds the result counts from one Markdown post sync.
 *
 * Its read-only values separate created, updated, restored, and soft-deleted
 * posts. Commands can show the exact result without querying the database again
 * or knowing how the sync works.
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
