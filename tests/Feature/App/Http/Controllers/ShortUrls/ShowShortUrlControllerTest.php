<?php

use App\Models\ShortUrl;

use function Pest\Laravel\get;

use Illuminate\Support\Facades\Http;

beforeEach(fn () => Http::allowStrayRequests());

it('redirects to the short URL', function () {
    $shortUrl = ShortUrl::factory()->create();

    get(route('shortUrl.show', $shortUrl))
        ->assertStatus(302)
        ->assertRedirect($shortUrl->url);
});

it('throws a 404 if the short URL does not exist', function () {
    get(route('shortUrl.show', 'non-existing'))
        ->assertNotFound();
});
