<?php

namespace App\Markdown;

/**
 * Carries counters and errors for inline post-image migration runs.
 */
class PostContentImageMigrationResult
{
    public int $scanned = 0;

    public int $updated = 0;

    public int $imagesUploaded = 0;

    public int $imagesReused = 0;

    /**
     * @var array<int, string>
     */
    public array $errors = [];

    public function hasErrors() : bool
    {
        return [] !== $this->errors;
    }
}
