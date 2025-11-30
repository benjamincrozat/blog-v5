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
