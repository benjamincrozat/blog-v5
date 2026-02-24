<?php

function complianceAllowlist() : array
{
    /** @var array<string, array<int, string>> $allowlist */
    $allowlist = require base_path('tests/Support/compliance_allowlist.php');

    return $allowlist;
}

function extractHtmlTags(string $content, string $name) : array
{
    $tags = [];
    $length = strlen($content);
    $cursor = 0;
    $needle = '<' . $name;

    while ($cursor < $length) {
        $start = stripos($content, $needle, $cursor);

        if (false === $start) {
            break;
        }

        $nextCharacter = $content[$start + strlen($needle)] ?? '';

        if ('' !== $nextCharacter && ! preg_match('/[\s>\/]/', $nextCharacter)) {
            $cursor = $start + 1;

            continue;
        }

        $index = $start + strlen($needle);
        $inSingleQuotes = false;
        $inDoubleQuotes = false;
        $bladeEchos = 0;
        $bladeRawEchos = 0;

        while ($index < $length) {
            $character = $content[$index];
            $nextTwo = substr($content, $index, 2);
            $nextThree = substr($content, $index, 3);

            if (! $inSingleQuotes && ! $inDoubleQuotes) {
                if ('{!!' === $nextThree) {
                    $bladeRawEchos++;
                    $index += 3;

                    continue;
                }

                if ($bladeRawEchos > 0 && '!!}' === $nextThree) {
                    $bladeRawEchos--;
                    $index += 3;

                    continue;
                }

                if ('{{' === $nextTwo) {
                    $bladeEchos++;
                    $index += 2;

                    continue;
                }

                if ($bladeEchos > 0 && '}}' === $nextTwo) {
                    $bladeEchos--;
                    $index += 2;

                    continue;
                }
            }

            if (0 === $bladeEchos && 0 === $bladeRawEchos) {
                if (! $inDoubleQuotes && "'" === $character) {
                    $inSingleQuotes = ! $inSingleQuotes;
                    $index++;

                    continue;
                }

                if (! $inSingleQuotes && '"' === $character) {
                    $inDoubleQuotes = ! $inDoubleQuotes;
                    $index++;

                    continue;
                }
            }

            if (! $inSingleQuotes && ! $inDoubleQuotes && 0 === $bladeEchos && 0 === $bladeRawEchos && '>' === $character) {
                $tags[] = [
                    'tag' => substr($content, $start, $index - $start + 1),
                    'offset' => $start,
                ];

                $cursor = $index + 1;

                continue 2;
            }

            $index++;
        }

        break;
    }

    return $tags;
}

it('keeps app classes documented with intentful docblocks', function () {
    $violations = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path('app')));

    foreach ($iterator as $file) {
        if (! $file->isFile() || 'php' !== $file->getExtension()) {
            continue;
        }

        $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());
        $code = file_get_contents($file->getPathname());

        if (! preg_match('/(?:(?:abstract|final)\s+)?(class|interface|trait|enum)\s+([A-Za-z_][A-Za-z0-9_]*)/', $code, $match, PREG_OFFSET_CAPTURE)) {
            continue;
        }

        $declarationOffset = $match[0][1];
        $prefix = substr($code, 0, $declarationOffset);

        if (! preg_match('/\/\*\*[\s\S]*?\*\/\s*(?:#\[[\s\S]*?\]\s*)*$/', $prefix, $docMatch)) {
            $violations[] = "{$path}: missing class-level docblock";

            continue;
        }

        $docblock = $docMatch[0];

        if (preg_match('/Defines the .* implementation\./', $docblock)) {
            $violations[] = "{$path}: generic class-level docblock";
        }
    }

    expect($violations)->toBeEmpty();
});

