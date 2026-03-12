<?php

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use App\Markdown\PostMarkdownDocument;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

function blogUploadMarkdownPath() : string
{
    return (string) config('blog.markdown.posts_path');
}

beforeEach(function () {
    $markdownPath = storage_path('framework/testing/markdown-upload-' . Str::uuid());

    File::deleteDirectory($markdownPath);
    File::ensureDirectoryExists($markdownPath);

    config()->set('blog.markdown.posts_path', $markdownPath);

    Storage::fake('cloudflare-images');
});

afterEach(function () {
    File::deleteDirectory(blogUploadMarkdownPath());
});

it('uploads an inline article image to Cloudflare Images', function () {
    $image = UploadedFile::fake()->image('dashboard-shot.png', 1200, 630);

    Artisan::call('blog:upload-image', [
        'source' => $image->getPathname(),
        '--path' => 'images/posts/dashboard-shot.png',
        '--alt' => 'Dashboard shot',
    ]);

    Storage::disk('cloudflare-images')->assertExists('images/posts/dashboard-shot.png');

    expect(Artisan::output())
        ->toContain('Image disk: cloudflare-images')
        ->toContain('Image path: images/posts/dashboard-shot.png')
        ->toContain('Markdown: ![Dashboard shot](')
        ->toContain('Use the URL above for inline article images');
});

it('updates the Markdown hero image fields after uploading to Cloudflare Images', function () {
    $image = UploadedFile::fake()->image('cover.png', 1200, 630);

    writeMarkdownPostForUpload(blogUploadMarkdownPath(), 'cloudflare-post', [
        'id' => '"01ARZ3NDEKTSV4RRFFQ69G5FAV"',
        'title' => '"Cloudflare post"',
        'slug' => 'cloudflare-post',
        'author' => 'benjamincrozat',
        'description' => '"Cloudflare summary"',
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
    ], 'Body content');

    Artisan::call('blog:upload-image', [
        'source' => $image->getPathname(),
        '--path' => 'images/posts/cloudflare-post-cover.png',
        '--markdown' => 'cloudflare-post.md',
    ]);

    Storage::disk('cloudflare-images')->assertExists('images/posts/cloudflare-post-cover.png');

    $document = PostMarkdownDocument::fromMarkdown(
        File::get(blogUploadMarkdownPath() . '/cloudflare-post.md'),
        'cloudflare-post.md',
    );

    expect($document->imageDisk)->toBe('cloudflare-images')
        ->and($document->imagePath)->toBe('images/posts/cloudflare-post-cover.png')
        ->and($document->body)->toBe('Body content')
        ->and(Artisan::output())->toContain('Run php artisan blog:sync to persist the new image metadata.');
});

function uploadFrontMatter(array $attributes) : string
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

function writeMarkdownPostForUpload(string $basePath, string $slug, array $attributes, string $body = 'Markdown body') : void
{
    File::put(
        $basePath . "/{$slug}.md",
        uploadFrontMatter($attributes) . $body
    );
}
