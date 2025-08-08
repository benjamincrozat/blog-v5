<?php

use App\Str;

if (! function_exists('extract_headings_from_markdown')) {
    /**
     * This handy helper was written by ChatGPT and helps
     * me display the table of contents in articles.
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
    function extract_headings_from_markdown(string $markdown) : array
    {
        // Split the markdown into lines (supports various newline types).
        $lines = preg_split('/\R/', $markdown);

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

                $text = trim(strip_tags(Str::markdown($matches[2])));

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

if (! function_exists('inject_newsletter_form')) {
    /**
     * Injects a newsletter form before in the middle of a given
     * piece of content. This helper has been written by GPT-5.
     */
    function inject_newsletter_form(string $content) : string
    {
        if ('' === $content) {
            return $content;
        }

        // Parse content as a fragment wrapped in a known root.
        $document = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $document->loadHTML('<div id="__root__">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $root = $document->getElementById('__root__');
        if (! $root) {
            return $content;
        }

        // Find all H2 headings.
        $headings = $document->getElementsByTagName('h2');

        // Create the test node to inject.
        $fragment = $document->createDocumentFragment();
        $fragment->appendXML('<p>Hello, World!</p>');

        if (0 === $headings->length) {
            // No H2 found, inject at the end of the content.
            $root->appendChild($fragment);

            $result = '';
            foreach ($root->childNodes as $childNode) {
                $result .= $document->saveHTML($childNode);
            }

            libxml_clear_errors();

            return $result;
        }

        $count = $headings->length;
        // Choose a position roughly at two-thirds of the way through the headings list.
        $insertBeforeIndex = (int) ceil($count * 2 / 3); // 6 -> 4, 3 -> 2, 1 -> 1
        if ($insertBeforeIndex < 1) {
            $insertBeforeIndex = 1;
        }
        if ($insertBeforeIndex > $count) {
            $insertBeforeIndex = $count;
        }

        /** @var DOMElement $targetHeading */
        $targetHeading = $headings->item($insertBeforeIndex - 1);
        if ($targetHeading && $targetHeading->parentNode) {
            $targetHeading->parentNode->insertBefore($fragment, $targetHeading);
        } else {
            $root->appendChild($fragment);
        }

        // Extract inner HTML of the root container only.
        $result = '';
        foreach ($root->childNodes as $childNode) {
            $result .= $document->saveHTML($childNode);
        }

        libxml_clear_errors();

        return $result;
    }
}
