<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Console\Command;
use App\Markdown\MarkdownPostSource;
use Illuminate\Support\Facades\File;
use App\Actions\Posts\ResolveMarkdownPost;
use App\Actions\Posts\UploadCloudflarePostImage;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Uploads local post images to Cloudflare Images and optionally updates front matter.
 */
#[AsCommand(
    name: 'app:upload-post-image',
    description: 'Upload a local post image to Cloudflare Images.'
)]
class UploadPostImageCommand extends Command
{
    protected $signature = 'app:upload-post-image
        {source : Local image path}
        {--path= : Destination path within Cloudflare Images}
        {--markdown= : Markdown post file to update image_disk and image_path}
        {--alt= : Alt text to use in the returned Markdown snippet}';

    public function handle(
        ResolveMarkdownPost $resolveMarkdownPost,
        UploadCloudflarePostImage $uploadCloudflarePostImage,
    ) : int {
        $sourcePath = $this->resolveSourcePath((string) $this->argument('source'));
        $markdownSource = $this->resolveMarkdownSource($this->option('markdown'), $resolveMarkdownPost);
        $destinationPath = $this->resolveDestinationPath($sourcePath, (string) $this->option('path'));
        $result = $uploadCloudflarePostImage->handle(
            sourcePath: $sourcePath,
            destinationPath: $destinationPath,
            markdownSource: $markdownSource,
        );
        $alt = $this->resolveAltText($sourcePath);

        $this->info('Uploaded image to Cloudflare Images.');
        $this->line('Image disk: cloudflare-images');
        $this->line("Image path: {$result->path}");
        $this->line("URL: {$result->url}");
        $this->line("Markdown: ![{$alt}]({$result->url})");

        if ($markdownSource) {
            $this->line(
                'Updated Markdown front matter in [' . $markdownSource->relativePath . '] with image_disk/image_path.'
            );
            $this->line('Run php artisan app:sync-posts to persist the new image metadata.');
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

    protected function resolveMarkdownSource(
        mixed $markdownInput,
        ResolveMarkdownPost $resolveMarkdownPost,
    ) : ?MarkdownPostSource {
        $markdownInput = trim((string) $markdownInput);

        if ('' === $markdownInput) {
            return null;
        }

        return $resolveMarkdownPost->handle($markdownInput);
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
