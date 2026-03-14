<?php

use Illuminate\Support\Str;

use function Pest\Laravel\get;

use Illuminate\Support\Facades\File;

function imagePreviewMarkdownPath() : string
{
    return (string) config('blog.markdown.posts_path');
}

beforeEach(function () {
    $markdownPath = storage_path('framework/testing/markdown-image-preview-' . Str::uuid());

    File::deleteDirectory($markdownPath);
    File::ensureDirectoryExists($markdownPath);

    config()->set('blog.markdown.posts_path', $markdownPath);
});

afterEach(function () {
    File::deleteDirectory(imagePreviewMarkdownPath());
});

function imagePreviewFrontMatter(array $attributes) : string
{
    $lines = [];

    foreach ($attributes as $key => $value) {
        if ('categories' === $key) {
            $lines[] = 'categories:';

            foreach ($value as $category) {
                $lines[] = "  - {$category}";
            }

            continue;
        }

        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (null === $value) {
            $value = 'null';
        }

        $lines[] = "{$key}: {$value}";
    }

    return "---\n" . implode("\n", $lines) . "\n---\n";
}

function writeImagePreviewPost(string $basePath, string $slug, array $attributes, string $body = 'Markdown body') : void
{
    File::put(
        $basePath . "/{$slug}.md",
        imagePreviewFrontMatter($attributes) . $body,
    );
}

it('renders the standalone post image preview with crawler guards', function () {
    writeImagePreviewPost(imagePreviewMarkdownPath(), 'preview-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
        'title' => '"Preview post title"',
        'slug' => 'preview-post',
        'author' => 'benjamincrozat',
        'description' => '"Preview summary"',
        'categories' => ['laravel'],
        'published_at' => 'null',
        'modified_at' => 'null',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => false,
        'image_disk' => 'null',
        'image_path' => 'null',
        'sponsored_at' => 'null',
    ]);

    get(route('posts.image-preview', ['slug' => 'preview-post']))
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow, noimageindex')
        ->assertSee('<meta name="robots" content="noindex, nofollow, noimageindex" />', escape: false)
        ->assertSee('https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4', escape: false)
        ->assertSee('Preview post title')
        ->assertSee('<svg', escape: false);
});
