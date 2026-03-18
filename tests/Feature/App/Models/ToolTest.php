<?php

use App\Models\Tool;
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
