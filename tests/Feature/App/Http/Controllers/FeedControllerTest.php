<?php

use App\Models\Post;

use function Pest\Laravel\get;

it('generates the atom feed', function () {
    $post = Post::factory()->create([
        'content' => 'UniqueContentFromTest',
        'description' => 'Short description',
    ]);

    get(route('feeds.main'))
        ->assertOk()
        ->assertSee('Short description')
        ->assertDontSee('UniqueContentFromTest');
});
