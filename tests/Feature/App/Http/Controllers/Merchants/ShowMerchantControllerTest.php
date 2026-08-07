<?php

use function Pest\Laravel\get;

it('redirects to the merchant with the same query parameters', function () {
    get(route('merchants.show', ['ploi', 'foo' => 'bar']))
        ->assertRedirectContains(config('merchants.services.ploi') . '&foo=bar');
});

it('redirects book recommendations', function () {
    get(route('merchants.show', 'battle-ready-laravel'))
        ->assertRedirect(config('merchants.books.battle-ready-laravel'));
});

test('it throws 404 when merchant does not exist', function () {
    get(route('merchants.show', 'foo'))
        ->assertNotFound();
});
