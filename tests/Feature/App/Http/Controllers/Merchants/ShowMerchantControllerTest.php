<?php

use App\Models\Tool;

use function Pest\Laravel\get;

it('redirects tool slugs from the database with the same query parameters', function () {
    Tool::factory()->create([
        'slug' => 'remodex',
        'outbound_url' => 'https://github.com/Emanuele-web04/remodex',
        'published_at' => now()->subMinute(),
    ]);

    get(route('merchants.show', ['remodex', 'foo' => 'bar']))
        ->assertRedirectContains('https://github.com/Emanuele-web04/remodex?foo=bar');
});

it('redirects to the merchant with the same query parameters', function () {
    get(route('merchants.show', ['ploi', 'foo' => 'bar']))
        ->assertRedirectContains(config('merchants.services.ploi') . '&foo=bar');
});

test('it throws 404 when merchant does not exist', function () {
    get(route('merchants.show', 'foo'))
        ->assertNotFound();
});
