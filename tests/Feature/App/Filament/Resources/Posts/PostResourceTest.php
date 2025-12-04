<?php

use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;

use App\Filament\Resources\Posts\PostResource;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
});

it('returns author in global search details', function () {
    $post = Post::factory()->for(User::factory(['name' => 'Filament Author']))->create();

    expect(PostResource::getGlobalSearchResultDetails($post))->toBe([
        'Author' => 'Filament Author',
    ]);
});
