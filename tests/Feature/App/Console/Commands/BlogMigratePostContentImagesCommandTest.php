<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

function blogMigrateContentImagesMarkdownPath() : string
{
    return (string) config('blog.markdown.posts_path');
}

beforeEach(function () {
    $markdownPath = storage_path('framework/testing/markdown-migrate-content-images-' . Str::uuid());

    File::deleteDirectory($markdownPath);
    File::ensureDirectoryExists($markdownPath);

    config()->set('blog.markdown.posts_path', $markdownPath);

    Storage::fake('cloudflare-images');
});

afterEach(function () {
    File::deleteDirectory(blogMigrateContentImagesMarkdownPath());
});

it('uploads external post content images to Cloudflare Images and rewrites markdown URLs', function () {
    $markdownImageUrl = 'https://cdn.example.com/tutorial-step.png';
    $htmlImageUrl = 'https://cdn.example.com/badge.svg';

    File::put(blogMigrateContentImagesMarkdownPath() . '/migrated-post.md', <<<MD
---
id: "01ARZ3NDEKTSV4RRFFQ69G5FAV"
title: "Migrated post"
slug: "migrated-post"
author: "benjamincrozat"
description: "Summary"
categories:
  - laravel
published_at: null
modified_at: null
serp_title: null
serp_description: null
canonical_url: null
is_commercial: false
image_disk: null
image_path: null
sponsored_at: null
---
![Tutorial step]({$markdownImageUrl})

<img src="{$htmlImageUrl}" alt="Badge" />
MD);

    Http::fake([
        $markdownImageUrl => Http::response('png image', 200, ['Content-Type' => 'image/png']),
        $htmlImageUrl => Http::response('<svg></svg>', 200, ['Content-Type' => 'image/svg+xml']),
    ]);

    $exitCode = Artisan::call('blog:migrate-post-content-images');

    $pngPath = 'images/posts/imported/migrated-post-' . substr(sha1($markdownImageUrl), 0, 20) . '.png';
    $svgPath = 'images/posts/imported/migrated-post-' . substr(sha1($htmlImageUrl), 0, 20) . '.svg';

    Storage::disk('cloudflare-images')->assertExists($pngPath);
    Storage::disk('cloudflare-images')->assertExists($svgPath);

    $contents = File::get(blogMigrateContentImagesMarkdownPath() . '/migrated-post.md');

    expect($contents)
        ->toContain(Storage::disk('cloudflare-images')->url($pngPath))
        ->toContain(Storage::disk('cloudflare-images')->url($svgPath))
        ->not->toContain($markdownImageUrl)
        ->not->toContain($htmlImageUrl)
        ->and($exitCode)->toBe(0);
});
