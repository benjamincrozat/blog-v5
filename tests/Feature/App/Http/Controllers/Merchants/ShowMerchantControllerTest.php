<?php

use function Pest\Laravel\get;

it('redirects to the merchant with the same query parameters', function () {
    get(route('merchants.show', ['ploi', 'foo' => 'bar']))
        ->assertRedirectContains(config('merchants.services.ploi') . '&foo=bar');
});

test('it throws 404 when merchant does not exist', function () {
    get(route('merchants.show', 'foo'))
        ->assertNotFound();
});
