<?php

use function Pest\Laravel\get;

it('starts a checkout session for a subscription product', function () {
    get(route('checkout.start', 'sticky_carousel'))
        ->assertRedirectContains('checkout.stripe.com');
});

it('starts a checkout session for a one-time product', function () {
    get(route('checkout.start', 'sponsored_article'))
        ->assertRedirectContains('checkout.stripe.com');
});

it('throws a 404 if the product slug does not exist', function () {
    get(route('checkout.start', 'non_existing_product'))
        ->assertNotFound();
});
