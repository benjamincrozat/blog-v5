<?php

namespace App\MarkdownSync\Exceptions;

use RuntimeException;

/**
 * Signals that a post markdown file failed frontmatter or content validation.
 */
class InvalidPostMarkdownException extends RuntimeException
{
    /**
     * @param  array<int, string>  $errors
     */
    public function __construct(
        public readonly array $errors,
    ) {
        parent::__construct(implode("\n", $errors));
    }
}

