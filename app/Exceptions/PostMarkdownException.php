<?php

namespace App\Exceptions;

use RuntimeException;

/**
 * Marks invalid Markdown post files with errors that tell the reader what to fix.
 *
 * Parsing can report one error with its file path, while a full sync can group
 * errors from several files. Commands can show these expected content problems
 * apart from unexpected filesystem, database, or remote-service failures.
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
