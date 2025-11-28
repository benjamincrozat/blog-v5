<?php

use function Pest\Laravel\get;

it('starts a subscription checkout session for the sticky carousel product', function () {
    get(route('checkout.start', 'sticky_carousel'))->assertRedirectContains('checkout.stripe.com');
});

it('starts a one-time checkout session for the sponsored article product', function () {
    get(route('checkout.start', 'sponsored_article'))->assertRedirectContains('checkout.stripe.com');
});

it('responds with 404 for unknown product slugs', function () {
    get(route('checkout.start', 'non_existing_product'))->assertNotFound();
});
