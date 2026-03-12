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

it('allows large image previews and exposes website schema', function () {
    get('/')
        ->assertSee('<meta name="robots" content="max-image-preview:large" />', escape: false)
        ->assertSee('"@type": "WebSite"', escape: false);
});
