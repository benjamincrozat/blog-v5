<?php

namespace App\Actions\Posts;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Markdown\PostMarkdownDocument;
use App\Exceptions\PostMarkdownException;

/**
 * Syncs canonical Markdown post sources into the database read model.
 */
class SyncMarkdownPosts
{
    public function handle(?string $path = null) : SyncMarkdownPostsResult
    {
        $path ??= (string) config('blog.markdown.posts_path');

        if (! File::isDirectory($path)) {
            throw PostMarkdownException::fromErrors([
                "[{$path}] Markdown posts directory does not exist.",
            ]);
        }

        $documents = $this->loadDocuments($path);
        $this->validateDocuments($documents);

        $authorsByLogin = User::query()
            ->whereIn('github_login', collect($documents)->pluck('author')->unique())
            ->get()
            ->keyBy('github_login');

        $categoriesBySlug = Category::query()
            ->whereIn('slug', collect($documents)->flatMap(fn (PostMarkdownDocument $document) => $document->categories)->unique())
            ->get()
            ->keyBy('slug');

        $this->validateReferences($documents, $authorsByLogin->all(), $categoriesBySlug->all());

        $existingById = Post::withTrashed()
            ->whereIn('source_uuid', collect($documents)->pluck('id'))
            ->get()
            ->keyBy('source_uuid');

        $existingBySlug = Post::withTrashed()
            ->whereIn('slug', collect($documents)->pluck('slug'))
            ->get()
            ->keyBy('slug');

        $this->validateSlugConflicts($documents, $existingById->all(), $existingBySlug->all());

        return DB::transaction(function () use ($documents, $authorsByLogin, $categoriesBySlug, $existingById) {
            $createdCount = 0;
            $updatedCount = 0;
            $restoredCount = 0;

            foreach ($documents as $document) {
                $post = $existingById[$document->id] ?? Post::withTrashed()
                    ->whereNull('source_uuid')
                    ->where('slug', $document->slug)
                    ->first();

                $isNew = ! $post;

                if ($isNew) {
                    $post = new Post;
                }

                $wasTrashed = $post->exists && $post->trashed();
                $categoryIds = collect($document->categories)
                    ->map(fn (string $slug) => $categoriesBySlug[$slug]->getKey())
                    ->all();

                $post->forceFill([
                    'title' => $document->title,
                    'slug' => $document->slug,
                    'content' => $document->body,
                    'description' => $document->description,
                    'serp_title' => $document->serpTitle,
                    'serp_description' => $document->serpDescription,
                    'canonical_url' => $document->canonicalUrl,
                    'is_commercial' => $document->isCommercial,
                    'published_at' => $document->publishedAt,
                    'modified_at' => $document->modifiedAt,
                    'image_disk' => $document->imageDisk,
                    'image_path' => $document->imagePath,
                    'sponsored_at' => $document->sponsoredAt,
                    'source_uuid' => $document->id,
                    'source_path' => $document->relativePath,
                    'source_hash' => $document->hash(),
                    'deleted_at' => null,
                ]);

                $post->user()->associate($authorsByLogin[$document->author]);

                $isDirty = $isNew || $post->isDirty();

                $post->save();

                $categoryChanges = $post->categories()->sync($categoryIds);
                $hasCategoryChanges = collect($categoryChanges)->flatten()->isNotEmpty();

                if ($isNew) {
                    $createdCount++;
                } elseif ($wasTrashed) {
                    $restoredCount++;
                } elseif ($isDirty || $hasCategoryChanges) {
                    $updatedCount++;
                }
            }

            $trackedIds = collect($documents)->pluck('id');

            $postsToDelete = Post::query()
                ->whereNotNull('source_uuid')
                ->whereNotIn('source_uuid', $trackedIds)
                ->get();

            $deletedCount = 0;

            foreach ($postsToDelete as $post) {
                if ($post->trashed()) {
                    continue;
                }

                $post->delete();
                $deletedCount++;
            }

            return new SyncMarkdownPostsResult(
                createdCount: $createdCount,
                updatedCount: $updatedCount,
                restoredCount: $restoredCount,
                deletedCount: $deletedCount,
            );
        });
    }

