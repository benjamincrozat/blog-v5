<?php

namespace App\Markdown;

use App\Models\Post;
use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Exceptions\PostMarkdownException;

/**
 * Holds the canonical Markdown contract for a file-managed post.
 */
class PostMarkdownDocument
{
    /**
     * @param  array<int, string>  $categories
     */
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $slug,
        public readonly string $author,
        public readonly string $description,
        public readonly array $categories,
        public readonly ?CarbonImmutable $publishedAt,
        public readonly ?CarbonImmutable $modifiedAt,
        public readonly ?string $serpTitle,
        public readonly ?string $serpDescription,
        public readonly ?string $canonicalUrl,
        public readonly bool $isCommercial,
        public readonly ?string $imageDisk,
        public readonly ?string $imagePath,
        public readonly ?CarbonImmutable $sponsoredAt,
        public readonly string $body,
        public readonly string $relativePath,
    ) {}

    public static function fromMarkdown(string $markdown, string $relativePath) : self
    {
        $markdown = static::normalizeLineEndings($markdown);

        if (! preg_match('/\A---\n(?<frontmatter>.*?)\n---\n?(?<body>.*)\z/s', $markdown, $matches)) {
            throw PostMarkdownException::forPath($relativePath, 'Missing a valid front matter block.');
        }

        /** @var array<string, mixed> $frontMatter */
        $frontMatter = static::parseFrontMatter($matches['frontmatter'], $relativePath);

        static::ensureRequiredKeys($frontMatter, $relativePath);

        /** @var array<int, string> $categories */
        $categories = $frontMatter['categories'];
        $slug = static::expectString($frontMatter, 'slug', $relativePath);

        if ('md' !== pathinfo($relativePath, PATHINFO_EXTENSION)) {
            throw PostMarkdownException::forPath($relativePath, 'Source files must use the .md extension.');
        }

        if (pathinfo($relativePath, PATHINFO_FILENAME) !== $slug) {
            throw PostMarkdownException::forPath($relativePath, "Filename must match slug [{$slug}].");
        }

        PostContentImageUrls::ensureCloudflare($matches['body'], $relativePath);

        return new self(
            id: static::expectString($frontMatter, 'id', $relativePath),
            title: static::expectString($frontMatter, 'title', $relativePath),
            slug: $slug,
            author: static::expectString($frontMatter, 'author', $relativePath),
            description: static::expectString($frontMatter, 'description', $relativePath, allowBlank: true),
            categories: static::expectCategories($categories, $relativePath),
            publishedAt: static::expectNullableDate($frontMatter, 'published_at', $relativePath),
            modifiedAt: static::expectNullableDate($frontMatter, 'modified_at', $relativePath),
            serpTitle: static::expectNullableString($frontMatter, 'serp_title', $relativePath),
            serpDescription: static::expectNullableString($frontMatter, 'serp_description', $relativePath),
            canonicalUrl: static::expectNullableString($frontMatter, 'canonical_url', $relativePath),
            isCommercial: static::expectBool($frontMatter, 'is_commercial', $relativePath),
            imageDisk: static::expectNullableString($frontMatter, 'image_disk', $relativePath),
            imagePath: static::expectNullableString($frontMatter, 'image_path', $relativePath),
            sponsoredAt: static::expectNullableDate($frontMatter, 'sponsored_at', $relativePath),
            body: $matches['body'],
            relativePath: static::normalizeRelativePath($relativePath),
        );
    }

    public static function fromPost(Post $post) : self
    {
        $post->loadMissing([
            'user:id,github_login',
            'categories:id,slug',
        ]);

        $author = $post->user?->github_login;

        if (! filled($author)) {
            throw PostMarkdownException::forPath(
                $post->slug . '.md',
                "Post [{$post->slug}] cannot be exported without an author github_login."
            );
        }

        return new self(
            id: $post->source_uuid ?: (string) Str::ulid(),
            title: $post->title,
            slug: $post->slug,
            author: $author,
            description: $post->description ?? '',
            categories: $post->categories->pluck('slug')->filter()->values()->all(),
            publishedAt: $post->published_at?->toImmutable(),
            modifiedAt: $post->modified_at?->toImmutable(),
            serpTitle: $post->serp_title,
            serpDescription: $post->serp_description,
            canonicalUrl: $post->canonical_url,
            isCommercial: (bool) $post->is_commercial,
            imageDisk: $post->image_disk,
            imagePath: $post->image_path,
            sponsoredAt: $post->sponsored_at?->toImmutable(),
            body: static::normalizeLineEndings($post->content),
            relativePath: static::buildRelativePath($post->source_path, $post->slug),
        );
    }

    public function toMarkdown() : string
    {
        $lines = [
            'id: ' . static::formatScalar($this->id),
            'title: ' . static::formatScalar($this->title),
            'slug: ' . static::formatScalar($this->slug),
            'author: ' . static::formatScalar($this->author),
            'description: ' . static::formatScalar($this->description),
            'categories:',
            ...collect($this->categories)
                ->map(fn (string $category) => '  - ' . static::formatScalar($category))
                ->all(),
            'published_at: ' . static::formatDate($this->publishedAt),
            'modified_at: ' . static::formatDate($this->modifiedAt),
            'serp_title: ' . static::formatScalar($this->serpTitle),
            'serp_description: ' . static::formatScalar($this->serpDescription),
            'canonical_url: ' . static::formatScalar($this->canonicalUrl),
            'is_commercial: ' . static::formatBool($this->isCommercial),
            'image_disk: ' . static::formatScalar($this->imageDisk),
            'image_path: ' . static::formatScalar($this->imagePath),
            'sponsored_at: ' . static::formatDate($this->sponsoredAt),
        ];

        return "---\n" . implode("\n", $lines) . "\n---\n{$this->body}";
    }

    public function withImage(?string $imageDisk, ?string $imagePath) : self
    {
        return new self(
            id: $this->id,
            title: $this->title,
            slug: $this->slug,
            author: $this->author,
            description: $this->description,
            categories: $this->categories,
            publishedAt: $this->publishedAt,
            modifiedAt: $this->modifiedAt,
            serpTitle: $this->serpTitle,
            serpDescription: $this->serpDescription,
            canonicalUrl: $this->canonicalUrl,
            isCommercial: $this->isCommercial,
            imageDisk: $imageDisk,
            imagePath: $imagePath,
            sponsoredAt: $this->sponsoredAt,
            body: $this->body,
            relativePath: $this->relativePath,
        );
    }

    public function hash() : string
    {
        return hash('sha256', static::normalizeLineEndings($this->toMarkdown()));
    }

    protected static function buildRelativePath(?string $sourcePath, string $slug) : string
    {
        $directory = collect(explode('/', static::normalizeRelativePath($sourcePath ?? '')))
            ->filter()
            ->slice(0, -1)
            ->implode('/');

        return ltrim(($directory ? "{$directory}/" : '') . "{$slug}.md", '/');
    }

    /**
     * @return array<string, mixed>
     */
    protected static function parseFrontMatter(string $frontMatter, string $relativePath) : array
    {
        $allowedKeys = [
            'id',
            'title',
            'slug',
            'author',
            'description',
            'categories',
            'published_at',
            'modified_at',
            'serp_title',
            'serp_description',
            'canonical_url',
            'is_commercial',
            'image_disk',
            'image_path',
            'sponsored_at',
        ];

        $data = [];
        $currentListKey = null;

        foreach (explode("\n", $frontMatter) as $lineNumber => $line) {
            if ('' === $line) {
                continue;
            }

            if ($currentListKey && preg_match('/^\s*-\s*(?<value>.+)$/', $line, $matches)) {
                /** @var array<int, mixed> $existing */
                $existing = $data[$currentListKey];
                try {
                    $existing[] = static::parseScalar(trim($matches['value']));
                } catch (\JsonException) {
                    throw PostMarkdownException::forPath(
                        $relativePath,
                        'Invalid quoted string in front matter on line ' . ($lineNumber + 1) . '.'
                    );
                }
                $data[$currentListKey] = $existing;

                continue;
            }

            $currentListKey = null;

            if (! preg_match('/^(?<key>[a-z_]+):(?:\s(?<value>.*)|(?<empty>\s*))$/', $line, $matches)) {
                throw PostMarkdownException::forPath(
                    $relativePath,
                    'Invalid front matter syntax on line ' . ($lineNumber + 1) . '.'
                );
            }

            $key = $matches['key'];

            if (! in_array($key, $allowedKeys, true)) {
                throw PostMarkdownException::forPath($relativePath, "Unknown front matter key [{$key}].");
            }

            if ('categories' === $key && '' === trim((string) ($matches['value'] ?? ''))) {
                $data[$key] = [];
                $currentListKey = $key;

                continue;
            }

            try {
                $data[$key] = static::parseScalar(trim((string) ($matches['value'] ?? '')));
            } catch (\JsonException) {
                throw PostMarkdownException::forPath(
                    $relativePath,
                    'Invalid quoted string for front matter key [' . $key . '].'
                );
            }
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function ensureRequiredKeys(array $frontMatter, string $relativePath) : void
    {
        $requiredKeys = ['id', 'title', 'slug', 'author', 'description', 'categories'];

        $missing = collect($requiredKeys)
            ->reject(fn (string $key) => Arr::has($frontMatter, $key))
            ->values()
            ->all();

        if ([] === $missing) {
            return;
        }

        throw PostMarkdownException::forPath(
            $relativePath,
            'Missing required front matter keys: ' . implode(', ', $missing) . '.'
        );
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function expectString(array $frontMatter, string $key, string $relativePath, bool $allowBlank = false) : string
    {
        $value = $frontMatter[$key] ?? null;

        if (! is_string($value) || (! $allowBlank && blank($value))) {
            throw PostMarkdownException::forPath(
                $relativePath,
                "Front matter key [{$key}] must be " . ($allowBlank ? 'a string.' : 'a non-empty string.')
            );
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function expectNullableString(array $frontMatter, string $key, string $relativePath) : ?string
    {
        $value = $frontMatter[$key] ?? null;

        if (null === $value) {
            return null;
        }

        if (! is_string($value)) {
            throw PostMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be a string or null.");
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function expectBool(array $frontMatter, string $key, string $relativePath) : bool
    {
        $value = $frontMatter[$key] ?? null;

        if (! is_bool($value)) {
            throw PostMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be a boolean.");
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function expectNullableDate(array $frontMatter, string $key, string $relativePath) : ?CarbonImmutable
    {
        $value = $frontMatter[$key] ?? null;

        if (null === $value) {
            return null;
        }

        if (! is_string($value) || blank($value)) {
            throw PostMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be an ISO-8601 datetime or null.");
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable) {
            throw PostMarkdownException::forPath(
                $relativePath,
                "Front matter key [{$key}] must be a valid datetime string."
            );
        }
    }

    /**
     * @return array<int, string>
     */
    protected static function expectCategories(mixed $value, string $relativePath) : array
    {
        if (! is_array($value)) {
            throw PostMarkdownException::forPath($relativePath, 'Front matter key [categories] must be a YAML list.');
        }

        if (collect($value)->contains(fn (mixed $category) => ! is_string($category) || blank($category))) {
            throw PostMarkdownException::forPath($relativePath, 'Front matter key [categories] must contain only non-empty strings.');
        }

        /** @var array<int, string> $categories */
        $categories = collect($value)
            ->values()
            ->all();

        if (count($categories) !== count(array_unique($categories))) {
            throw PostMarkdownException::forPath($relativePath, 'Front matter key [categories] cannot contain duplicates.');
        }

        return $categories;
    }

    protected static function parseScalar(string $value) : string|bool|null
    {
        if ('null' === $value) {
            return null;
        }

        if ('true' === $value) {
            return true;
        }

        if ('false' === $value) {
            return false;
        }

        if (Str::startsWith($value, '"') && Str::endsWith($value, '"')) {
            $decoded = json_decode($value, true, 512, JSON_THROW_ON_ERROR);

            return is_string($decoded) ? $decoded : $value;
        }

        if (Str::startsWith($value, "'") && Str::endsWith($value, "'")) {
            return str_replace("''", "'", substr($value, 1, -1));
        }

        return $value;
    }

    protected static function formatScalar(?string $value) : string
    {
        if (null === $value) {
            return 'null';
        }

        return json_encode($value, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    protected static function formatDate(?CarbonImmutable $value) : string
    {
        return $value?->toIso8601String() ?? 'null';
    }

    protected static function formatBool(bool $value) : string
    {
        return $value ? 'true' : 'false';
    }

    protected static function normalizeLineEndings(string $markdown) : string
    {
        return str_replace(["\r\n", "\r"], "\n", $markdown);
    }

    protected static function normalizeRelativePath(string $relativePath) : string
    {
        return ltrim(str_replace('\\', '/', $relativePath), '/');
    }
}
