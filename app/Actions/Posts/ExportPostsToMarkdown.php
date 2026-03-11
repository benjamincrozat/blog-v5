<?php

namespace App\Actions\Posts;

use App\Models\Post;
use Illuminate\Support\Facades\File;
use App\Markdown\PostMarkdownDocument;
use App\Exceptions\PostMarkdownException;

/**
 * Exports database-backed posts into canonical Markdown source files.
 */
class ExportPostsToMarkdown
{
    /**
     * @param  array<int, string>  $slugs
     */
    public function handle(array $slugs = []) : ExportPostsToMarkdownResult
    {
        $path = (string) config('blog.markdown.posts_path');

        File::ensureDirectoryExists($path);

        $posts = Post::query()
            ->with([
                'user:id,github_login',
                'categories:id,slug',
            ])
            ->withoutTrashed()
            ->when([] !== $slugs, fn ($query) => $query->whereIn('slug', $slugs))
            ->orderBy('slug')
            ->get();

        $missingSlugs = collect($slugs)
            ->diff($posts->pluck('slug'))
            ->values()
            ->all();

        if ([] !== $missingSlugs) {
            throw PostMarkdownException::fromErrors(
                collect($missingSlugs)
                    ->map(fn (string $slug) => "[{$slug}] No matching post exists in the local database.")
                    ->all()
            );
        }

        $exportedCount = 0;

        foreach ($posts as $post) {
            $document = PostMarkdownDocument::fromPost($post);
            $markdown = $document->toMarkdown();
            $newPath = $path . DIRECTORY_SEPARATOR . $document->relativePath;

            File::ensureDirectoryExists(dirname($newPath));
            File::put($newPath, $markdown);

            $previousPath = filled($post->source_path)
                ? $path . DIRECTORY_SEPARATOR . ltrim((string) $post->source_path, DIRECTORY_SEPARATOR)
                : null;

            if ($previousPath && $previousPath !== $newPath && File::exists($previousPath)) {
                File::delete($previousPath);
            }

            $post->forceFill([
                'source_uuid' => $document->id,
                'source_path' => $document->relativePath,
                'source_hash' => $document->hash(),
            ])->save();

            $exportedCount++;
        }

        return new ExportPostsToMarkdownResult(
            exportedCount: $exportedCount,
            path: $path,
        );
    }
}
