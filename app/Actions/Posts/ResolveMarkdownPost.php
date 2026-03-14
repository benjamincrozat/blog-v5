<?php

namespace App\Actions\Posts;

use Illuminate\Support\Str;
use InvalidArgumentException;
use App\Markdown\MarkdownPostSource;
use Illuminate\Support\Facades\File;
use App\Markdown\PostMarkdownDocument;

/**
 * Resolves markdown-managed posts from a slug or file path.
 */
class ResolveMarkdownPost
{
    public function handle(string $input) : MarkdownPostSource
    {
        $trimmedInput = trim($input);

        if ('' === $trimmedInput) {
            throw new InvalidArgumentException('A post slug or Markdown path is required.');
        }

        return $this->looksLikePath($trimmedInput)
            ? $this->resolveByPath($trimmedInput)
            : $this->resolveBySlug($trimmedInput);
    }

    public function fromSlug(string $slug) : MarkdownPostSource
    {
        return $this->resolveBySlug($slug);
    }

    protected function resolveByPath(string $path) : MarkdownPostSource
    {
        foreach ($this->pathCandidates($path) as $candidate) {
            if (! File::isFile($candidate)) {
                continue;
            }

            return $this->sourceFromPath($candidate);
        }

        throw new InvalidArgumentException("Markdown post [{$path}] does not exist.");
    }

    protected function resolveBySlug(string $slug) : MarkdownPostSource
    {
        $matches = collect(File::allFiles($this->basePath()))
            ->filter(fn (\SplFileInfo $file) => 'md' === $file->getExtension())
            ->filter(fn (\SplFileInfo $file) => $file->getBasename('.md') === $slug)
            ->values();

        if ($matches->isEmpty()) {
            throw new InvalidArgumentException("Markdown post for slug [{$slug}] does not exist.");
        }

        if ($matches->count() > 1) {
            throw new InvalidArgumentException("Multiple Markdown posts match slug [{$slug}].");
        }

        return $this->sourceFromPath($matches->first()->getPathname());
    }

    /**
     * @return array<int, string>
     */
    protected function pathCandidates(string $path) : array
    {
        $normalizedPath = str_replace('\\', '/', $path);

        return array_values(array_unique([
            $normalizedPath,
            $this->basePath() . DIRECTORY_SEPARATOR . ltrim($normalizedPath, DIRECTORY_SEPARATOR),
        ]));
    }

    protected function sourceFromPath(string $absolutePath) : MarkdownPostSource
    {
        $relativePath = $this->relativePath($absolutePath);

        return new MarkdownPostSource(
            absolutePath: $absolutePath,
            relativePath: $relativePath,
            document: PostMarkdownDocument::fromMarkdown(File::get($absolutePath), $relativePath),
        );
    }

    protected function relativePath(string $absolutePath) : string
    {
        $basePath = $this->normalizePath($this->basePath()) . '/';
        $normalizedPath = $this->normalizePath($absolutePath);

        return ltrim(Str::after($normalizedPath, $basePath), '/');
    }

    protected function looksLikePath(string $input) : bool
    {
        return str_contains($input, '/') || str_contains($input, '\\') || str_ends_with($input, '.md');
    }

    protected function basePath() : string
    {
        return (string) config('blog.markdown.posts_path');
    }

    protected function normalizePath(string $path) : string
    {
        return trim(str_replace('\\', '/', $path), '/');
    }
}
