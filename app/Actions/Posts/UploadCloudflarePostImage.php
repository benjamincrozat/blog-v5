<?php

namespace App\Actions\Posts;

use Illuminate\Support\Str;
use InvalidArgumentException;
use App\Markdown\MarkdownPostSource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * Uploads a local post image to the configured Cloudflare Images disk.
 *
 * It checks the source file, cleans or creates the remote path, and can delete an
 * existing image when overwrite mode is on. If a Markdown post is supplied, it
 * also writes the new disk and path into that file. It does not run the post sync.
 */
class UploadCloudflarePostImage
{
    public function handle(
        string $sourcePath,
        ?string $destinationPath = null,
        ?MarkdownPostSource $markdownSource = null,
        bool $overwrite = false,
    ) : UploadCloudflarePostImageResult {
        $resolvedSourcePath = $this->resolveSourcePath($sourcePath);
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

        if ($markdownSource) {
            $this->updateMarkdownFrontMatter($markdownSource, $resolvedDestinationPath);
        }

        return new UploadCloudflarePostImageResult(
            disk: 'cloudflare-images',
            path: $resolvedDestinationPath,
            url: Storage::disk('cloudflare-images')->url($resolvedDestinationPath),
        );
    }

    protected function resolveSourcePath(string $sourcePath) : string
    {
        if (! File::isFile($sourcePath)) {
            throw new InvalidArgumentException("Image source [{$sourcePath}] does not exist.");
        }

        return $sourcePath;
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

    protected function updateMarkdownFrontMatter(MarkdownPostSource $source, string $imagePath) : void
    {
        File::put(
            $source->absolutePath,
            $source->document->withImage('cloudflare-images', $imagePath)->toMarkdown(),
        );
    }

    protected function normalizePath(string $path) : string
    {
        return trim(str_replace('\\', '/', $path), '/');
    }
}
