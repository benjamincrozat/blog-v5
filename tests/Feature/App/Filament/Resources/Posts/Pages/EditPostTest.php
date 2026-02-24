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

it('saves editable changes without changing slug', function () {
    $post = Post::factory()->create([
        'title' => 'Existing title',
        'slug' => 'existing-title',
        'description' => 'Old description',
    ]);

    livewire(EditPost::class, ['record' => $post->slug])
        ->fillForm([
            'title' => 'Updated title',
            'description' => 'Updated description',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($post->refresh()->title)->toBe('Updated title')
        ->and($post->description)->toBe('Updated description')
        ->and($post->slug)->toBe('existing-title');
});
