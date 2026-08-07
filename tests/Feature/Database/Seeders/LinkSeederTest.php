<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\seed;

use Database\Seeders\LinkSeeder;

it('seeds approved community links without unreliable remote images', function () {
    User::factory(2)->create([
        'github_data' => ['id' => 123],
    ]);
    Post::factory(2)->create();

    seed(LinkSeeder::class);

    expect(Link::query()->count())->toBe(40)
        ->and(Link::query()->approved()->count())->toBe(40)
        ->and(Link::query()->whereNotNull('post_id')->count())->toBe(10)
        ->and(Link::query()->whereNotNull('image_url')->count())->toBe(0);
});
