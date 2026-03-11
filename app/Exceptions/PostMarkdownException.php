<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Reports invalid Markdown post source data with actionable context.
 */
class PostMarkdownException extends RuntimeException
{
    /**
     * @param  array<int, string>  $errors
     */
    public static function fromErrors(array $errors) : self
    {
        $message = collect($errors)
            ->prepend('Markdown post validation failed:')
            ->implode("\n");

        return new self($message);
    }

    public static function forPath(string $relativePath, string $message) : self
    {
        return new self("[{$relativePath}] {$message}");
    }
}
