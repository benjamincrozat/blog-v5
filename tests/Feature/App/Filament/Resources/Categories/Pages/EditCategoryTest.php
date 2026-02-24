<?php

use App\Models\User;
use App\Models\Category;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

use App\Filament\Resources\Categories\Pages\EditCategory;

beforeEach(function () {
    $admin = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    actingAs($admin);
});

it('locks slug changes on existing categories', function () {
    $category = Category::factory()->create([
        'name' => 'Old Name',
        'slug' => 'old-slug',
    ]);

    livewire(EditCategory::class, ['record' => $category->getKey()])
        ->fillForm([
            'name' => 'New Name',
            'slug' => 'new-slug',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($category->refresh()->name)->toBe('New Name')
        ->and($category->slug)->toBe('old-slug');
});

