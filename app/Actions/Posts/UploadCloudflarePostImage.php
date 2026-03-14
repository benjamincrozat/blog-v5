<?php

namespace App\Actions\Posts;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Facades\File;
use App\Markdown\PostMarkdownDocument;
use Illuminate\Support\Facades\Storage;

/**
 * Uploads a local image to Cloudflare Images and can sync Markdown front matter.
 */
class UploadCloudflarePostImage
{
    public function handle(
        string $sourcePath,
        ?string $destinationPath = null,
        ?string $markdownPath = null,
        bool $overwrite = false,
    ) : UploadCloudflarePostImageResult {
        $resolvedSourcePath = $this->resolveSourcePath($sourcePath);
        $resolvedMarkdownPath = $this->resolveMarkdownPath($markdownPath);
        $resolvedDestinationPath = $this->resolveDestinationPath($resolvedSourcePath, $destinationPath);

        if ($overwrite && Storage::disk('cloudflare-images')->exists($resolvedDestinationPath)) {
            Storage::disk('cloudflare-images')->delete($resolvedDestinationPath);
        }

        $stream = fopen($resolvedSourcePath, 'r');

        if (false === $stream) {
            throw new InvalidArgumentException("Image source [{$resolvedSourcePath}] could not be opened.");
        }

        try {
            Storage::disk('cloudflare-images')->put($resolvedDestinationPath, $stream);
        } finally {
            fclose($stream);
        }

        if ($resolvedMarkdownPath) {
            $this->updateMarkdownFrontMatter($resolvedMarkdownPath, $resolvedDestinationPath);
        }

        return new UploadCloudflarePostImageResult(
            disk: 'cloudflare-images',
            path: $resolvedDestinationPath,
            url: Storage::disk('cloudflare-images')->url($resolvedDestinationPath),
            markdownPath: $resolvedMarkdownPath,
        );
    }

    protected function resolveSourcePath(string $sourcePath) : string
    {
        if (! File::isFile($sourcePath)) {
            throw new InvalidArgumentException("Image source [{$sourcePath}] does not exist.");
        }

        return $sourcePath;
    }

    protected function resolveMarkdownPath(?string $markdownPath) : ?string
    {
        $trimmedMarkdownPath = trim((string) $markdownPath);

        if ('' === $trimmedMarkdownPath) {
            return null;
        }

        $candidates = array_unique([
            $trimmedMarkdownPath,
            rtrim((string) config('blog.markdown.posts_path'), DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . ltrim($trimmedMarkdownPath, DIRECTORY_SEPARATOR),
        ]);

        foreach ($candidates as $candidate) {
            if (File::isFile($candidate)) {
                return $candidate;
            }
        }

        throw new InvalidArgumentException("Markdown post [{$trimmedMarkdownPath}] does not exist.");
    }

    protected function resolveDestinationPath(string $sourcePath, ?string $destinationPath) : string
    {
        $trimmedDestinationPath = trim((string) $destinationPath);

        if ('' !== $trimmedDestinationPath) {
            return $this->normalizePath($trimmedDestinationPath);
        }

        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));

        if ('' === $extension) {
            throw new InvalidArgumentException(
                "Image source [{$sourcePath}] must have a file extension or you must pass --path."
            );
        }

        return 'images/posts/' . Str::ulid() . ".{$extension}";
    }

    protected function updateMarkdownFrontMatter(string $markdownPath, string $imagePath) : void
    {
        $document = PostMarkdownDocument::fromMarkdown(
            File::get($markdownPath),
            $this->markdownRelativePath($markdownPath),
        )->withImage('cloudflare-images', $imagePath);

        File::put($markdownPath, $document->toMarkdown());
    }

    protected function markdownRelativePath(string $markdownPath) : string
    {
        $basePath = rtrim((string) config('blog.markdown.posts_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $normalizedBasePath = $this->normalizePath($basePath);
        $normalizedMarkdownPath = $this->normalizePath($markdownPath);

        if (str_starts_with($normalizedMarkdownPath, $normalizedBasePath)) {
            return ltrim(Str::after($normalizedMarkdownPath, $normalizedBasePath), '/');
        }

        return basename($normalizedMarkdownPath);
    }

    protected function normalizePath(string $path) : string
    {
        return trim(str_replace('\\', '/', $path), '/');
    }
}
