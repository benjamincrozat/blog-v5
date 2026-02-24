<?php

namespace App\MarkdownSync;

use Throwable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Date;
use App\MarkdownSync\Exceptions\InvalidPostMarkdownException;

/**
 * Parses and validates post markdown files with strict YAML frontmatter.
 */
class PostMarkdownParser
{
    /**
     * @var array<int, string>
     */
    protected const ALLOWED_KEYS = [
        'title',
        'slug',
        'author',
        'categories',
        'description',
        'serp_title',
        'serp_description',
        'canonical_url',
        'published_at',
        'modified_at',
        'image_path',
        'image_disk',
        'is_commercial',
        'sponsored_at',
    ];

    public function parseFile(string $path) : ParsedPostMarkdown
    {
        $contents = file_get_contents($path);

        if (false === $contents) {
            throw new InvalidPostMarkdownException([
                "Unable to read markdown file: {$path}",
            ]);
        }

        return $this->parse($path, $contents);
    }

    public function parse(string $path, string $contents) : ParsedPostMarkdown
    {
        $errors = [];

        if (! preg_match('/^---\R(.*?)\R---\R?(.*)\z/s', $contents, $matches)) {
            throw new InvalidPostMarkdownException([
                "Invalid frontmatter format in {$path}.",
                'Expected a leading "---" block with YAML frontmatter.',
            ]);
        }

        $frontMatter = $this->parseFrontMatter($matches[1], $errors);

        $unknownKeys = array_values(array_diff(array_keys($frontMatter), self::ALLOWED_KEYS));
        if ([] !== $unknownKeys) {
            $errors[] = 'Unknown frontmatter key(s): ' . implode(', ', $unknownKeys) . '.';
        }

        $title = $this->requireString('title', $frontMatter, $errors);
        $slug = $this->requireSlug('slug', $frontMatter, $errors);
        $author = $this->requireSlug('author', $frontMatter, $errors);

        $categories = $frontMatter['categories'] ?? null;

        if (! is_array($categories)) {
            $errors[] = 'The "categories" key must be a YAML list (or []).';
            $categories = [];
        }

        $normalizedCategories = [];
        foreach ($categories as $index => $category) {
            if (! is_string($category) || '' === trim($category)) {
                $errors[] = "Category entry #{$index} must be a non-empty string.";

                continue;
            }

            $normalized = Str::slug($category);
            if ('' === $normalized) {
                $errors[] = "Category entry #{$index} cannot be normalized into a slug.";

                continue;
            }

            $normalizedCategories[] = $normalized;
        }
        $normalizedCategories = array_values(array_unique($normalizedCategories));

        $filenameSlug = pathinfo($path, PATHINFO_FILENAME);
        if (filled($slug) && $slug !== $filenameSlug) {
            $errors[] = "Slug \"{$slug}\" must match filename \"{$filenameSlug}.md\".";
        }

        $content = $matches[2];
        if (blank(trim($content))) {
            $errors[] = 'Markdown body cannot be empty.';
        }

        $description = $this->optionalString('description', $frontMatter, $errors);
        $serpTitle = $this->optionalString('serp_title', $frontMatter, $errors);
        $serpDescription = $this->optionalString('serp_description', $frontMatter, $errors);
        $canonicalUrl = $this->optionalString('canonical_url', $frontMatter, $errors);
        $imagePath = $this->optionalString('image_path', $frontMatter, $errors);
        $imageDisk = $this->optionalString('image_disk', $frontMatter, $errors);

        if (filled($canonicalUrl) && ! filter_var($canonicalUrl, FILTER_VALIDATE_URL)) {
            $errors[] = 'The "canonical_url" key must be a valid URL.';
        }

        $publishedAt = $this->optionalDate('published_at', $frontMatter, $errors);
        $modifiedAt = $this->optionalDate('modified_at', $frontMatter, $errors);
        $sponsoredAt = $this->optionalDate('sponsored_at', $frontMatter, $errors);

        $isCommercial = false;
        if (array_key_exists('is_commercial', $frontMatter)) {
            if (! is_bool($frontMatter['is_commercial'])) {
                $errors[] = 'The "is_commercial" key must be a boolean.';
            } else {
                $isCommercial = $frontMatter['is_commercial'];
            }
        }

        if (filled($imagePath) && blank($imageDisk)) {
            $imageDisk = 'cloudflare-images';
        }

        if (blank($imagePath)) {
            $imagePath = null;
            $imageDisk = null;
        }

        if ([] !== $errors) {
            throw new InvalidPostMarkdownException(
                array_map(
                    fn (string $error) => "{$path}: {$error}",
                    $errors,
                )
            );
        }

        return new ParsedPostMarkdown(
            path: $path,
            title: $title,
            slug: $slug,
            author: $author,
            categories: $normalizedCategories,
            content: $content,
            description: $description,
            serpTitle: $serpTitle,
            serpDescription: $serpDescription,
            canonicalUrl: $canonicalUrl,
            publishedAt: $publishedAt,
            modifiedAt: $modifiedAt,
            imagePath: $imagePath,
            imageDisk: $imageDisk,
            isCommercial: $isCommercial,
            sponsoredAt: $sponsoredAt,
        );
    }

