<?php

use App\Models\ShortUrl;

it('generates the code on creation', function () {
    $shortUrl = ShortUrl::query()->create([
        'url' => 'https://example.com',
    ]);

    expect($shortUrl->code)->toBeString();
});

it('keeps an explicitly provided code untouched', function () {
    $shortUrl = ShortUrl::query()->create([
        'url' => 'https://example.com',
        'code' => 'abcde',
    ]);

    expect($shortUrl->code)->toBe('abcde');
});

it('has a link attribute', function () {
    $shortUrl = ShortUrl::factory()->create();

    expect($shortUrl->link)->toBe('https://' . config('app.url_shortener_domain') . '/' . $shortUrl->code);
});
