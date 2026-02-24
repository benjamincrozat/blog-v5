<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

use function Pest\Laravel\artisan;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertSoftDeleted;
use function Pest\Laravel\assertDatabaseMissing;

use App\Console\Commands\SyncPostsCommand;

beforeEach(function () {
    $this->markdownDirectory = storage_path('framework/testing/markdown-posts-' . Str::uuid());

    File::ensureDirectoryExists($this->markdownDirectory);
});

afterEach(function () {
    File::deleteDirectory($this->markdownDirectory);
});

it('creates a post from markdown and syncs categories by slug', function () {
    $author = User::factory()->create(['name' => 'Author Slug']);

    File::put($this->markdownDirectory . '/my-post.md', <<<MD
---
title: 'My post'
slug: 'my-post'
author: 'author-slug'
categories:
  - laravel
  - testing
description: 'Description'
published_at: '2026-02-01 10:00:00'
---
## Hello
MD);

    artisan(SyncPostsCommand::class, ['--directory' => $this->markdownDirectory])
        ->assertSuccessful();

    assertDatabaseHas('posts', [
        'slug' => 'my-post',
        'title' => 'My post',
        'user_id' => $author->getKey(),
        'description' => 'Description',
    ]);

    assertDatabaseHas('categories', ['slug' => 'laravel']);
    assertDatabaseHas('categories', ['slug' => 'testing']);

    $post = Post::query()->where('slug', 'my-post')->firstOrFail();
    expect($post->categories->pluck('slug')->sort()->values()->all())
        ->toBe(['laravel', 'testing']);
});

it('updates posts, resets missing optional fields, and preserves sessions_count', function () {
    $author = User::factory()->create(['name' => 'Author Slug']);
    $post = Post::factory()->for($author)->create([
        'slug' => 'my-post',
        'title' => 'Old title',
        'description' => 'Old description',
        'serp_title' => 'Old serp title',
        'serp_description' => 'Old serp description',
        'canonical_url' => 'https://example.com/old',
        'sessions_count' => 321,
        'is_commercial' => true,
    ]);

    $post->categories()->sync([
        Category::factory()->create(['slug' => 'old-category'])->getKey(),
    ]);

    File::put($this->markdownDirectory . '/my-post.md', <<<MD
---
title: 'New title'
slug: 'my-post'
author: 'author-slug'
categories:
  - laravel
---
## Updated
MD);

    artisan(SyncPostsCommand::class, ['--directory' => $this->markdownDirectory])
        ->assertSuccessful();

    $post->refresh();

    expect($post->title)->toBe('New title')
        ->and($post->description)->toBeNull()
        ->and($post->serp_title)->toBeNull()
        ->and($post->serp_description)->toBeNull()
        ->and($post->canonical_url)->toBeNull()
        ->and((bool) $post->is_commercial)->toBeFalse()
        ->and($post->sessions_count)->toBe(321)
        ->and($post->categories->pluck('slug')->all())->toBe(['laravel']);
});

it('soft deletes posts that are missing from markdown files', function () {
    $author = User::factory()->create(['name' => 'Author Slug']);
    $post = Post::factory()->for($author)->create(['slug' => 'remove-me']);

    File::put($this->markdownDirectory . '/keep-me.md', <<<MD
---
title: 'Keep me'
slug: 'keep-me'
author: 'author-slug'
categories: []
---
Hello
MD);

    artisan(SyncPostsCommand::class, ['--directory' => $this->markdownDirectory])
        ->assertSuccessful();

    assertSoftDeleted('posts', ['id' => $post->getKey()]);
});

it('fails with clear errors when the author slug is unknown', function () {
    File::put($this->markdownDirectory . '/my-post.md', <<<MD
---
title: 'My post'
slug: 'my-post'
author: 'unknown-author'
categories: []
---
Hello
MD);

    artisan(SyncPostsCommand::class, ['--directory' => $this->markdownDirectory])
        ->assertFailed();

    assertDatabaseMissing('posts', ['slug' => 'my-post']);
});

it('fails with clear errors when filename and slug do not match', function () {
    User::factory()->create(['name' => 'Author Slug']);

    File::put($this->markdownDirectory . '/filename-slug.md', <<<MD
---
title: 'My post'
slug: 'frontmatter-slug'
author: 'author-slug'
categories: []
---
Hello
MD);

    artisan(SyncPostsCommand::class, ['--directory' => $this->markdownDirectory])
        ->assertFailed();

    assertDatabaseMissing('posts', ['slug' => 'frontmatter-slug']);
});
