<?php

use App\Models\Post;
use App\Models\Tool;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

function syncToolsMarkdownPath() : string
{
    return (string) config('blog.markdown.tools_path');
}

beforeEach(function () {
    $markdownPath = storage_path('framework/testing/tool-markdown-sync-' . Str::uuid());

    File::deleteDirectory($markdownPath);
    File::ensureDirectoryExists($markdownPath);

    config()->set('blog.markdown.tools_path', $markdownPath);
});

afterEach(function () {
    File::deleteDirectory(syncToolsMarkdownPath());
});

function syncToolsFrontMatter(array $attributes) : string
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

function writeSyncTool(string $basePath, string $slug, array $attributes, string $body = 'Tool body') : void
{
    File::put(
        $basePath . "/{$slug}.md",
        syncToolsFrontMatter($attributes) . $body
    );
}

it('creates a tool from markdown and links its review post', function () {
    $reviewPost = Post::factory()->create([
        'slug' => 'remodex-review',
        'published_at' => now()->subDay(),
    ]);

    writeSyncTool(syncToolsMarkdownPath(), 'remodex', [
        'id' => '01KP0KBPQ37T7D6G5E2M4Y71V6',
        'name' => '"Remodex"',
        'slug' => 'remodex',
        'description' => '"A local-first Codex companion for iPhone."',
        'website_url' => '"https://github.com/Emanuele-web04/remodex"',
        'outbound_url' => '"https://github.com/Emanuele-web04/remodex"',
        'pricing_model' => '"free"',
        'has_free_plan' => true,
        'has_free_trial' => false,
        'is_open_source' => true,
        'categories' => ['ai', 'developer-tools'],
        'image_disk' => '"public"',
        'image_path' => '"images/tools/remodex.png"',
        'review_post_slug' => '"remodex-review"',
        'published_at' => '"2026-03-17T09:00:00+00:00"',
    ], "A short tool summary.\n");

    expect(Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]))
        ->toBe(0);

    expect(Artisan::output())
        ->toContain('created=1');

    $tool = Tool::query()->where('source_uuid', '01KP0KBPQ37T7D6G5E2M4Y71V6')->firstOrFail();

    expect($tool->name)->toBe('Remodex')
        ->and($tool->reviewPost?->is($reviewPost))->toBeTrue()
        ->and($tool->categories)->toBe(['ai', 'developer-tools'])
        ->and($tool->image_disk)->toBe('public')
        ->and($tool->content)->toBe("A short tool summary.\n")
        ->and($tool->source_path)->toBe('remodex.md');
});

it('fails when a review post slug does not exist', function () {
    writeSyncTool(syncToolsMarkdownPath(), 'missing-review', [
        'id' => '01KP0KD4M8YB7ATV9W5J8A4GQ5',
        'name' => '"Missing Review Tool"',
        'slug' => 'missing-review',
        'description' => '"A tool with a broken review link."',
        'website_url' => '"https://example.com/tool"',
        'outbound_url' => '"https://example.com/tool"',
        'pricing_model' => '"paid"',
        'has_free_plan' => false,
        'has_free_trial' => true,
        'is_open_source' => false,
        'categories' => ['testing'],
        'image_disk' => 'null',
        'image_path' => 'null',
        'review_post_slug' => '"not-a-real-post"',
        'published_at' => '"2026-03-17T09:00:00+00:00"',
    ]);

    expect(Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]))
        ->toBe(1);

    expect(Artisan::output())
        ->toContain('Unknown review post slug [not-a-real-post]');
});

it('deletes a tool when its markdown file is removed', function () {
    writeSyncTool(syncToolsMarkdownPath(), 'tower', [
        'id' => '01KP0KD8T8VA1QGQ9S7QF8N1H6',
        'name' => '"Tower"',
        'slug' => 'tower',
        'description' => '"A Git client for desktop."',
        'website_url' => '"https://www.git-tower.com/"',
        'outbound_url' => '"https://www.git-tower.com/?via=benjamincrozat"',
        'pricing_model' => '"paid"',
        'has_free_plan' => false,
        'has_free_trial' => true,
        'is_open_source' => false,
        'categories' => ['git'],
        'image_disk' => 'null',
        'image_path' => '"resources/img/screenshots/tower.webp"',
        'review_post_slug' => 'null',
        'published_at' => '"2026-03-17T09:00:00+00:00"',
    ]);

    Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]);

    File::delete(syncToolsMarkdownPath() . '/tower.md');

    expect(Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]))
        ->toBe(0);

    expect(Tool::query()->where('slug', 'tower')->exists())->toBeFalse();
});

