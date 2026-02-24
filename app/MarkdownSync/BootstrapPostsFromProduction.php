<?php

namespace App\MarkdownSync;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

/**
 * Exports active production posts into markdown files and migrates embedded images.
 */
class BootstrapPostsFromProduction
{
    /**
     * @var array<string, array{path: string, url: string}>
     */
    protected array $migratedImages = [];

    public function __construct(
        protected PostMarkdownSerializer $serializer,
    ) {}

    public function handle(string $directory = 'resources/markdown/posts') : BootstrapPostsFromProductionResult
    {
        $result = new BootstrapPostsFromProductionResult;

        $absoluteDirectory = str_starts_with($directory, '/')
            ? $directory
            : base_path($directory);

        File::ensureDirectoryExists($absoluteDirectory);

        $production = DB::connection('production');

        $posts = $production
            ->table('posts')
            ->select([
                'id',
                'user_id',
                'image_path',
                'image_disk',
                'title',
                'slug',
                'content',
                'description',
                'canonical_url',
                'published_at',
                'modified_at',
                'serp_title',
                'is_commercial',
                'sponsored_at',
            ])
            ->whereNull('deleted_at')
            ->orderBy('id')
            ->get();

        $result->scanned = $posts->count();

        /** @var array<int, string> $authorsById */
        $authorsById = $production
            ->table('users')
            ->select(['id', 'slug'])
            ->pluck('slug', 'id')
            ->all();

        /** @var Collection<int, array<int, string>> $categoriesByPost */
        $categoriesByPost = $production
            ->table('category_post')
            ->join('categories', 'categories.id', '=', 'category_post.category_id')
            ->select(['category_post.post_id', 'categories.slug'])
            ->orderBy('category_post.post_id')
            ->get()
            ->groupBy('post_id')
            ->map(
                fn (Collection $rows) => $rows
                    ->pluck('slug')
                    ->filter()
                    ->map(fn (string $slug) => Str::slug($slug))
                    ->unique()
                    ->values()
                    ->all()
            );

        foreach ($posts as $post) {
            $slug = Str::slug((string) $post->slug);

            if (blank($slug)) {
                $result->skipped++;
                $result->errors[] = "Post #{$post->id}: missing or invalid slug.";

                continue;
            }

            if (blank((string) $post->title)) {
                $result->skipped++;
                $result->errors[] = "Post {$slug}: missing title.";

                continue;
            }

            $author = $authorsById[(int) $post->user_id] ?? null;
            if (blank($author)) {
                $result->skipped++;
                $result->errors[] = "Post {$slug}: unknown author for user_id {$post->user_id}.";

                continue;
            }

            $content = (string) ($post->content ?? '');
            $content = $this->rewriteEmbeddedImageUrls($content, $slug, $result);

            $imagePath = filled($post->image_path) ? (string) $post->image_path : null;
            $imageDisk = filled($post->image_disk) ? (string) $post->image_disk : null;

            if (filled($imagePath) && $this->isHttpUrl($imagePath)) {
                $migrated = $this->migrateExternalImage($imagePath, $slug, $result);

                if ($migrated) {
                    $imagePath = $migrated['path'];
                    $imageDisk = 'cloudflare-images';
                }
            }

            $payload = [
                'title' => (string) $post->title,
                'slug' => $slug,
                'author' => Str::slug((string) $author),
                'categories' => $categoriesByPost->get((int) $post->id, []),
                'description' => filled($post->description) ? (string) $post->description : null,
                'serp_title' => filled($post->serp_title) ? (string) $post->serp_title : null,
                'serp_description' => null,
                'canonical_url' => filled($post->canonical_url) ? (string) $post->canonical_url : null,
                'published_at' => filled($post->published_at) ? (string) $post->published_at : null,
                'modified_at' => filled($post->modified_at) ? (string) $post->modified_at : null,
                'image_path' => $imagePath,
                'image_disk' => $imageDisk,
                'is_commercial' => (bool) $post->is_commercial,
                'sponsored_at' => filled($post->sponsored_at) ? (string) $post->sponsored_at : null,
            ];

            File::put(
                $absoluteDirectory . "/{$slug}.md",
                $this->serializer->serialize($payload, $content),
            );

            $result->exported++;
        }

        return $result;
    }

