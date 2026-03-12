<?php

use Illuminate\Support\Str;

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use App\Console\Commands\BootstrapPostsFromProductionCommand;

function bootstrapPostsMarkdownDirectory() : string
{
    return (string) config('tests.bootstrap-posts.markdown_directory');
}

function bootstrapPostsProductionConnection() : \Illuminate\Database\ConnectionInterface
{
    return DB::connection('production');
}

beforeEach(function () {
    config([
        'database.connections.production' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
            'foreign_key_constraints' => true,
        ],
    ]);

    DB::purge('production');
    Schema::connection('production')->create('users', function (Blueprint $table) {
        $table->id();
        $table->string('slug');
    });
    Schema::connection('production')->create('posts', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('user_id');
        $table->string('image_path')->nullable();
        $table->string('image_disk')->nullable();
        $table->string('title');
        $table->string('slug');
        $table->text('content');
        $table->string('description')->nullable();
        $table->string('canonical_url')->nullable();
        $table->dateTime('published_at')->nullable();
        $table->dateTime('modified_at')->nullable();
        $table->string('serp_title')->nullable();
        $table->boolean('is_commercial')->default(false);
        $table->dateTime('sponsored_at')->nullable();
        $table->dateTime('deleted_at')->nullable();
    });
    Schema::connection('production')->create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('slug');
    });
    Schema::connection('production')->create('category_post', function (Blueprint $table) {
        $table->unsignedBigInteger('category_id');
        $table->unsignedBigInteger('post_id');
    });

    config()->set('tests.bootstrap-posts.markdown_directory', storage_path('framework/testing/bootstrap-posts-' . Str::uuid()));

    File::ensureDirectoryExists(bootstrapPostsMarkdownDirectory());
});

afterEach(function () {
    File::deleteDirectory(bootstrapPostsMarkdownDirectory());
});

it('exports only active production posts to markdown', function () {
    $production = bootstrapPostsProductionConnection();

    $production->table('users')->insert([
        ['id' => 1, 'slug' => 'author-slug'],
    ]);

    $production->table('posts')->insert([
        [
            'id' => 1,
            'user_id' => 1,
            'title' => 'Active post',
            'slug' => 'active-post',
            'content' => 'Body',
            'is_commercial' => false,
            'deleted_at' => null,
        ],
        [
            'id' => 2,
            'user_id' => 1,
            'title' => 'Deleted post',
            'slug' => 'deleted-post',
            'content' => 'Deleted body',
            'is_commercial' => false,
            'deleted_at' => '2026-01-01 00:00:00',
        ],
    ]);

    artisan(BootstrapPostsFromProductionCommand::class, ['--directory' => bootstrapPostsMarkdownDirectory()])
        ->assertSuccessful();

    expect(File::exists(bootstrapPostsMarkdownDirectory() . '/active-post.md'))->toBeTrue()
        ->and(File::exists(bootstrapPostsMarkdownDirectory() . '/deleted-post.md'))->toBeFalse();

    $markdown = File::get(bootstrapPostsMarkdownDirectory() . '/active-post.md');

    expect($markdown)
        ->toContain("title: 'Active post'")
        ->toContain("slug: 'active-post'")
        ->toContain("author: 'author-slug'");
});

it('migrates embedded and featured external images during bootstrap', function () {
    Storage::fake('cloudflare-images');

    $production = bootstrapPostsProductionConnection();

    $production->table('users')->insert([
        ['id' => 1, 'slug' => 'author-slug'],
    ]);

    $production->table('posts')->insert([
        [
            'id' => 1,
            'user_id' => 1,
            'title' => 'Image post',
            'slug' => 'image-post',
            'content' => <<<'MARKDOWN'
![Screenshot](https://old-cdn.test/content-image.png)
<img src="https://old-cdn.test/content-html-image.png" alt="html" />
MARKDOWN,
            'image_path' => 'https://old-cdn.test/featured-image.png',
            'image_disk' => null,
            'is_commercial' => false,
            'deleted_at' => null,
        ],
    ]);

    Http::fake([
        'old-cdn.test/*' => Http::response('fake-image-content', 200, [
            'Content-Type' => 'image/png',
        ]),
    ]);

    artisan(BootstrapPostsFromProductionCommand::class, ['--directory' => bootstrapPostsMarkdownDirectory()])
        ->assertSuccessful();

    $markdown = File::get(bootstrapPostsMarkdownDirectory() . '/image-post.md');

    expect($markdown)
        ->not->toContain('https://old-cdn.test/content-image.png')
        ->and($markdown)->not->toContain('https://old-cdn.test/content-html-image.png')
        ->and($markdown)->not->toContain('https://old-cdn.test/featured-image.png')
        ->and($markdown)->toContain('images/posts/imported/')
        ->and($markdown)->toContain("image_path: 'images/posts/imported/");

    expect(Storage::disk('cloudflare-images')->allFiles('images/posts/imported'))
        ->toHaveCount(3);
});
