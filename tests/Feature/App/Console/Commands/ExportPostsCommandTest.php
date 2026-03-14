<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Exceptions\PostMarkdownException;

function exportPostsMarkdownPath() : string
{
    return (string) config('blog.markdown.posts_path');
}

beforeEach(function () {
    $markdownPath = storage_path('framework/testing/markdown-export-' . Str::uuid());

    File::deleteDirectory($markdownPath);
    File::ensureDirectoryExists($markdownPath);

    config()->set('blog.markdown.posts_path', $markdownPath);
});

afterEach(function () {
    File::deleteDirectory(exportPostsMarkdownPath());
});

it('exports canonical markdown files from the local database', function () {
    $author = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $categories = Category::factory(2)->create();

    $post = Post::factory()
        ->for($author)
        ->create([
            'title' => 'Export me',
            'slug' => 'export-me',
            'content' => "## Body\n\nExported content.",
            'description' => 'Export summary',
            'serp_title' => 'Export SERP',
            'serp_description' => 'Export SERP description',
            'canonical_url' => 'https://example.com/export-me',
            'is_commercial' => false,
            'published_at' => now()->startOfMinute(),
            'modified_at' => now()->subDay()->startOfMinute(),
            'image_disk' => 'public',
            'image_path' => 'images/posts/export-me.jpg',
            'sponsored_at' => now()->subHours(2)->startOfMinute(),
        ]);

    $post->categories()->sync($categories->pluck('id'));

    Artisan::call('app:export-posts');

    expect(Artisan::output())->toContain('Exported 1 posts');

    $filePath = exportPostsMarkdownPath() . '/export-me.md';

    expect(File::exists($filePath))->toBeTrue();

    $contents = File::get($filePath);

    expect($contents)->toContain('id: "')
        ->and($contents)->toContain('title: "Export me"')
        ->and($contents)->toContain('author: "benjamincrozat"')
        ->and($contents)->toContain('categories:')
        ->and($contents)->toContain('published_at: ')
        ->and($contents)->not->toContain('# Export me')
        ->and($contents)->toEndWith("## Body\n\nExported content.");

    expect($post->refresh()->source_uuid)->not->toBeNull()
        ->and($post->source_path)->toBe('export-me.md')
        ->and($post->source_hash)->toBe(hash('sha256', $contents));
});

it('fails loudly when a requested slug does not exist', function () {
    expect(fn () => Artisan::call('app:export-posts', ['--slug' => ['missing-post']]))
        ->toThrow(PostMarkdownException::class, 'missing-post');
});