    protected function rewriteEmbeddedImageUrls(
        string $markdown,
        string $slug,
        BootstrapPostsFromProductionResult $result,
    ) : string {
        $markdown = preg_replace_callback(
            '/(!\[[^\]]*]\()(?<url>https?:\/\/[^)\s]+)(\))/i',
            function (array $matches) use ($slug, $result) : string {
                $migrated = $this->migrateExternalImage($matches['url'], $slug, $result);

                if (! $migrated) {
                    return $matches[0];
                }

                return $matches[1] . $migrated['url'] . $matches[3];
            },
            $markdown,
        ) ?? $markdown;

        return preg_replace_callback(
            '/(<img\b[^>]*\bsrc=["\'])(?<url>https?:\/\/[^"\']+)(["\'][^>]*>)/i',
            function (array $matches) use ($slug, $result) : string {
                $migrated = $this->migrateExternalImage($matches['url'], $slug, $result);

                if (! $migrated) {
                    return $matches[0];
                }

                return $matches[1] . $migrated['url'] . $matches[3];
            },
            $markdown,
        ) ?? $markdown;
    }

    /**
     * @return array{path: string, url: string}|null
     */
    protected function migrateExternalImage(
        string $url,
        string $slug,
        BootstrapPostsFromProductionResult $result,
    ) : ?array {
        if ($this->isCurrentCloudflareImageUrl($url)) {
            return null;
        }

        if (array_key_exists($url, $this->migratedImages)) {
            $result->imagesReused++;

            return $this->migratedImages[$url];
        }

        $response = Http::retry(3, 200, throw: false)
            ->timeout(20)
            ->withHeaders([
                'User-Agent' => 'Nobinge Markdown Bootstrap',
            ])
            ->get($url);

        if (! $response->successful()) {
            $result->errors[] = "Image download failed for {$url} (HTTP {$response->status()}).";

            return null;
        }

        $mimeType = strtolower(trim(explode(';', (string) $response->header('Content-Type'))[0]));
        if (! str_starts_with($mimeType, 'image/')) {
            $result->errors[] = "Image download failed for {$url}: non-image content type {$mimeType}.";

            return null;
        }

        $extension = $this->determineFileExtension($url, $mimeType);
        $path = "images/posts/imported/{$slug}-" . substr(sha1($url), 0, 20) . ".{$extension}";

        try {
            if (! Storage::disk('cloudflare-images')->exists($path)) {
                Storage::disk('cloudflare-images')->put($path, $response->body());
                $result->imagesUploaded++;
            } else {
                $result->imagesReused++;
            }
        } catch (Throwable $exception) {
            $result->errors[] = "Image upload failed for {$url}: {$exception->getMessage()}";

            return null;
        }

        $mapped = [
            'path' => $path,
            'url' => Storage::disk('cloudflare-images')->url($path),
        ];

        $this->migratedImages[$url] = $mapped;

        return $mapped;
    }

    protected function determineFileExtension(string $url, string $mimeType) : string
    {
        $extensionByMime = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'image/avif' => 'avif',
            'image/svg+xml' => 'svg',
        ];

        if (array_key_exists($mimeType, $extensionByMime)) {
            return $extensionByMime[$mimeType];
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return '' !== $extension ? $extension : 'jpg';
    }

    protected function isHttpUrl(string $value) : bool
    {
        return (bool) preg_match('/^https?:\/\//i', $value);
    }

    protected function isCurrentCloudflareImageUrl(string $value) : bool
    {
        if (! $this->isHttpUrl($value)) {
            return false;
        }

        $accountHash = (string) config('services.cloudflare_images.account_hash');
        if (blank($accountHash)) {
            return false;
        }

        $host = parse_url($value, PHP_URL_HOST);
        $path = parse_url($value, PHP_URL_PATH);

        return 'imagedelivery.net' === $host && str_starts_with((string) $path, "/{$accountHash}/");
    }
}

