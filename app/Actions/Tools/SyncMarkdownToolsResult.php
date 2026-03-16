<?php

namespace App\Actions\Tools;

/**
 * Carries counters for Markdown tool synchronization runs.
 */
class SyncMarkdownToolsResult
{
    public function __construct(
        public readonly int $createdCount,
        public readonly int $updatedCount,
        public readonly int $deletedCount,
    ) {}
}
