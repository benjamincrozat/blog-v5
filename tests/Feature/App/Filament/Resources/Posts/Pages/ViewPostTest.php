<?php

use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Filament\Resources\Posts\Pages\ViewPost;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
});

it('loads post details in read-only view mode', function () {
    $post = Post::factory()->create([
        'title' => 'Existing title',
        'slug' => 'existing-title',
        'serp_description' => 'SERP description',
    ]);

    livewire(ViewPost::class, ['record' => $post->slug])
        ->assertSuccessful()
        ->assertSee('Existing title')
        ->assertSee('SERP description');
});

