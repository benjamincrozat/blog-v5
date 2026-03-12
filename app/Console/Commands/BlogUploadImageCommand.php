<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Markdown\PostMarkdownDocument;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Uploads local post images to Cloudflare Images and optionally updates front matter.
 */
#[AsCommand(
    name: 'blog:upload-image',
    description: 'Upload a local post image to Cloudflare Images.'
)]
class BlogUploadImageCommand extends Command
{
    protected $signature = 'blog:upload-image
        {source : Local image path}
        {--path= : Destination path within Cloudflare Images}
        {--markdown= : Markdown post file to update image_disk and image_path}
        {--alt= : Alt text to use in the returned Markdown snippet}';

    public function handle() : int
    {
        $sourcePath = $this->resolveSourcePath((string) $this->argument('source'));
        $markdownPath = $this->resolveMarkdownPath($this->option('markdown'));
        $destinationPath = $this->resolveDestinationPath($sourcePath, (string) $this->option('path'));

        $stream = fopen($sourcePath, 'r');

        if (false === $stream) {
            throw new InvalidArgumentException("Image source [{$sourcePath}] could not be opened.");
        }

        try {
            Storage::disk('cloudflare-images')->put($destinationPath, $stream);
        } finally {
            fclose($stream);
        }

        if ($markdownPath) {
            $this->updateMarkdownFrontMatter($markdownPath, $destinationPath);
        }

        $url = Storage::disk('cloudflare-images')->url($destinationPath);
        $alt = $this->resolveAltText($sourcePath);

        $this->info('Uploaded image to Cloudflare Images.');
        $this->line('Image disk: cloudflare-images');
        $this->line("Image path: {$destinationPath}");
        $this->line("URL: {$url}");
        $this->line("Markdown: ![{$alt}]({$url})");

        if ($markdownPath) {
            $this->line(
                'Updated Markdown front matter in [' . $this->markdownRelativePath($markdownPath) . '] with image_disk/image_path.'
            );
            $this->line('Run php artisan blog:sync to persist the new image metadata.');
        } else {
            $this->line(
                'Use the URL above for inline article images, or pass --markdown=post-slug.md to update the hero image fields.'
            );
        }

        return self::SUCCESS;
    }

    protected function resolveSourcePath(string $sourcePath) : string
    {
        if (! File::isFile($sourcePath)) {
            throw new InvalidArgumentException("Image source [{$sourcePath}] does not exist.");
        }

        return $sourcePath;
    }

    protected function resolveMarkdownPath(mixed $markdownPath) : ?string
    {
        $markdownPath = trim((string) $markdownPath);

        if ('' === $markdownPath) {
            return null;
        }

        $candidates = array_unique([
            $markdownPath,
            rtrim((string) config('blog.markdown.posts_path'), DIRECTORY_SEPARATOR)
                . DIRECTORY_SEPARATOR
                . ltrim($markdownPath, DIRECTORY_SEPARATOR),
        ]);

        foreach ($candidates as $candidate) {
            if (File::isFile($candidate)) {
                return $candidate;
            }
        }

        throw new InvalidArgumentException("Markdown post [{$markdownPath}] does not exist.");
    }

    protected function resolveDestinationPath(string $sourcePath, string $pathOption) : string
    {
        $pathOption = trim($pathOption);

        if ('' !== $pathOption) {
            return $this->normalizePath($pathOption);
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

    protected function resolveAltText(string $sourcePath) : string
    {
        $alt = trim((string) $this->option('alt'));

        if ('' !== $alt) {
            return $alt;
        }

        $fallback = Str::of(pathinfo($sourcePath, PATHINFO_FILENAME))
            ->replace(['-', '_'], ' ')
            ->squish()
            ->toString();

        return '' !== $fallback ? $fallback : 'Image';
    }
}
