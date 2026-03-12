<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Markdown\PostContentImageMigrator;
use App\Markdown\PostContentImageMigrationResult;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Rewrites markdown post content images to Cloudflare Images URLs.
 */
#[AsCommand(
    name: 'blog:migrate-post-content-images',
    description: 'Upload inline post content images to Cloudflare Images and rewrite Markdown URLs.'
)]
class BlogMigratePostContentImagesCommand extends Command
{
    public function handle(PostContentImageMigrator $migrator) : int
    {
        $result = new PostContentImageMigrationResult;
        $path = (string) config('blog.markdown.posts_path');

        $files = collect(File::allFiles($path))
            ->filter(fn (\SplFileInfo $file) => 'md' === $file->getExtension())
            ->sortBy(fn (\SplFileInfo $file) => str_replace('\\', '/', $file->getPathname()))
            ->values();

        foreach ($files as $file) {
            $result->scanned++;

            $contents = File::get($file->getPathname());

            if (! preg_match('/\A(?<prefix>---\R.*?\R---\R?)(?<body>.*)\z/s', $contents, $matches)) {
                $result->errors[] = "Invalid front matter format in {$file->getFilename()}.";

                continue;
            }

            $rewrittenBody = $migrator->rewrite($matches['body'], Str::of($file->getFilename())->beforeLast('.md')->toString(), $result);

            if ($rewrittenBody === $matches['body']) {
                continue;
            }

            File::put($file->getPathname(), $matches['prefix'] . $rewrittenBody);
            $result->updated++;
        }

        $this->info(
            "Migrated post content images: scanned={$result->scanned}, updated={$result->updated}, uploaded={$result->imagesUploaded}, reused={$result->imagesReused}."
        );

        foreach ($result->errors as $error) {
            $this->error($error);
        }

        return $result->hasErrors() ? self::FAILURE : self::SUCCESS;
    }
}