    /**
     * @param  array<int, string>  $errors
     * @return array<string, mixed>
     */
    protected function parseFrontMatter(string $rawFrontMatter, array &$errors) : array
    {
        /** @var array<int, string> $lines */
        $lines = preg_split('/\R/', $rawFrontMatter) ?: [];
        $frontMatter = [];

        for ($index = 0; $index < count($lines); $index++) {
            $line = rtrim($lines[$index]);

            if ('' === trim($line)) {
                continue;
            }

            if (! preg_match('/^([a-z_]+):(.*)$/', $line, $matches)) {
                $errors[] = "Invalid frontmatter line: {$line}";

                continue;
            }

            $key = $matches[1];
            $rawValue = trim($matches[2]);

            if (array_key_exists($key, $frontMatter)) {
                $errors[] = "Duplicate frontmatter key \"{$key}\".";

                continue;
            }

            if ('categories' === $key) {
                if ('[]' === $rawValue) {
                    $frontMatter[$key] = [];

                    continue;
                }

                if ('' !== $rawValue) {
                    $errors[] = 'The "categories" key must use YAML list syntax.';

                    continue;
                }

                $categories = [];
                $cursor = $index + 1;

                while ($cursor < count($lines)) {
                    $categoryLine = rtrim($lines[$cursor]);

                    if ('' === trim($categoryLine)) {
                        $cursor++;

                        continue;
                    }

                    if (! preg_match('/^\s*-\s*(.+)\s*$/', $categoryLine, $categoryMatches)) {
                        break;
                    }

                    $categories[] = $this->parseScalar(trim($categoryMatches[1]));
                    $cursor++;
                }

                $index = $cursor - 1;
                $frontMatter[$key] = $categories;

                continue;
            }

            $frontMatter[$key] = '' === $rawValue ? null : $this->parseScalar($rawValue);
        }

        return $frontMatter;
    }

    protected function parseScalar(string $value) : mixed
    {
        if ('true' === strtolower($value)) {
            return true;
        }

        if ('false' === strtolower($value)) {
            return false;
        }

        if ('null' === strtolower($value)) {
            return null;
        }

        if (preg_match('/^\'(.*)\'$/s', $value, $matches)) {
            return str_replace("''", "'", $matches[1]);
        }

        if (preg_match('/^"(.*)"$/s', $value, $matches)) {
            return stripcslashes($matches[1]);
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     * @param  array<int, string>  $errors
     */
    protected function requireString(string $key, array $frontMatter, array &$errors) : string
    {
        $value = $frontMatter[$key] ?? null;

        if (! is_string($value) || '' === trim($value)) {
            $errors[] = "The \"{$key}\" key is required and must be a non-empty string.";

            return '';
        }

        return trim($value);
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     * @param  array<int, string>  $errors
     */
    protected function requireSlug(string $key, array $frontMatter, array &$errors) : string
    {
        $value = $this->requireString($key, $frontMatter, $errors);

        if (filled($value) && Str::slug($value) !== $value) {
            $errors[] = "The \"{$key}\" key must be a normalized slug.";
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     * @param  array<int, string>  $errors
     */
    protected function optionalString(string $key, array $frontMatter, array &$errors) : ?string
    {
        if (! array_key_exists($key, $frontMatter) || null === $frontMatter[$key]) {
            return null;
        }

        if (! is_string($frontMatter[$key])) {
            $errors[] = "The \"{$key}\" key must be a string when provided.";

            return null;
        }

        $value = trim($frontMatter[$key]);

        return '' === $value ? null : $value;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     * @param  array<int, string>  $errors
     */
    protected function optionalDate(string $key, array $frontMatter, array &$errors) : ?\Carbon\CarbonImmutable
    {
        if (! array_key_exists($key, $frontMatter) || null === $frontMatter[$key]) {
            return null;
        }

        if (! is_string($frontMatter[$key])) {
            $errors[] = "The \"{$key}\" key must be a date string when provided.";

            return null;
        }

        try {
            return Date::parse($frontMatter[$key])->toImmutable();
        } catch (Throwable) {
            $errors[] = "The \"{$key}\" key must be a valid date string.";

            return null;
        }
    }
}

