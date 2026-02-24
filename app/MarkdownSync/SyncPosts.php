<?php

namespace App\MarkdownSync;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Synchronizes markdown source posts into the database read model.
 */
class SyncPosts
{
    public function __construct(
        protected PostMarkdownParser $parser,
    ) {}

    public function handle(string $directory = 'resources/markdown/posts') : SyncPostsResult
    {
        $result = new SyncPostsResult;

        $absoluteDirectory = str_starts_with($directory, '/')
            ? $directory
            : base_path($directory);

        if (! File::exists($absoluteDirectory)) {
            File::ensureDirectoryExists($absoluteDirectory);
        }

        $files = collect(File::allFiles($absoluteDirectory))
            ->filter(fn (\SplFileInfo $file) => 'md' === $file->getExtension())
            ->sortBy(fn (\SplFileInfo $file) => $file->getPathname())
            ->values();

        $result->scanned = $files->count();

        /** @var array<int, string> $sourceSlugs */
        $sourceSlugs = $files
            ->map(fn (\SplFileInfo $file) => pathinfo($file->getFilename(), PATHINFO_FILENAME))
            ->values()
            ->all();

        $files->each(function (\SplFileInfo $file) use ($result) : void {
            try {
                $parsed = $this->parser->parseFile($file->getPathname());
            } catch (\RuntimeException $exception) {
                $result->skipped++;

                foreach (array_filter(explode("\n", $exception->getMessage())) as $error) {
                    $result->errors[] = $error;
                }

                return;
            }

            /** @var User|null $author */
            $author = User::query()
                ->where('slug', $parsed->author)
                ->first();

            if (! $author) {
                $result->skipped++;
                $result->errors[] = "{$parsed->path}: Unknown author slug \"{$parsed->author}\".";

                return;
            }

            DB::transaction(function () use ($parsed, $author, $result) : void {
                /** @var Post $post */
                $post = Post::withTrashed()
                    ->where('slug', $parsed->slug)
                    ->first() ?? new Post;

                $exists = $post->exists;

                $post->slug = $parsed->slug;
                $post->title = $parsed->title;
                $post->content = $parsed->content;
                $post->user_id = $author->getKey();
                $post->description = $parsed->description;
                $post->serp_title = $parsed->serpTitle;
                $post->serp_description = $parsed->serpDescription;
                $post->canonical_url = $parsed->canonicalUrl;
                $post->published_at = $parsed->publishedAt;
                $post->modified_at = $parsed->modifiedAt;
                $post->image_path = $parsed->imagePath;
                $post->image_disk = $parsed->imageDisk;
                $post->is_commercial = $parsed->isCommercial;
                $post->sponsored_at = $parsed->sponsoredAt;
                $post->deleted_at = null;

                $isDirty = $post->isDirty();
                $post->save();

                $categoryIds = $this->resolveCategoryIds($parsed->categories);
                $beforeCategories = $post->categories()
                    ->pluck('categories.id')
                    ->map(fn (int $id) => (int) $id)
                    ->values()
                    ->all();
                sort($beforeCategories);

                $post->categories()->sync($categoryIds);
                $sortedCategoryIds = $categoryIds;
                sort($sortedCategoryIds);

                $categoriesChanged = $beforeCategories !== $sortedCategoryIds;

                if (! $exists) {
                    $result->created++;

                    return;
                }

                if ($isDirty || $categoriesChanged || $post->wasChanged('deleted_at')) {
                    $result->updated++;
                }
            });
        });

        $result->softDeleted = Post::query()
            ->whereNull('deleted_at')
            ->whereNotIn('slug', $sourceSlugs)
            ->get()
            ->tap(fn (Collection $posts) => $posts->each->delete())
            ->count();

        return $result;
    }

    /**
     * @param  array<int, string>  $slugs
     * @return array<int, int>
     */
    protected function resolveCategoryIds(array $slugs) : array
    {
        return collect($slugs)
            ->map(fn (string $slug) => Str::slug($slug))
            ->filter()
            ->unique()
            ->map(function (string $slug) : int {
                /** @var Category $category */
                $category = Category::query()->firstOrCreate(
                    ['slug' => $slug],
                    ['name' => Str::headline($slug)],
                );

                return $category->getKey();
            })
            ->values()
            ->all();
    }
}