    /**
     * @return array<int, PostMarkdownDocument>
     */
    protected function loadDocuments(string $path) : array
    {
        $errors = [];
        $documents = [];

        $files = collect(File::allFiles($path))
            ->filter(fn (\SplFileInfo $file) => 'md' === $file->getExtension())
            ->sortBy(fn (\SplFileInfo $file) => $this->relativePath($path, $file->getPathname()))
            ->values();

        foreach ($files as $file) {
            $relativePath = $this->relativePath($path, $file->getPathname());

            try {
                $documents[] = PostMarkdownDocument::fromMarkdown(File::get($file->getPathname()), $relativePath);
            } catch (PostMarkdownException $exception) {
                $errors[] = $exception->getMessage();
            }
        }

        if ([] !== $errors) {
            throw PostMarkdownException::fromErrors($errors);
        }

        return $documents;
    }

    /**
     * @param  array<int, PostMarkdownDocument>  $documents
     */
    protected function validateDocuments(array $documents) : void
    {
        $errors = [];

        foreach (['id', 'slug'] as $field) {
            $duplicates = collect($documents)
                ->groupBy(fn (PostMarkdownDocument $document) => $document->{$field})
                ->filter(fn ($items) => $items->count() > 1);

            foreach ($duplicates as $value => $items) {
                $errors[] = "Duplicate {$field} [{$value}] found in " . $items->pluck('relativePath')->implode(', ') . '.';
            }
        }

        if ([] !== $errors) {
            throw PostMarkdownException::fromErrors($errors);
        }
    }

    /**
     * @param  array<int, PostMarkdownDocument>  $documents
     * @param  array<string, User>  $authorsByLogin
     * @param  array<string, Category>  $categoriesBySlug
     */
    protected function validateReferences(array $documents, array $authorsByLogin, array $categoriesBySlug) : void
    {
        $errors = [];

        foreach ($documents as $document) {
            if (! Arr::exists($authorsByLogin, $document->author)) {
                $errors[] = "[{$document->relativePath}] Unknown author [{$document->author}].";
            }

            $missingCategories = collect($document->categories)
                ->reject(fn (string $slug) => Arr::exists($categoriesBySlug, $slug))
                ->values()
                ->all();

            if ([] !== $missingCategories) {
                $errors[] = "[{$document->relativePath}] Unknown categories: " . implode(', ', $missingCategories) . '.';
            }
        }

        if ([] !== $errors) {
            throw PostMarkdownException::fromErrors($errors);
        }
    }

    /**
     * @param  array<int, PostMarkdownDocument>  $documents
     * @param  array<string, Post>  $existingById
     * @param  array<string, Post>  $existingBySlug
     */
    protected function validateSlugConflicts(array $documents, array $existingById, array $existingBySlug) : void
    {
        $errors = [];

        foreach ($documents as $document) {
            $postById = $existingById[$document->id] ?? null;
            $postBySlug = $existingBySlug[$document->slug] ?? null;

            if ($postById && $postBySlug && ! $postBySlug->is($postById)) {
                $errors[] = "[{$document->relativePath}] Slug [{$document->slug}] already belongs to a different post.";

                continue;
            }

            if ($postById) {
                continue;
            }

            if ($postBySlug && filled($postBySlug->source_uuid) && $postBySlug->source_uuid !== $document->id) {
                $errors[] = "[{$document->relativePath}] Slug [{$document->slug}] already belongs to source id [{$postBySlug->source_uuid}].";
            }
        }

        if ([] !== $errors) {
            throw PostMarkdownException::fromErrors($errors);
        }
    }

    protected function relativePath(string $basePath, string $fullPath) : string
    {
        return ltrim(str_replace('\\', '/', Str::after($fullPath, rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)), '/');
    }
}
