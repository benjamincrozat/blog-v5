<?php

use Laravel\Cashier\Checkout;

use function Pest\Laravel\get;

use Stripe\Checkout\Session as StripeSession;

it('renders a page when the checkout is completed', function () {
    $session = Checkout::guest()
        ->create([
            [
                'price' => config('products.sticky_carousel.price_id'),
                'quantity' => 1,
            ],
        ], [
            'mode' => StripeSession::MODE_SUBSCRIPTION,
            'automatic_tax' => ['enabled' => true],
            'billing_address_collection' => 'required',
            'success_url' => url('/foo'),
            'cancel_url' => url('/bar'),
        ])
        ->asStripeCheckoutSession();

    get(route('checkout.completed', [
        'session_id' => $session->id,
    ]))
        ->assertOk();
});
