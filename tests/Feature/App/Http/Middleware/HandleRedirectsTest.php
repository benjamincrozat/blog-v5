<?php

use App\Models\Post;
use App\Models\Redirect;
use Illuminate\Http\Request;

use function Pest\Laravel\get;

use App\Http\Middleware\HandleRedirects;

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

it('removes trailing slashes and keeps the query string', function () {
    $response = app(HandleRedirects::class)->handle(
        Request::create('/blog/?utm=abc', 'GET'),
        fn () => response('continued'),
    );

    expect($response->getStatusCode())->toBe(301)
        ->and($response->headers->get('Location'))->toEndWith('/blog?utm=abc');
});
