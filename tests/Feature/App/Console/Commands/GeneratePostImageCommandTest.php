<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use App\Markdown\PostMarkdownDocument;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Contracts\PostImageScreenshotter;
use Tests\Support\FakePostImageScreenshotter;

function generatePostImageMarkdownPath() : string
{
    return (string) config('blog.markdown.posts_path');
}

beforeEach(function () {
    $markdownPath = storage_path('framework/testing/markdown-generate-image-' . Str::uuid());

    File::deleteDirectory($markdownPath);
    File::ensureDirectoryExists($markdownPath);

    config()->set('blog.markdown.posts_path', $markdownPath);
    config()->set('blog.preview_base_url', 'https://blog-v5.test');

    Storage::fake('cloudflare-images');
});

afterEach(function () {
    File::deleteDirectory(generatePostImageMarkdownPath());
});

function generatePostImageFrontMatter(array $attributes) : string
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

function writeGeneratePostImagePost(
    string $basePath,
    string $slug,
    array $attributes,
    string $body = 'Markdown body',
    ?string $relativeDirectory = null,
) : string {
    $directory = $relativeDirectory
        ? $basePath . '/' . trim($relativeDirectory, '/')
        : $basePath;

    File::ensureDirectoryExists($directory);

    $path = $directory . "/{$slug}.md";

    File::put($path, generatePostImageFrontMatter($attributes) . $body);

    return $path;
}

it('generates and uploads a fallback image for a Markdown post without image fields', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel', 'name' => 'Laravel']);

    writeGeneratePostImagePost(generatePostImageMarkdownPath(), 'generated-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
        'title' => '"Generated post"',
        'slug' => 'generated-post',
        'author' => 'benjamincrozat',
        'description' => '"Generated summary"',
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

    $fakeScreenshotter = new FakePostImageScreenshotter;
    app()->instance(PostImageScreenshotter::class, $fakeScreenshotter);

    expect(Artisan::call('app:generate-post-image', [
        'post' => 'generated-post',
    ]))->toBe(0);

    Storage::disk('cloudflare-images')->assertExists('images/posts/generated/generated-post.png');

    $document = PostMarkdownDocument::fromMarkdown(
        File::get(generatePostImageMarkdownPath() . '/generated-post.md'),
        'generated-post.md',
    );

    $post = Post::query()->where('slug', 'generated-post')->firstOrFail();

    expect($fakeScreenshotter->captures)->toHaveCount(1)
        ->and($fakeScreenshotter->captures[0]['url'])->toBe('https://blog-v5.test/preview/posts/generated-post/image')
        ->and($document->imageDisk)->toBe('cloudflare-images')
        ->and($document->imagePath)->toBe('images/posts/generated/generated-post.png')
        ->and($post->image_disk)->toBe('cloudflare-images')
        ->and($post->image_path)->toBe('images/posts/generated/generated-post.png')
        ->and(Artisan::output())->toContain('Generated post image and uploaded it to Cloudflare Images.');
});

it('refuses to generate a fallback image when the post already has image metadata', function () {
    writeGeneratePostImagePost(generatePostImageMarkdownPath(), 'existing-image-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
        'title' => '"Existing image post"',
        'slug' => 'existing-image-post',
        'author' => 'benjamincrozat',
        'description' => '"Generated summary"',
        'categories' => ['laravel'],
        'published_at' => 'null',
        'modified_at' => 'null',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => false,
        'image_disk' => '"cloudflare-images"',
        'image_path' => '"images/posts/already-there.png"',
        'sponsored_at' => 'null',
    ]);

    $fakeScreenshotter = new FakePostImageScreenshotter;
    app()->instance(PostImageScreenshotter::class, $fakeScreenshotter);

    expect(Artisan::call('app:generate-post-image', [
        'post' => 'existing-image-post',
    ]))->toBe(1);

    expect($fakeScreenshotter->captures)->toBeEmpty()
        ->and(Artisan::output())->toContain('already has image_disk/image_path');
});

it('forces regeneration for a Markdown path input and overwrites the generated asset', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel', 'name' => 'Laravel']);

    writeGeneratePostImagePost(generatePostImageMarkdownPath(), 'forced-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FB0',
        'title' => '"Forced post"',
        'slug' => 'forced-post',
        'author' => 'benjamincrozat',
        'description' => '"Forced summary"',
        'categories' => ['laravel'],
        'published_at' => 'null',
        'modified_at' => 'null',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => false,
        'image_disk' => '"cloudflare-images"',
        'image_path' => '"images/posts/generated/forced-post.png"',
        'sponsored_at' => 'null',
    ], relativeDirectory: 'nested');

    Storage::disk('cloudflare-images')->put('images/posts/generated/forced-post.png', 'old-image');

    $fakeScreenshotter = new FakePostImageScreenshotter;
    app()->instance(PostImageScreenshotter::class, $fakeScreenshotter);

    expect(Artisan::call('app:generate-post-image', [
        'post' => 'nested/forced-post.md',
        '--force' => true,
    ]))->toBe(0);

    expect(Storage::disk('cloudflare-images')->get('images/posts/generated/forced-post.png'))
        ->not->toBe('old-image');

    $document = PostMarkdownDocument::fromMarkdown(
        File::get(generatePostImageMarkdownPath() . '/nested/forced-post.md'),
        'nested/forced-post.md',
    );

    expect($fakeScreenshotter->captures)->toHaveCount(1)
        ->and($document->imagePath)->toBe('images/posts/generated/forced-post.png');
});

it('falls back to APP_URL when no dedicated preview base URL is configured', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel', 'name' => 'Laravel']);

    config()->set('blog.preview_base_url', null);
    config()->set('app.url', 'http://127.0.0.1:8090');

    writeGeneratePostImagePost(generatePostImageMarkdownPath(), 'app-url-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FB1',
        'title' => '"APP URL post"',
        'slug' => 'app-url-post',
        'author' => 'benjamincrozat',
        'description' => '"Generated summary"',
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

    $fakeScreenshotter = new FakePostImageScreenshotter;
    app()->instance(PostImageScreenshotter::class, $fakeScreenshotter);

    expect(Artisan::call('app:generate-post-image', [
        'post' => 'app-url-post',
    ]))->toBe(0);

    expect($fakeScreenshotter->captures)->toHaveCount(1)
        ->and($fakeScreenshotter->captures[0]['url'])->toBe('http://127.0.0.1:8090/preview/posts/app-url-post/image');
});
