<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Redirect;
use Illuminate\Support\Str;

use function Pest\Laravel\get;
use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\SyncPostsCommand;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseMissing;

function syncPostsMarkdownPath() : string
{
    return (string) config('blog.markdown.posts_path');
}

beforeEach(function () {
    $markdownPath = storage_path('framework/testing/markdown-sync-' . Str::uuid());

    File::deleteDirectory($markdownPath);
    File::ensureDirectoryExists($markdownPath);

    config()->set('blog.markdown.posts_path', $markdownPath);
});

afterEach(function () {
    File::deleteDirectory(syncPostsMarkdownPath());
});

function syncPostsFrontMatter(array $attributes) : string
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

function writeSyncPost(string $basePath, string $slug, array $attributes, string $body = 'Markdown body') : void
{
    File::put(
        $basePath . "/{$slug}.md",
        syncPostsFrontMatter($attributes) . $body
    );
}

it('creates a post from markdown', function () {
    $author = User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel', 'name' => 'Laravel']);
    Category::factory()->create(['slug' => 'testing', 'name' => 'Testing']);

    writeSyncPost(syncPostsMarkdownPath(), 'file-first-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
        'title' => '"File first post"',
        'slug' => 'file-first-post',
        'author' => 'benjamincrozat',
        'description' => '"File first summary"',
        'categories' => ['laravel', 'testing'],
        'published_at' => '"2026-03-11T09:00:00+01:00"',
        'modified_at' => '"2026-03-11T12:00:00+01:00"',
        'serp_title' => '"File first SERP"',
        'serp_description' => '"File first SERP description"',
        'canonical_url' => '"https://example.com/file-first-post"',
        'is_commercial' => false,
        'image_disk' => '"public"',
        'image_path' => '"images/posts/file-first-post.jpg"',
        'sponsored_at' => 'null',
    ], "## Intro\n\nBody content.");

    expect(Artisan::call('app:sync-posts', ['--directory' => syncPostsMarkdownPath()]))
        ->toBe(0);

    expect(Artisan::output())
        ->toContain('created=1');

    $post = Post::query()->where('source_uuid', '01ARZ3NDEKTSV4RRFFQ69G5FAV')->firstOrFail();

    expect($post->title)->toBe('File first post')
        ->and($post->user->is($author))->toBeTrue()
        ->and($post->categories->pluck('slug')->sort()->values()->all())->toBe(['laravel', 'testing'])
        ->and($post->content)->toBe("## Intro\n\nBody content.")
        ->and($post->source_path)->toBe('file-first-post.md')
        ->and($post->trashed())->toBeFalse();
});

it('updates an existing post by source id', function () {
    $author = User::factory()->create(['github_login' => 'benjamincrozat']);
    $oldCategory = Category::factory()->create(['slug' => 'laravel']);
    $newCategory = Category::factory()->create(['slug' => 'testing']);

    $post = Post::factory()
        ->for($author)
        ->create([
            'source_uuid' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'source_path' => 'original-post.md',
            'slug' => 'original-post',
            'title' => 'Original post',
            'description' => 'Original summary',
        ]);

    $post->categories()->sync([$oldCategory->id]);

    writeSyncPost(syncPostsMarkdownPath(), 'original-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
        'title' => '"Updated post"',
        'slug' => 'original-post',
        'author' => 'benjamincrozat',
        'description' => '"Updated summary"',
        'categories' => ['testing'],
        'published_at' => 'null',
        'modified_at' => 'null',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => true,
        'image_disk' => 'null',
        'image_path' => 'null',
        'sponsored_at' => 'null',
    ], 'Updated body');

    artisan(SyncPostsCommand::class, ['--directory' => syncPostsMarkdownPath()])
        ->assertSuccessful();

    $post->refresh();

    expect($post->title)->toBe('Updated post')
        ->and($post->description)->toBe('Updated summary')
        ->and($post->content)->toBe('Updated body')
        ->and($post->is_commercial)->toBeTrue()
        ->and($post->categories->pluck('slug')->all())->toBe(['testing']);
});

