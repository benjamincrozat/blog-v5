<?php

namespace App\MarkdownSync;

/**
 * Carries `app:sync-posts` counters and detailed error messages.
 */
class SyncPostsResult
{
    public int $scanned = 0;

    public int $created = 0;

    public int $updated = 0;

    public int $softDeleted = 0;

    public int $skipped = 0;

    /**
     * @var array<int, string>
     */
    public array $errors = [];

    public function hasErrors() : bool
    {
        return [] !== $this->errors;
    }
}
