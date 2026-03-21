<?php

use App\Models\Tool;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Storage;

it('returns null for image_url when the tool has no image', function () {
    $tool = Tool::factory()->create([
        'image_disk' => null,
        'image_path' => null,
    ]);

    expect($tool->image_url)->toBeNull();
});

it('returns the correct image_url when the tool image uses a configured disk', function () {
    Storage::fake('public');

    $tool = Tool::factory()->create([
        'image_disk' => 'public',
        'image_path' => 'images/tools/foo.jpg',
    ]);

    expect($tool->image_url)->toMatch('/\/images\/tools\/foo\.jpg$/');
});

it('returns the correct image_url for resource-backed catalog images', function () {
    $tool = Tool::factory()->create([
        'image_disk' => null,
        'image_path' => 'resources/img/screenshots/tool.webp',
    ]);

    expect($tool->image_url)->toBe(Vite::asset('resources/img/screenshots/tool.webp'));
});

it('returns the image_url as-is for absolute URLs', function () {
    $tool = Tool::factory()->create([
        'image_disk' => null,
        'image_path' => 'https://cdn.example.com/tool.png',
    ]);

    expect($tool->image_url)->toBe('https://cdn.example.com/tool.png');
});
