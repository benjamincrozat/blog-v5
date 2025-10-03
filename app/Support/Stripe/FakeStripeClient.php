<?php

namespace App\Support\Stripe;

use Illuminate\Support\Str;

class FakeStripeClient
{
    public FakeStripeCheckout $checkout;

    /**
     * @param  array<string,mixed>  $config
     */
    public function __construct(protected array $config = [])
    {
        $this->checkout = new FakeStripeCheckout();
    }
}

class FakeStripeCheckout
{
    public FakeStripeCheckoutSessions $sessions;

    public function __construct()
    {
        $this->sessions = new FakeStripeCheckoutSessions();
    }
}

class FakeStripeCheckoutSessions
{
    /**
     * @var array<string,object>
     */
    protected static array $sessions = [];

    /**
     * @param  array<string,mixed>  $parameters
     */
    public function create(array $parameters)
    {
        $session = $this->makeSession($parameters);

        static::$sessions[$session->id] = $session;

        return $session;
    }

    /**
     * @param  array<string,mixed>  $parameters
     */
    public function retrieve(string $sessionId, array $parameters = [])
    {
        if (! array_key_exists($sessionId, static::$sessions)) {
            throw new \InvalidArgumentException("Checkout session [{$sessionId}] not found.");
        }

        return static::$sessions[$sessionId];
    }

    /**
     * @param  array<string,mixed>  $parameters
     */
    protected function makeSession(array $parameters) : object
    {
        $sessionId = 'cs_test_' . Str::random(24);

        $lineItems = array_map(function (array $item) {
            return [
                'quantity' => $item['quantity'] ?? 1,
                'amount_total' => 0,
                'currency' => 'usd',
                'price' => [
                    'id' => $item['price'] ?? 'price_fake',
                    'product' => [
                        'name' => 'Sponsored Article',
                        'description' => 'Sponsored article placement',
                    ],
                ],
            ];
        }, $parameters['line_items'] ?? []);

        $session = [
            'id' => $sessionId,
            'object' => 'checkout.session',
            'amount_subtotal' => 0,
            'amount_total' => 0,
            'currency' => 'usd',
            'line_items' => [
                'data' => $lineItems,
            ],
            'total_details' => [
                'amount_tax' => 0,
            ],
            'customer_details' => [
                'email' => 'customer@example.com',
            ],
            'invoice' => [
                'hosted_invoice_url' => 'https://stripe.com/invoice/' . $sessionId,
            ],
            'url' => 'https://checkout.stripe.com/pay/' . $sessionId,
        ];

        if (isset($parameters['success_url'])) {
            $session['success_url'] = $parameters['success_url'];
        }

        if (isset($parameters['cancel_url'])) {
            $session['cancel_url'] = $parameters['cancel_url'];
        }

        return json_decode(json_encode($session));
    }
}
