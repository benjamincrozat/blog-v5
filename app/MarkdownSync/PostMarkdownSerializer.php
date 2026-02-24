<?php

namespace App\MarkdownSync;

use App\Models\Post;
use Illuminate\Support\Collection;

/**
 * Serializes posts into deterministic YAML frontmatter markdown files.
 */
class PostMarkdownSerializer
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function serialize(array $payload, string $content) : string
    {
        $frontMatter = [
            'title' => $payload['title'],
            'slug' => $payload['slug'],
            'author' => $payload['author'],
            'categories' => $payload['categories'],
        ];

        $optional = [
            'description' => $payload['description'] ?? null,
            'serp_title' => $payload['serp_title'] ?? null,
            'serp_description' => $payload['serp_description'] ?? null,
            'canonical_url' => $payload['canonical_url'] ?? null,
            'published_at' => $payload['published_at'] ?? null,
            'modified_at' => $payload['modified_at'] ?? null,
            'image_path' => $payload['image_path'] ?? null,
            'image_disk' => $payload['image_disk'] ?? null,
            'is_commercial' => $payload['is_commercial'] ?? false,
            'sponsored_at' => $payload['sponsored_at'] ?? null,
        ];

        foreach ($optional as $key => $value) {
            if ('is_commercial' === $key) {
                if (true === $value) {
                    $frontMatter[$key] = true;
                }

                continue;
            }

            if (blank($value)) {
                continue;
            }

            if ('image_disk' === $key && 'cloudflare-images' === $value) {
                continue;
            }

            $frontMatter[$key] = $value;
        }

        $lines = [];

        foreach ($frontMatter as $key => $value) {
            if ('categories' === $key) {
                $categories = collect($value)->filter()->values();

                if ($categories->isEmpty()) {
                    $lines[] = 'categories: []';

                    continue;
                }

                $lines[] = 'categories:';
                $categories->each(fn (string $category) => $lines[] = "  - {$category}");

                continue;
            }

            if (is_bool($value)) {
                $lines[] = "{$key}: " . ($value ? 'true' : 'false');

                continue;
            }

            $lines[] = "{$key}: {$this->toYamlString((string) $value)}";
        }

        $trimmedContent = ltrim($content, "\r\n");

        return "---\n" . implode("\n", $lines) . "\n---\n" . $trimmedContent;
    }

    public function serializePost(Post $post) : string
    {
        $post->loadMissing(['user', 'categories']);

        /** @var Collection<int, string> $categories */
        $categories = $post->categories
            ->pluck('slug')
            ->filter()
            ->map(fn (string $slug) => trim($slug))
            ->values();

        return $this->serialize([
            'title' => $post->title,
            'slug' => $post->slug,
            'author' => $post->user->slug,
            'categories' => $categories->all(),
            'description' => $post->description,
            'serp_title' => $post->serp_title,
            'serp_description' => $post->serp_description,
            'canonical_url' => $post->canonical_url,
            'published_at' => $post->published_at?->toDateTimeString(),
            'modified_at' => $post->modified_at?->toDateTimeString(),
            'image_path' => $post->image_path,
            'image_disk' => $post->image_disk,
            'is_commercial' => $post->is_commercial,
            'sponsored_at' => $post->sponsored_at?->toDateTimeString(),
        ], $post->content);
    }

    protected function toYamlString(string $value) : string
    {
        return "'" . str_replace("'", "''", $value) . "'";
    }
}