it('assigns a source id through the first cutover slug fallback', function () {
    $author = User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel']);

    $post = Post::factory()
        ->for($author)
        ->create([
            'source_uuid' => null,
            'slug' => 'cutover-post',
            'title' => 'Cutover post',
            'description' => 'Cutover summary',
        ]);

    writeSyncPost(syncPostsMarkdownPath(), 'cutover-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAW',
        'title' => '"Cutover post"',
        'slug' => 'cutover-post',
        'author' => 'benjamincrozat',
        'description' => '"Cutover summary"',
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

    artisan(SyncPostsCommand::class, ['--directory' => syncPostsMarkdownPath()])
        ->assertSuccessful();

    expect($post->refresh()->source_uuid)->toBe('01ARZ3NDEKTSV4RRFFQ69G5FAW');
});

it('creates redirects when a synced slug changes', function () {
    $author = User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel']);

    $post = Post::factory()
        ->for($author)
        ->create([
            'source_uuid' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'source_path' => 'old-slug.md',
            'slug' => 'old-slug',
        ]);

    writeSyncPost(syncPostsMarkdownPath(), 'new-slug', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
        'title' => '"Slug update"',
        'slug' => 'new-slug',
        'author' => 'benjamincrozat',
        'description' => '"Slug update summary"',
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

    artisan(SyncPostsCommand::class, ['--directory' => syncPostsMarkdownPath()])
        ->assertSuccessful();

    assertDatabaseHas(Redirect::class, [
        'from' => 'old-slug',
        'to' => 'new-slug',
    ]);
});

it('soft deletes removed files and restores posts when the file returns', function () {
    $author = User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel']);

    writeSyncPost(syncPostsMarkdownPath(), 'restorable-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAX',
        'title' => '"Restorable post"',
        'slug' => 'restorable-post',
        'author' => 'benjamincrozat',
        'description' => '"Restorable summary"',
        'categories' => ['laravel'],
        'published_at' => '"2026-03-11T09:00:00+01:00"',
        'modified_at' => 'null',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => false,
        'image_disk' => 'null',
        'image_path' => 'null',
        'sponsored_at' => 'null',
    ]);

    artisan(SyncPostsCommand::class, ['--directory' => syncPostsMarkdownPath()])
        ->assertSuccessful();

    $post = Post::query()->where('source_uuid', '01ARZ3NDEKTSV4RRFFQ69G5FAX')->firstOrFail();

    File::delete(syncPostsMarkdownPath() . '/restorable-post.md');

    artisan(SyncPostsCommand::class, ['--directory' => syncPostsMarkdownPath()])
        ->assertSuccessful();

    expect($post->fresh()->trashed())->toBeTrue();

    get(route('posts.show', 'restorable-post'))
        ->assertStatus(410);

    writeSyncPost(syncPostsMarkdownPath(), 'restorable-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAX',
        'title' => '"Restorable post"',
        'slug' => 'restorable-post',
        'author' => 'benjamincrozat',
        'description' => '"Restorable summary"',
        'categories' => ['laravel'],
        'published_at' => '"2026-03-11T09:00:00+01:00"',
        'modified_at' => 'null',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => false,
        'image_disk' => 'null',
        'image_path' => 'null',
        'sponsored_at' => 'null',
    ]);

    artisan(SyncPostsCommand::class, ['--directory' => syncPostsMarkdownPath()])
        ->assertSuccessful();

    expect($post->fresh()->trashed())->toBeFalse();
});

it('fails on invalid front matter and unresolved references', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel']);

    writeSyncPost(syncPostsMarkdownPath(), 'invalid-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAY',
        'slug' => 'invalid-post',
        'author' => 'missing-author',
        'description' => '"Missing title"',
        'categories' => ['unknown-category'],
        'published_at' => '"not-a-date"',
        'modified_at' => 'null',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => false,
        'image_disk' => 'null',
        'image_path' => 'null',
        'sponsored_at' => 'null',
    ]);

    expect(Artisan::call('app:sync-posts', ['--directory' => syncPostsMarkdownPath()]))
        ->toBe(1);

    expect(Artisan::output())->toContain('invalid-post.md');

    assertDatabaseMissing('posts', ['slug' => 'invalid-post']);
});

it('fails on duplicate ids and slugs', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel']);

    writeSyncPost(syncPostsMarkdownPath(), 'duplicate-one', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAZ',
        'title' => '"Duplicate one"',
        'slug' => 'duplicate-one',
        'author' => 'benjamincrozat',
        'description' => '"Duplicate one summary"',
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

    File::ensureDirectoryExists(syncPostsMarkdownPath() . '/nested');

    File::put(syncPostsMarkdownPath() . '/nested/duplicate-one.md', syncPostsFrontMatter([
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FB0',
        'title' => '"Duplicate two"',
        'slug' => 'duplicate-one',
        'author' => 'benjamincrozat',
        'description' => '"Duplicate two summary"',
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
    ]) . 'Duplicate content');

    expect(Artisan::call('app:sync-posts', ['--directory' => syncPostsMarkdownPath()]))
        ->toBe(1);

    expect(Artisan::output())->toContain('Duplicate slug');
});

it('does not touch the sitemap or search console during sync', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel', 'name' => 'Laravel']);

    File::delete(public_path('sitemap.xml'));

    writeSyncPost(syncPostsMarkdownPath(), 'search-console-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FB1',
        'title' => '"Search Console post"',
        'slug' => 'search-console-post',
        'author' => 'benjamincrozat',
        'description' => '"Search Console summary"',
        'categories' => ['laravel'],
        'published_at' => '"2026-03-11T09:00:00+01:00"',
        'modified_at' => '"2026-03-11T12:00:00+01:00"',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => false,
        'image_disk' => 'null',
        'image_path' => 'null',
        'sponsored_at' => 'null',
    ]);

    expect(Artisan::call('app:sync-posts', ['--directory' => syncPostsMarkdownPath()]))
        ->toBe(0);

    expect(Artisan::output())
        ->toContain('created=1')
        ->not->toContain('Sitemap generated successfully')
        ->not->toContain('Search Console');

    expect(File::exists(public_path('sitemap.xml')))->toBeFalse();
});