it('reassigns a review post cleanly when a replacement tool takes over', function () {
    $reviewPost = Post::factory()->create([
        'slug' => 'laravel-mcp-review',
        'published_at' => now()->subDay(),
    ]);

    Tool::factory()->create([
        'source_uuid' => '01KP0KD9D8ZVZ9CV08D2YCV8ZZ',
        'slug' => 'old-laravel-mcp',
        'review_post_id' => $reviewPost->getKey(),
    ]);

    writeSyncTool(syncToolsMarkdownPath(), 'laravel-mcp', [
        'id' => '01KP0KDABYBCQW3MZB8Z0E3SKA',
        'name' => '"Laravel MCP"',
        'slug' => 'laravel-mcp',
        'description' => '"An MCP server for Laravel projects."',
        'website_url' => '"https://github.com/benjamincrozat/laravel-mcp"',
        'outbound_url' => '"https://github.com/benjamincrozat/laravel-mcp"',
        'pricing_model' => '"free"',
        'has_free_plan' => true,
        'has_free_trial' => false,
        'is_open_source' => true,
        'categories' => ['laravel', 'ai'],
        'image_disk' => 'null',
        'image_path' => 'null',
        'review_post_slug' => '"laravel-mcp-review"',
        'published_at' => '"2026-03-17T09:00:00+00:00"',
    ]);

    expect(Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]))
        ->toBe(0);

    expect(Tool::query()->where('slug', 'old-laravel-mcp')->exists())->toBeFalse();

    $tool = Tool::query()->where('slug', 'laravel-mcp')->firstOrFail();

    expect($tool->reviewPost?->is($reviewPost))->toBeTrue();
});

it('fails when the tool id is not a valid ulid', function () {
    writeSyncTool(syncToolsMarkdownPath(), 'bad-id', [
        'id' => '"not-a-ulid"',
        'name' => '"Bad ID"',
        'slug' => 'bad-id',
        'description' => '"A tool with an invalid source id."',
        'website_url' => '"https://example.com/tool"',
        'outbound_url' => '"https://example.com/tool"',
        'pricing_model' => '"free"',
        'has_free_plan' => true,
        'has_free_trial' => false,
        'is_open_source' => true,
        'categories' => ['testing'],
        'image_disk' => 'null',
        'image_path' => 'null',
        'review_post_slug' => 'null',
        'published_at' => '"2026-03-17T09:00:00+00:00"',
    ]);

    expect(Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]))
        ->toBe(1);

    expect(Artisan::output())
        ->toContain('Front matter key [id] must be a valid ULID.');
});

it('fails when published_at is not ISO-8601', function () {
    writeSyncTool(syncToolsMarkdownPath(), 'bad-published-at', [
        'id' => '01KP0KDAMZ0AZ3CP6GFFVZXFJ6',
        'name' => '"Bad Published At"',
        'slug' => 'bad-published-at',
        'description' => '"A tool with a non-deterministic publish date."',
        'website_url' => '"https://example.com/tool"',
        'outbound_url' => '"https://example.com/tool"',
        'pricing_model' => '"free"',
        'has_free_plan' => true,
        'has_free_trial' => false,
        'is_open_source' => true,
        'categories' => ['testing'],
        'image_disk' => 'null',
        'image_path' => 'null',
        'review_post_slug' => 'null',
        'published_at' => '"tomorrow"',
    ]);

    expect(Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]))
        ->toBe(1);

    expect(Artisan::output())
        ->toContain('Front matter key [published_at] must be an ISO-8601 datetime or null.');
});

it('fails when pricing_model is invalid', function () {
    writeSyncTool(syncToolsMarkdownPath(), 'bad-pricing', [
        'id' => '01KP0KDB5XNZSAAWQ8M5Q7B419',
        'name' => '"Bad Pricing"',
        'slug' => 'bad-pricing',
        'description' => '"A tool with an invalid pricing model."',
        'website_url' => '"https://example.com/tool"',
        'outbound_url' => '"https://example.com/tool"',
        'pricing_model' => '"enterprise"',
        'has_free_plan' => false,
        'has_free_trial' => false,
        'is_open_source' => false,
        'categories' => ['testing'],
        'image_disk' => 'null',
        'image_path' => 'null',
        'review_post_slug' => 'null',
        'published_at' => '"2026-03-17T09:00:00+00:00"',
    ]);

    expect(Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]))
        ->toBe(1);

    expect(Artisan::output())
        ->toContain('Front matter key [pricing_model] must be one of');
});

it('fails when a disk-backed image_path is missing image_disk', function () {
    writeSyncTool(syncToolsMarkdownPath(), 'bad-image', [
        'id' => '01KP0KDBER66R3C81GZVWXW3B7',
        'name' => '"Bad Image"',
        'slug' => 'bad-image',
        'description' => '"A tool with incomplete image metadata."',
        'website_url' => '"https://example.com/tool"',
        'outbound_url' => '"https://example.com/tool"',
        'pricing_model' => '"free"',
        'has_free_plan' => true,
        'has_free_trial' => false,
        'is_open_source' => true,
        'categories' => ['testing'],
        'image_disk' => 'null',
        'image_path' => '"images/tools/foo.jpg"',
        'review_post_slug' => 'null',
        'published_at' => '"2026-03-17T09:00:00+00:00"',
    ]);

    expect(Artisan::call('app:sync-tools', ['--directory' => syncToolsMarkdownPath()]))
        ->toBe(1);

    expect(Artisan::output())
        ->toContain('Front matter key [image_path] requires [image_disk]');
});
