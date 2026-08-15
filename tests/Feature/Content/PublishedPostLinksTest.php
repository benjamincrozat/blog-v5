<?php

use App\Markdown\PostMarkdownDocument;

it('keeps root-level internal links pointed at published posts', function () {
    $documents = collect(glob(resource_path('markdown/posts/*.md')))
        ->map(function (string $path) : PostMarkdownDocument {
            $relativePath = str_replace(resource_path('markdown') . DIRECTORY_SEPARATOR, '', $path);

            return PostMarkdownDocument::fromMarkdown(file_get_contents($path), $relativePath);
        });

    $published = $documents
        ->filter(fn (PostMarkdownDocument $document) => $document->publishedAt?->lte(now()))
        ->values();

    $publishedSlugs = $published->pluck('slug')->flip();
    $violations = [];

    foreach ($published as $document) {
        preg_match_all(
            '/(?<!\!)\[[^\]]+\]\((?<url>[^)\s]+)(?:\s+["\'][^"\']*["\'])?\)/',
            $document->body,
            $matches,
        );

        foreach ($matches['url'] as $url) {
            $host = parse_url($url, PHP_URL_HOST);

            if ($host && ! in_array($host, ['benjamincrozat.com', 'www.benjamincrozat.com'], true)) {
                continue;
            }

            if (! $host && ! str_starts_with($url, '/')) {
                continue;
            }

            $slug = trim((string) parse_url($url, PHP_URL_PATH), '/');

            if ('' === $slug || str_contains($slug, '/')) {
                continue;
            }

            if (! $publishedSlugs->has($slug)) {
                $violations[] = "{$document->relativePath} -> {$url}";
            }
        }
    }

    expect($violations)->toBeEmpty();
});
