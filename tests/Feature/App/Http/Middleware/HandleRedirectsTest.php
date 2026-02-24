<?php

use App\Models\Post;
use App\Models\Redirect;

use function Pest\Laravel\get;

it('redirects to the new slug when a redirect exists', function () {
    $post = Post::factory()->create(['slug' => 'bar']);

    Redirect::query()->create([
        'from' => 'foo',
        'to' => 'bar',
    ]);

    get('/foo')
        ->assertRedirect('/bar')
        ->assertStatus(301);
});

it('preserves query string parameters on redirect', function () {
    $post = Post::factory()->create(['slug' => 'bar']);

    Redirect::query()->create([
        'from' => 'foo',
        'to' => 'bar',
    ]);

    get('/foo?utm=abc')
        ->assertRedirect('/bar?utm=abc');
});

it('returns 404 when no post or redirect exists', function () {
    get('/non-existent-slug')
        ->assertNotFound();
});
