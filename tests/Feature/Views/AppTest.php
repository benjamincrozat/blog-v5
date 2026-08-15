<?php

use function Pest\Laravel\get;

it('has a default title', function () {
    get('/')
        ->assertSee('title', config('app.name'));
});

it('has a default description', function () {
    get('/')
        ->assertSee('<meta name="description" content="', false);
});

it('signals the Atom feed', function () {
    get('/')
        ->assertSee('application/atom+xml', escape: false);
});

it('serves the public site fonts locally', function () {
    get('/')
        ->assertSee('font-family: "Outfit"', escape: false)
        ->assertDontSee('fonts.googleapis.com', escape: false);
});

it('allows large image previews and exposes website schema', function () {
    $response = get('/')
        ->assertSee('<meta name="robots" content="max-image-preview:large" />', escape: false);

    preg_match('/<script type="application\/ld\+json">\s*(?<schema>.*?)\s*<\/script>/s', $response->getContent(), $matches);

    $schema = json_decode($matches['schema'] ?? '', true, flags: JSON_THROW_ON_ERROR);

    expect($schema['@context'] ?? null)->toBe('https://schema.org')
        ->and(collect($schema['@graph'] ?? [])->pluck('@type')->all())
        ->toBe(['Organization', 'WebSite']);
});
