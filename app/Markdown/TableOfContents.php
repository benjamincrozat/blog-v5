<?php

namespace App\Markdown;

use Illuminate\Support\Str;

/**
 * Defines the TableOfContents implementation.
 */
class TableOfContents
{
    public function __construct(
        public string $markdown,
    ) {}

    /**
     * Build a hierarchical table of contents from Markdown headings.
     *
     * @return array<int, array{
     *     level: int,
     *     text: string,
     *     slug: string,
     *     children: array<int, array{
     *         level: int,
     *         text: string,
     *         slug: string,
     *         children: array
     *     }>
     * }>
     */
    public function toArray() : array
    {
        // Split the markdown into lines (supports various newline types).
        $lines = preg_split('/\R/', $this->markdown);

        $headings = [];

        $stack = [];

        $inFencedCodeBlock = false;

        foreach ($lines as $line) {
            // Toggle fenced code block detection (``` or ~~~).
            if (preg_match('/^\s*(```|~~~)/', $line)) {
                $inFencedCodeBlock = ! $inFencedCodeBlock;

                // We don't want to evaluate headings on the same line as the opening/closing fence.
                continue;
            }

            // Skip anything that lives inside a fenced code block.
            if ($inFencedCodeBlock) {
                continue;
            }

            // Look for markdown headings (one or more '#' followed by a space and then text).
            if (preg_match('/^(#+)\s+(.*)$/', $line, $matches)) {
                $level = strlen($matches[1]);  // The heading level is determined by the number of '#' characters

                $text = trim(strip_tags(MarkdownRenderer::parse($matches[2])));

                $node = [
                    'level' => $level,
                    'text' => $text,
                    'slug' => Str::slug($text),
                    'children' => [],
                ];

                // Pop the stack until we find a heading of a lower level.
                while (! empty($stack) && end($stack)['level'] >= $level) {
                    array_pop($stack);
                }

                if (empty($stack)) {
                    // No parent heading found; this is a top-level heading.
                    $headings[] = $node;

                    // Push a reference to the new node onto the stack.
                    $stack[] = &$headings[count($headings) - 1];
                } else {
                    // The current heading becomes a child of the last heading in the stack.
                    $parent = &$stack[count($stack) - 1];

                    $parent['children'][] = $node;

                    // Push a reference to the new child node onto the stack.
                    $stack[] = &$parent['children'][count($parent['children']) - 1];
                }
            }
        }

        return $headings;
    }
}
