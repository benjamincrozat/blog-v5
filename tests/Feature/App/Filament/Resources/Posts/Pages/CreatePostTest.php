<?php

use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Str;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;
use function Pest\Laravel\assertDatabaseHas;

use App\Filament\Resources\Posts\Pages\CreatePost;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
});

it('creates a post', function () {
    $author = User::factory()->create();
    $category = Category::factory()->create();

    livewire(CreatePost::class)
        ->fillForm([
            'title' => 'Filament Post Title',
            'slug' => Str::slug('Filament Post Title'),
            'content' => 'Content body',
            'user_id' => $author->getKey(),
            'categories' => [$category->getKey()],
            'serp_title' => 'SERP title',
            'description' => 'Meta description',
            'canonical_url' => 'https://example.com/foo',
            'published_at' => now(),
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertRedirect();

    assertDatabaseHas('posts', [
        'title' => 'Filament Post Title',
        'slug' => Str::slug('Filament Post Title'),
        'user_id' => $author->getKey(),
        'canonical_url' => 'https://example.com/foo',
    ]);
});

it('validates required fields on create', function () {
    livewire(CreatePost::class)
        ->fillForm([
            'title' => null,
            'slug' => null,
            'content' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
            'slug' => 'required',
            'content' => 'required',
        ]);
});
