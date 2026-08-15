<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Contracts\PostImageScreenshotter;
use App\Actions\Posts\ResolveMarkdownPost;
use App\Actions\Posts\UploadCloudflarePostImage;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Generates a fallback hero image for one Markdown-managed post.
 *
 * It captures the local preview as a temporary 1280x720 PNG and uploads it to a
 * stable Cloudflare path. It saves that path in front matter, then runs the post
 * sync. It will not replace existing image data unless --force is passed.
 * Temporary files are always removed. The remote upload can remain if the final
 * sync fails.
 */
#[AsCommand(
    name: 'app:generate-post-image',
    description: 'Generate and upload a fallback image for a Markdown-managed post.'
)]
class GeneratePostImageCommand extends Command
{
    protected $signature = 'app:generate-post-image
        {post : Post slug or Markdown path}
        {--force : Regenerate even if the post already has image metadata}';

    public function handle(
        ResolveMarkdownPost $resolveMarkdownPost,
        PostImageScreenshotter $postImageScreenshotter,
        UploadCloudflarePostImage $uploadCloudflarePostImage,
    ) : int {
        $source = $resolveMarkdownPost->handle((string) $this->argument('post'));

        if ($source->document->imageDisk && $source->document->imagePath && ! $this->option('force')) {
            $this->error(
                "Post [{$source->document->slug}] already has image_disk/image_path. Pass --force to replace it."
            );

            return self::FAILURE;
        }

        $temporaryDirectory = storage_path('app/post-images/' . Str::uuid());
        File::ensureDirectoryExists($temporaryDirectory);

        $temporaryScreenshotPath = $temporaryDirectory . '/' . $source->document->slug . '.png';
        $previewBaseUrl = (string) (config('blog.preview_base_url') ?: config('app.url', 'http://localhost'));
        $previewUrl = rtrim($previewBaseUrl, '/')
            . route('posts.image-preview', ['slug' => $source->document->slug], absolute: false);
        $destinationPath = "images/posts/generated/{$source->document->slug}.png";

        try {
            $postImageScreenshotter->capture($previewUrl, $temporaryScreenshotPath);

            $result = $uploadCloudflarePostImage->handle(
                sourcePath: $temporaryScreenshotPath,
                destinationPath: $destinationPath,
                markdownSource: $source,
                overwrite: (bool) $this->option('force'),
            );
        } finally {
            File::deleteDirectory($temporaryDirectory);
        }

        if (self::SUCCESS !== $this->call('app:sync-posts')) {
            $this->error('Image uploaded, but syncing Markdown posts failed.');

            return self::FAILURE;
        }

        $this->info('Generated post image and uploaded it to Cloudflare Images.');
        $this->line("Preview URL: {$previewUrl}");
        $this->line("Image disk: {$result->disk}");
        $this->line("Image path: {$result->path}");
        $this->line("URL: {$result->url}");
        $this->line(
            'Updated Markdown front matter in [' . $source->relativePath . '] and synced the posts database.'
        );

        return self::SUCCESS;
    }
}
