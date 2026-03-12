<?php

namespace App\Markdown;

use Throwable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Mirrors inline markdown and raw HTML images onto Cloudflare Images.
 */
class PostContentImageMigrator
{
    /**
     * @var array<string, array{path: string, url: string}>
     */
    protected array $migratedImages = [];

    public function rewrite(string $markdown, string $slug, PostContentImageMigrationResult $result) : string
    {
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
        PostContentImageMigrationResult $result,
    ) : ?array {
        if (PostContentImageUrls::isCloudflare($url)) {
            return null;
        }

        if (array_key_exists($url, $this->migratedImages)) {
            $result->imagesReused++;

            return $this->migratedImages[$url];
        }

        $response = Http::retry(3, 200, throw: false)
            ->timeout(20)
            ->withHeaders([
                'User-Agent' => 'benjamincrozat.com image migration',
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
}
