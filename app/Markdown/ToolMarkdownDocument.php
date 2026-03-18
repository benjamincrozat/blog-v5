<?php

namespace App\Markdown;

use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Enums\ToolPricingModel;
use App\Exceptions\ToolMarkdownException;

/**
 * Holds the canonical Markdown contract for a file-managed tool.
 */
class ToolMarkdownDocument
{
    /**
     * @param  array<int, string>  $categories
     */
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $description,
        public readonly string $websiteUrl,
        public readonly string $outboundUrl,
        public readonly ToolPricingModel $pricingModel,
        public readonly bool $hasFreePlan,
        public readonly bool $hasFreeTrial,
        public readonly bool $isOpenSource,
        public readonly array $categories,
        public readonly ?string $imageDisk,
        public readonly ?string $imagePath,
        public readonly ?string $reviewPostSlug,
        public readonly ?CarbonImmutable $publishedAt,
        public readonly string $body,
        public readonly string $relativePath,
    ) {}

    public static function fromMarkdown(string $markdown, string $relativePath) : self
    {
        $markdown = static::normalizeLineEndings($markdown);

        if (! preg_match('/\A---\n(?<frontmatter>.*?)\n---\n?(?<body>.*)\z/s', $markdown, $matches)) {
            throw ToolMarkdownException::forPath($relativePath, 'Missing a valid front matter block.');
        }

        /** @var array<string, mixed> $frontMatter */
        $frontMatter = static::parseFrontMatter($matches['frontmatter'], $relativePath);

        static::ensureRequiredKeys($frontMatter, $relativePath);

        /** @var array<int, string> $categories */
        $categories = $frontMatter['categories'];
        $slug = static::expectString($frontMatter, 'slug', $relativePath);

        if ('md' !== pathinfo($relativePath, PATHINFO_EXTENSION)) {
            throw ToolMarkdownException::forPath($relativePath, 'Source files must use the .md extension.');
        }

        if (pathinfo($relativePath, PATHINFO_FILENAME) !== $slug) {
            throw ToolMarkdownException::forPath($relativePath, "Filename must match slug [{$slug}].");
        }

        return new self(
            id: static::expectId($frontMatter, 'id', $relativePath),
            name: static::expectString($frontMatter, 'name', $relativePath),
            slug: $slug,
            description: static::expectString($frontMatter, 'description', $relativePath, allowBlank: true),
            websiteUrl: static::expectUrl($frontMatter, 'website_url', $relativePath),
            outboundUrl: static::expectUrl($frontMatter, 'outbound_url', $relativePath),
            pricingModel: static::expectPricingModel($frontMatter, 'pricing_model', $relativePath),
            hasFreePlan: static::expectBool($frontMatter, 'has_free_plan', $relativePath),
            hasFreeTrial: static::expectBool($frontMatter, 'has_free_trial', $relativePath),
            isOpenSource: static::expectBool($frontMatter, 'is_open_source', $relativePath),
            categories: static::expectCategories($categories, $relativePath),
            imageDisk: static::expectNullableString($frontMatter, 'image_disk', $relativePath),
            imagePath: static::expectNullableString($frontMatter, 'image_path', $relativePath),
            reviewPostSlug: static::expectNullableString($frontMatter, 'review_post_slug', $relativePath),
            publishedAt: static::expectNullableDate($frontMatter, 'published_at', $relativePath),
            body: $matches['body'],
            relativePath: static::normalizeRelativePath($relativePath),
        );
    }

    public function hash() : string
    {
        return hash('sha256', static::normalizeLineEndings($this->toMarkdown()));
    }

    public function toMarkdown() : string
    {
        $lines = [
            'id: ' . static::formatScalar($this->id),
            'name: ' . static::formatScalar($this->name),
            'slug: ' . static::formatScalar($this->slug),
            'description: ' . static::formatScalar($this->description),
            'website_url: ' . static::formatScalar($this->websiteUrl),
            'outbound_url: ' . static::formatScalar($this->outboundUrl),
            'pricing_model: ' . static::formatScalar($this->pricingModel->value),
            'has_free_plan: ' . static::formatBool($this->hasFreePlan),
            'has_free_trial: ' . static::formatBool($this->hasFreeTrial),
            'is_open_source: ' . static::formatBool($this->isOpenSource),
            'categories:',
            ...collect($this->categories)
                ->map(fn (string $category) => '  - ' . static::formatScalar($category))
                ->all(),
            'image_disk: ' . static::formatScalar($this->imageDisk),
            'image_path: ' . static::formatScalar($this->imagePath),
            'review_post_slug: ' . static::formatScalar($this->reviewPostSlug),
            'published_at: ' . static::formatDate($this->publishedAt),
        ];

        return "---\n" . implode("\n", $lines) . "\n---\n{$this->body}";
    }

    /**
     * @return array<string, mixed>
     */
    protected static function parseFrontMatter(string $frontMatter, string $relativePath) : array
    {
        $allowedKeys = [
            'id',
            'name',
            'slug',
            'description',
            'website_url',
            'outbound_url',
            'pricing_model',
            'has_free_plan',
            'has_free_trial',
            'is_open_source',
            'categories',
            'image_disk',
            'image_path',
            'review_post_slug',
            'published_at',
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
                    throw ToolMarkdownException::forPath(
                        $relativePath,
                        'Invalid quoted string in front matter on line ' . ($lineNumber + 1) . '.'
                    );
                }

                $data[$currentListKey] = $existing;

                continue;
            }

            $currentListKey = null;

            if (! preg_match('/^(?<key>[a-z_]+):(?:\s(?<value>.*)|(?<empty>\s*))$/', $line, $matches)) {
                throw ToolMarkdownException::forPath(
                    $relativePath,
                    'Invalid front matter syntax on line ' . ($lineNumber + 1) . '.'
                );
            }

            $key = $matches['key'];

            if (! in_array($key, $allowedKeys, true)) {
                throw ToolMarkdownException::forPath($relativePath, "Unknown front matter key [{$key}].");
            }

            if ('categories' === $key && '' === trim((string) ($matches['value'] ?? ''))) {
                $data[$key] = [];
                $currentListKey = $key;

                continue;
            }

            try {
                $data[$key] = static::parseScalar(trim((string) ($matches['value'] ?? '')));
            } catch (\JsonException) {
                throw ToolMarkdownException::forPath(
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
        $requiredKeys = [
            'id',
            'name',
            'slug',
            'description',
            'website_url',
            'outbound_url',
            'pricing_model',
            'has_free_plan',
            'has_free_trial',
            'is_open_source',
            'categories',
        ];

        $missing = collect($requiredKeys)
            ->reject(fn (string $key) => Arr::has($frontMatter, $key))
            ->values()
            ->all();

        if ([] === $missing) {
            return;
        }

        throw ToolMarkdownException::forPath(
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
            throw ToolMarkdownException::forPath(
                $relativePath,
                "Front matter key [{$key}] must be " . ($allowBlank ? 'a string.' : 'a non-empty string.')
            );
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function expectId(array $frontMatter, string $key, string $relativePath) : string
    {
        $value = static::expectString($frontMatter, $key, $relativePath);

        if (! Str::isUlid($value)) {
            throw ToolMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be a valid ULID.");
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
            throw ToolMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be a string or null.");
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function expectUrl(array $frontMatter, string $key, string $relativePath) : string
    {
        $value = static::expectString($frontMatter, $key, $relativePath);

        if (! filter_var($value, FILTER_VALIDATE_URL)) {
            throw ToolMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be a valid URL.");
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function expectPricingModel(array $frontMatter, string $key, string $relativePath) : ToolPricingModel
    {
        $value = static::expectString($frontMatter, $key, $relativePath);

        return ToolPricingModel::tryFrom($value)
            ?? throw ToolMarkdownException::forPath(
                $relativePath,
                'Front matter key [pricing_model] must be one of: ' . implode(', ', ToolPricingModel::values()) . '.'
            );
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    protected static function expectBool(array $frontMatter, string $key, string $relativePath) : bool
    {
        $value = $frontMatter[$key] ?? null;

        if (! is_bool($value)) {
            throw ToolMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be a boolean.");
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
            throw ToolMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be an ISO-8601 datetime or null.");
        }

        if (! preg_match('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}(?:\.\d+)?(?:Z|[+-]\d{2}:\d{2})$/', $value)) {
            throw ToolMarkdownException::forPath($relativePath, "Front matter key [{$key}] must be an ISO-8601 datetime or null.");
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable) {
            throw ToolMarkdownException::forPath(
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
            throw ToolMarkdownException::forPath($relativePath, 'Front matter key [categories] must be a YAML list.');
        }

        if (collect($value)->contains(fn (mixed $category) => ! is_string($category) || blank($category))) {
            throw ToolMarkdownException::forPath($relativePath, 'Front matter key [categories] must contain only non-empty strings.');
        }

        /** @var array<int, string> $categories */
        $categories = collect($value)
            ->values()
            ->all();

        if (count($categories) !== count(array_unique($categories))) {
            throw ToolMarkdownException::forPath($relativePath, 'Front matter key [categories] cannot contain duplicates.');
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
