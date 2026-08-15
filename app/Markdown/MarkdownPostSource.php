<?php

namespace App\Markdown;

/**
 * Keeps checked Markdown post data together with the file it came from.
 *
 * Image tools use these read-only values to edit the exact source file without
 * finding it again. The full path is used for filesystem work. The shorter path
 * appears in messages that tell the reader which post has a problem.
 */
class MarkdownPostSource
{
    public function __construct(
        public readonly string $absolutePath,
        public readonly string $relativePath,
        public readonly PostMarkdownDocument $document,
    ) {}
}
