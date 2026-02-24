<?php

namespace App\MarkdownSync;

/**
 * Carries counters and errors for production markdown bootstrap runs.
 */
class BootstrapPostsFromProductionResult
{
    public int $scanned = 0;

    public int $exported = 0;

    public int $skipped = 0;

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

