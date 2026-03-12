<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use App\Models\Redirect;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Exceptions\PostMarkdownException;

use function Pest\Laravel\assertDatabaseHas;

function blogMarkdownFrontMatter(array $attributes) : string
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

function writeMarkdownPost(string $basePath, string $slug, array $attributes, string $body = 'Markdown body') : void
{
    File::put(
        $basePath . "/{$slug}.md",
        blogMarkdownFrontMatter($attributes) . $body
    );
}

beforeEach(function () {
    $this->markdownPath = storage_path('framework/testing/markdown-sync-' . Str::uuid());

    File::deleteDirectory($this->markdownPath);
    File::ensureDirectoryExists($this->markdownPath);

    config()->set('blog.markdown.posts_path', $this->markdownPath);
});

afterEach(function () {
    File::deleteDirectory($this->markdownPath);
});

it('creates a post from markdown', function () {
    $author = User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel', 'name' => 'Laravel']);
    Category::factory()->create(['slug' => 'testing', 'name' => 'Testing']);

    writeMarkdownPost($this->markdownPath, 'file-first-post', [
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

    Artisan::call('blog:sync');

    expect(Artisan::output())->toContain('created=1');

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

    writeMarkdownPost($this->markdownPath, 'original-post', [
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

    Artisan::call('blog:sync');

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

    writeMarkdownPost($this->markdownPath, 'cutover-post', [
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

    Artisan::call('blog:sync');

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

    writeMarkdownPost($this->markdownPath, 'new-slug', [
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

    Artisan::call('blog:sync');

    assertDatabaseHas(Redirect::class, [
        'from' => 'old-slug',
        'to' => 'new-slug',
    ]);
});

it('soft deletes removed files and restores posts when the file returns', function () {
    $author = User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel']);

    writeMarkdownPost($this->markdownPath, 'restorable-post', [
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

    Artisan::call('blog:sync');

    $post = Post::query()->where('source_uuid', '01ARZ3NDEKTSV4RRFFQ69G5FAX')->firstOrFail();

    File::delete($this->markdownPath . '/restorable-post.md');

    Artisan::call('blog:sync');

    expect($post->fresh()->trashed())->toBeTrue();

    get(route('posts.show', 'restorable-post'))
        ->assertStatus(410);

    writeMarkdownPost($this->markdownPath, 'restorable-post', [
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

    Artisan::call('blog:sync');

    expect($post->fresh()->trashed())->toBeFalse();
});

it('fails on invalid front matter and unresolved references', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel']);

    writeMarkdownPost($this->markdownPath, 'invalid-post', [
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

    expect(fn () => Artisan::call('blog:sync'))
        ->toThrow(PostMarkdownException::class, 'invalid-post.md');
});

it('fails on duplicate ids and slugs', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel']);

    writeMarkdownPost($this->markdownPath, 'duplicate-one', [
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

    File::ensureDirectoryExists($this->markdownPath . '/nested');

    File::put($this->markdownPath . '/nested/duplicate-one.md', blogMarkdownFrontMatter([
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

    expect(fn () => Artisan::call('blog:sync'))
        ->toThrow(PostMarkdownException::class, 'Duplicate slug');
});

it('regenerates and submits the sitemap when synced content changes', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel', 'name' => 'Laravel']);

    config()->set('app.url', 'https://benjamincrozat.com');
    config()->set('services.search_console.enabled', true);
    config()->set('services.search_console.property', 'sc-domain:benjamincrozat.com');
    config()->set('services.search_console.oauth.client_id', 'client-id');
    config()->set('services.search_console.oauth.client_secret', 'client-secret');
    config()->set('services.search_console.oauth.refresh_token', 'refresh-token');

    Http::fake([
        'https://oauth2.googleapis.com/token' => Http::response(['access_token' => 'token'], 200),
        'https://www.googleapis.com/webmasters/v3/sites/*/sitemaps/*' => Http::response('', 204),
    ]);

    writeMarkdownPost($this->markdownPath, 'search-console-post', [
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

    Artisan::call('blog:sync');

    expect(Artisan::output())
        ->toContain('created=1')
        ->toContain('Sitemap generated successfully')
        ->toContain('Sitemap submitted to Google Search Console.');

    Http::assertSent(function (Request $request) {
        return 'PUT' === $request->method() &&
            str_contains((string) $request->url(), rawurlencode('https://benjamincrozat.com/sitemap.xml'));
    });
});

it('skips search console work when blog sync finds no content changes', function () {
    User::factory()->create(['github_login' => 'benjamincrozat']);
    Category::factory()->create(['slug' => 'laravel', 'name' => 'Laravel']);

    writeMarkdownPost($this->markdownPath, 'no-change-post', [
        'id' => '01ARZ3NDEKTSV4RRFFQ69G5FB2',
        'title' => '"No change post"',
        'slug' => 'no-change-post',
        'author' => 'benjamincrozat',
        'description' => '"No change summary"',
        'categories' => ['laravel'],
        'published_at' => '"2026-03-11T08:00:00+00:00"',
        'modified_at' => '"2026-03-11T11:00:00+00:00"',
        'serp_title' => 'null',
        'serp_description' => 'null',
        'canonical_url' => 'null',
        'is_commercial' => false,
        'image_disk' => 'null',
        'image_path' => 'null',
        'sponsored_at' => 'null',
    ]);

    Artisan::call('blog:sync');

    Http::fake();

    Artisan::call('blog:sync');

    expect(Artisan::output())
        ->toContain('created=0, updated=0, restored=0, deleted=0')
        ->toContain('Search Console submission skipped because no content changes were detected.');

    Http::assertNothingSent();
});
