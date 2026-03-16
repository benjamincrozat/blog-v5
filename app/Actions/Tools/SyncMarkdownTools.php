<?php

namespace App\Actions\Tools;

use App\Models\Post;
use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Markdown\ToolMarkdownDocument;
use App\Exceptions\ToolMarkdownException;

/**
 * Syncs canonical Markdown tool sources into the database read model.
 */
class SyncMarkdownTools
{
    public function handle(?string $path = null) : SyncMarkdownToolsResult
    {
        $path ??= (string) config('blog.markdown.tools_path');

        if (! File::isDirectory($path)) {
            throw ToolMarkdownException::fromErrors([
                "[{$path}] Markdown tools directory does not exist.",
            ]);
        }

        $documents = $this->loadDocuments($path);
        $this->validateDocuments($documents);

        $reviewPostsBySlug = Post::query()
            ->whereIn('slug', collect($documents)->pluck('reviewPostSlug')->filter()->unique())
            ->get()
            ->keyBy('slug');

        $this->validateReviewPosts($documents, $reviewPostsBySlug->all());

        $existingById = Tool::query()
            ->whereIn('source_uuid', collect($documents)->pluck('id'))
            ->get()
            ->keyBy('source_uuid');

        $existingBySlug = Tool::query()
            ->whereIn('slug', collect($documents)->pluck('slug'))
            ->get()
            ->keyBy('slug');

        $this->validateSlugConflicts($documents, $existingById->all(), $existingBySlug->all());

        return DB::transaction(function () use ($documents, $reviewPostsBySlug, $existingById) {
            $createdCount = 0;
            $updatedCount = 0;

            foreach ($documents as $document) {
                $tool = $existingById[$document->id] ?? new Tool;
                $isNew = ! $tool->exists;

                $tool->forceFill([
                    'slug' => $document->slug,
                    'name' => $document->name,
                    'description' => $document->description,
                    'content' => blank($document->body) ? null : $document->body,
                    'website_url' => $document->websiteUrl,
                    'outbound_url' => $document->outboundUrl,
                    'pricing_model' => $document->pricingModel,
                    'has_free_plan' => $document->hasFreePlan,
                    'has_free_trial' => $document->hasFreeTrial,
                    'is_open_source' => $document->isOpenSource,
                    'categories' => $document->categories,
                    'image_path' => $document->imagePath,
                    'review_post_id' => $document->reviewPostSlug
                        ? $reviewPostsBySlug[$document->reviewPostSlug]->getKey()
                        : null,
                    'published_at' => $document->publishedAt,
                    'source_uuid' => $document->id,
                    'source_path' => $document->relativePath,
                    'source_hash' => $document->hash(),
                ]);

                $isDirty = $isNew || $tool->isDirty();

                $tool->save();

                if ($isNew) {
                    $createdCount++;

                    continue;
                }

                if ($isDirty) {
                    $updatedCount++;
                }
            }

            $trackedIds = collect($documents)->pluck('id');

            $deletedCount = Tool::query()
                ->whereNotIn('source_uuid', $trackedIds)
                ->delete();

            return new SyncMarkdownToolsResult(
                createdCount: $createdCount,
                updatedCount: $updatedCount,
                deletedCount: $deletedCount,
            );
        });
    }

    /**
     * @return array<int, ToolMarkdownDocument>
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
                $documents[] = ToolMarkdownDocument::fromMarkdown(File::get($file->getPathname()), $relativePath);
            } catch (ToolMarkdownException $exception) {
                $errors[] = $exception->getMessage();
            }
        }

        if ([] !== $errors) {
            throw ToolMarkdownException::fromErrors($errors);
        }

        return $documents;
    }

    /**
     * @param  array<int, ToolMarkdownDocument>  $documents
     */
    protected function validateDocuments(array $documents) : void
    {
        $errors = [];

        foreach (['id', 'slug'] as $field) {
            $duplicates = collect($documents)
                ->groupBy(fn (ToolMarkdownDocument $document) => $document->{$field})
                ->filter(fn ($items) => $items->count() > 1);

            foreach ($duplicates as $value => $items) {
                $errors[] = "Duplicate {$field} [{$value}] found in " . $items->pluck('relativePath')->implode(', ') . '.';
            }
        }

        $reviewDuplicates = collect($documents)
            ->filter(fn (ToolMarkdownDocument $document) => filled($document->reviewPostSlug))
            ->groupBy(fn (ToolMarkdownDocument $document) => $document->reviewPostSlug)
            ->filter(fn ($items) => $items->count() > 1);

        foreach ($reviewDuplicates as $reviewPostSlug => $items) {
            $errors[] = "Review post slug [{$reviewPostSlug}] is assigned in " . $items->pluck('relativePath')->implode(', ') . '.';
        }

        if ([] !== $errors) {
            throw ToolMarkdownException::fromErrors($errors);
        }
    }

    /**
     * @param  array<int, ToolMarkdownDocument>  $documents
     * @param  array<string, Post>  $reviewPostsBySlug
     */
    protected function validateReviewPosts(array $documents, array $reviewPostsBySlug) : void
    {
        $errors = [];

        foreach ($documents as $document) {
            if (blank($document->reviewPostSlug)) {
                continue;
            }

            $reviewPost = $reviewPostsBySlug[$document->reviewPostSlug] ?? null;

            if (! $reviewPost) {
                $errors[] = "[{$document->relativePath}] Unknown review post slug [{$document->reviewPostSlug}].";

                continue;
            }

            if (method_exists($reviewPost, 'trashed') && $reviewPost->trashed()) {
                $errors[] = "[{$document->relativePath}] Review post slug [{$document->reviewPostSlug}] is trashed.";
            }
        }

        if ([] !== $errors) {
            throw ToolMarkdownException::fromErrors($errors);
        }
    }

    /**
     * @param  array<int, ToolMarkdownDocument>  $documents
     * @param  array<string, Tool>  $existingById
     * @param  array<string, Tool>  $existingBySlug
     */
    protected function validateSlugConflicts(array $documents, array $existingById, array $existingBySlug) : void
    {
        $errors = [];

        foreach ($documents as $document) {
            $toolById = $existingById[$document->id] ?? null;
            $toolBySlug = $existingBySlug[$document->slug] ?? null;

            if ($toolById && $toolBySlug && ! $toolBySlug->is($toolById)) {
                $errors[] = "[{$document->relativePath}] Slug [{$document->slug}] already belongs to a different tool.";

                continue;
            }

            if ($toolById) {
                continue;
            }

            if ($toolBySlug && $toolBySlug->source_uuid !== $document->id) {
                $errors[] = "[{$document->relativePath}] Slug [{$document->slug}] already belongs to source id [{$toolBySlug->source_uuid}].";
            }
        }

        if ([] !== $errors) {
            throw ToolMarkdownException::fromErrors($errors);
        }
    }

    protected function relativePath(string $basePath, string $fullPath) : string
    {
        return ltrim(str_replace('\\', '/', Str::after($fullPath, rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)), '/');
    }
}
