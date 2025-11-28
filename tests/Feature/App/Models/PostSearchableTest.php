<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;

it('serializes searchable post data with categories', function () {
    $user = User::factory()->create([
        'name' => 'Alice Writer',
    ]);

    $categories = Category::factory()->count(2)->create();

    $post = Post::factory()
        ->for($user)
        ->create([
            'title' => 'Laravel Tips',
            'slug' => 'laravel-tips',
            'content' => 'Use Pest for testing.',
            'description' => 'Handy Laravel tips.',
            'published_at' => now(),
        ]);

    $post->categories()->sync($categories->pluck('id'));

    expect($post->toSearchableArray())->toMatchArray([
        'user_name' => 'Alice Writer',
        'title' => 'Laravel Tips',
        'slug' => 'laravel-tips',
        'content' => 'Use Pest for testing.',
        'description' => 'Handy Laravel tips.',
        'categories' => $categories->pluck('name')->toArray(),
    ]);
});

it('is searchable only when the post is published', function () {
    $published = Post::factory()->create([
        'published_at' => now(),
    ]);

    $draft = Post::factory()->create([
        'published_at' => null,
    ]);

    expect($published->shouldBeSearchable())->toBeTrue();
    expect($draft->shouldBeSearchable())->toBeFalse();
});
