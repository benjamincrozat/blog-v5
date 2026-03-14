<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Redirect;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;
use function Pest\Laravel\assertDatabaseHas;

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

it('updates the slug when it still matches the original title', function () {
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
        ->and($post->slug)->toBe('updated-title');
});

it('creates a redirect when the slug is changed from the edit page', function () {
    $post = Post::factory()->create([
        'title' => 'Existing title',
        'slug' => 'existing-title',
    ]);

    livewire(EditPost::class, ['record' => $post->slug])
        ->fillForm([
            'slug' => 'updated-title',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($post->refresh()->slug)->toBe('updated-title');

    assertDatabaseHas(Redirect::class, [
        'from' => 'existing-title',
        'to' => 'updated-title',
    ]);

    get('/existing-title')
        ->assertRedirect('/updated-title')
        ->assertStatus(301);
});

it('preserves a customized slug when editing the title', function () {
    $post = Post::factory()->create([
        'title' => 'Existing title',
        'slug' => 'custom-slug',
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
        ->and($post->slug)->toBe('custom-slug');
});