it('keeps blade top comments structured and contract-focused', function () {
    $violations = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path('resources/views')));

    foreach ($iterator as $file) {
        if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }

        $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

        if (str_starts_with($path, 'resources/views/vendor/')) {
            continue;
        }

        $content = file_get_contents($file->getPathname());

        if (! preg_match('/\A\s*\{\{--\R(.*?)\R--\}\}/s', $content, $match)) {
            $violations[] = "{$path}: missing top-level intent comment";

            continue;
        }

        $firstComment = $match[0];
        $normalizedComment = strtolower(trim(preg_replace('/\s+/', ' ', $match[1])));

        if (! str_starts_with($firstComment, "{{--\n") && ! str_starts_with($firstComment, "{{--\r\n")) {
            $violations[] = "{$path}: opening comment delimiter must be on its own line";
        }

        if (! str_ends_with($firstComment, "\n--}}") && ! str_ends_with($firstComment, "\r\n--}}")) {
            $violations[] = "{$path}: closing comment delimiter must be on its own line";
        }

        if (str_contains($normalizedComment, 'renders the ')) {
            $violations[] = "{$path}: top comment still uses generic render phrasing";
        }

        if (str_starts_with($path, 'resources/views/components/') && ! str_contains($normalizedComment, 'accepts')) {
            $violations[] = "{$path}: component comment must describe input contract";
        }

        if (preg_match('/\{\{--\s*@(?:if|foreach|for|while|can|cannot|empty)\b/s', $content)) {
            $violations[] = "{$path}: contains commented Blade control flow";
        }
    }

    expect($violations)->toBeEmpty();
});

it('keeps internal links wire navigable', function () {
    $allowlist = complianceAllowlist();
    $exemptions = $allowlist['internal_anchor_without_wire'];
    $violations = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path('resources/views')));

    foreach ($iterator as $file) {
        if (! $file->isFile() || ! str_ends_with($file->getFilename(), '.blade.php')) {
            continue;
        }

        $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

        if (str_starts_with($path, 'resources/views/vendor/')) {
            continue;
        }

        $content = file_get_contents($file->getPathname());

        foreach (extractHtmlTags($content, 'a') as $item) {
            $tag = $item['tag'];

            if (! preg_match('/\bhref\s*=\s*(["\'])(.*?)\1/is', $tag, $match)) {
                continue;
            }

            $href = trim($match[2]);
            $key = $path . '::' . $href;
            $isInternal = preg_match('/^\{\{\s*route\(/i', $href)
                || preg_match('/^\{\{\s*url\(/i', $href)
                || str_starts_with($href, '/')
                || str_starts_with($href, '#');

            if (! $isInternal) {
                continue;
            }

            if (in_array($key, $exemptions, true)) {
                continue;
            }

            if (str_starts_with($href, '#')) {
                continue;
            }

            if (preg_match('/\btarget\s*=\s*(["\'])_blank\1/i', $tag)) {
                continue;
            }

            if (! preg_match('/\bwire:navigate\b/i', $tag)) {
                $line = substr_count(substr($content, 0, $item['offset']), "\n") + 1;
                $violations[] = "{$path}:{$line}";
            }
        }
    }

    $buttonComponent = file_get_contents(base_path('resources/views/components/btn.blade.php'));
    $dropdownItemComponent = file_get_contents(base_path('resources/views/components/dropdown/item.blade.php'));

    if (! str_contains($buttonComponent, 'InternalNavigation::shouldUseWireNavigate')) {
        $violations[] = 'resources/views/components/btn.blade.php: missing internal-link navigation helper';
    }

    if (! str_contains($dropdownItemComponent, 'InternalNavigation::shouldUseWireNavigate')) {
        $violations[] = 'resources/views/components/dropdown/item.blade.php: missing internal-link navigation helper';
    }

    expect($violations)->toBeEmpty();
});

it('requires down methods for non-legacy migrations', function () {
    $allowlist = complianceAllowlist();
    $legacyWithoutDown = $allowlist['legacy_migrations_without_down'];
    $violations = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(base_path('database/migrations')));

    foreach ($iterator as $file) {
        if (! $file->isFile() || 'php' !== $file->getExtension()) {
            continue;
        }

        $path = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file->getPathname());

        if (in_array($path, $legacyWithoutDown, true)) {
            continue;
        }

        $content = file_get_contents($file->getPathname());

        if (! preg_match('/function\s+down\s*\(/', $content)) {
            $violations[] = $path;
        }
    }

    expect($violations)->toBeEmpty();
});
