<?php

use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Filament\Resources\Posts\Pages\EditPost;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
});

it('loads data for editing', function () {
    $post = Post::factory()->create([
        'title' => 'Existing title',
        'slug' => 'existing-title',
    ]);

    livewire(EditPost::class, ['record' => $post->slug])
        ->assertFormSet([
            'title' => 'Existing title',
            'slug' => 'existing-title',
        ]);
});

it('saves changes', function () {
    $post = Post::factory()->create([
        'title' => 'Existing title',
        'slug' => 'existing-title',
    ]);

    livewire(EditPost::class, ['record' => $post->slug])
        ->fillForm([
            'title' => 'Updated title',
            'slug' => 'updated-title',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($post->refresh()->title)->toBe('Updated title');
});
