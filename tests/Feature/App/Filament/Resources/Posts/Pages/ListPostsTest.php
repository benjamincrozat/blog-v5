<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use Illuminate\Support\Facades\Storage;
use App\Filament\Resources\Posts\Pages\ListPosts;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);

    Storage::fake('public');
});

it('shows columns, filters and renders posts', function () {
    $post = Post::factory()->for(User::factory())->create();

    livewire(ListPosts::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$post])
        ->assertTableColumnExists('image_path')
        ->assertTableColumnExists('title')
        ->assertTableColumnExists('user.name')
        ->assertTableColumnExists('canonical_url')
        ->assertTableColumnExists('categories')
        ->assertTableColumnExists('sessions_count')
        ->assertTableColumnExists('published_at')
        ->assertTableColumnExists('modified_at')
        ->assertTableFilterExists('image_path')
        ->assertTableFilterExists('link_association')
        ->assertTableFilterExists('published_at')
        ->assertTableFilterExists('updated_stale')
        ->assertTableFilterExists('trashed');
});

it('searches posts by title and author', function () {
    $match = Post::factory()->for(User::factory(['name' => 'Matched Author']))->create([
        'title' => 'Unique search title',
    ]);

    $other = Post::factory()->for(User::factory(['name' => 'Other Author']))->create([
        'title' => 'Different',
    ]);

    livewire(ListPosts::class)
        ->searchTable('Unique search title')
        ->assertCanSeeTableRecords([$match])
        ->assertCanNotSeeTableRecords([$other]);

    livewire(ListPosts::class)
        ->searchTable('Matched Author')
        ->assertCanSeeTableRecords([$match])
        ->assertCanNotSeeTableRecords([$other]);
});

it('sorts newest first by default', function () {
    $older = Post::factory()->create(['id' => 1]);
    $newer = Post::factory()->create(['id' => 2]);

    livewire(ListPosts::class)
        ->assertCanSeeTableRecords([$newer, $older], inOrder: true);
});

it('filters by published status and image presence', function () {
    $publishedWithImage = Post::factory()->create([
        'published_at' => now()->subDay(),
        'image_path' => 'images/posts/foo.jpg',
        'image_disk' => 'public',
    ]);

    $draftNoImage = Post::factory()->create([
        'published_at' => null,
        'image_path' => null,
        'image_disk' => null,
    ]);

    livewire(ListPosts::class)
        ->filterTable('published_at', true)
        ->assertCanSeeTableRecords([$publishedWithImage])
        ->assertCanNotSeeTableRecords([$draftNoImage]);

    livewire(ListPosts::class)
        ->filterTable('image_path', false)
        ->assertCanSeeTableRecords([$draftNoImage])
        ->assertCanNotSeeTableRecords([$publishedWithImage]);
});

it('filters by link association', function () {
    $withLink = Link::factory()->withPost()->create()->post;
    $withoutLink = Post::factory()->create();

    livewire(ListPosts::class)
        ->filterTable('link_association', 'with_link')
        ->assertCanSeeTableRecords([$withLink])
        ->assertCanNotSeeTableRecords([$withoutLink]);

    livewire(ListPosts::class)
        ->filterTable('link_association', 'without_link')
        ->assertCanSeeTableRecords([$withoutLink])
        ->assertCanNotSeeTableRecords([$withLink]);
});

it('filters by updated stale status', function () {
    $stale = Post::factory()->create([
        'modified_at' => now()->subYears(2),
        'published_at' => now()->subYears(2),
    ]);

    $fresh = Post::factory()->create([
        'modified_at' => now()->subMonths(3),
        'published_at' => now()->subMonths(3),
    ]);

    livewire(ListPosts::class)
        ->filterTable('updated_stale', true)
        ->assertCanSeeTableRecords([$stale])
        ->assertCanNotSeeTableRecords([$fresh]);

    livewire(ListPosts::class)
        ->filterTable('updated_stale', false)
        ->assertCanSeeTableRecords([$fresh])
        ->assertCanNotSeeTableRecords([$stale]);
});

// Bulk actions covered elsewhere; focus on filters and rendering here.
