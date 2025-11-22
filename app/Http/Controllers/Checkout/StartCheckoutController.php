<?php

namespace App\Http\Controllers\Checkout;

use Laravel\Cashier\Checkout;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Stripe\Checkout\Session as StripeSession;

class StartCheckoutController extends Controller
{
    public function __invoke(string $slug) : RedirectResponse
    {
        $products = config()->array('products', []);

        $product = $products[$slug] ?? null;

        if (! is_array($product) || empty($product['price_id'])) {
            abort(404);
        }

        $isSubscription = $product['subscription'] ?? false;

        $checkout = Checkout::guest()
            ->create([
                [
                    'price' => $product['price_id'],
                    'quantity' => 1,
                ],
            ], [
                'mode' => $isSubscription
                    ? StripeSession::MODE_SUBSCRIPTION
                    : StripeSession::MODE_PAYMENT,
                'billing_address_collection' => 'required',
                'success_url' => route('checkout.completed') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => back()->getTargetUrl(),
            ]);

        return $checkout->redirect();
    }
}
