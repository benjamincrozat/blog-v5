<?php

use App\Str;

if (! function_exists('extract_headings_from_markdown')) {
    function extract_headings_from_markdown($markdown)
    {
        // Split the markdown into lines (supports various newline types).
        $lines = preg_split('/\R/', $markdown);

        $headings = [];

        $stack = [];

        foreach ($lines as $line) {
            // Look for markdown headings (one or more '#' followed by a space and then text).
            if (preg_match('/^(#+)\s+(.*)$/', $line, $matches)) {
                $level = strlen($matches[1]);  // The heading level is determined by the number of '#' characters

                $text = trim($matches[2]);

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
