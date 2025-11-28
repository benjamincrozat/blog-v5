<?php

use Stripe\StripeClient;
use Laravel\Cashier\Checkout;

use function Pest\Laravel\get;

use Illuminate\Support\Carbon;
use Stripe\Exception\ApiConnectionException;
use Stripe\Checkout\Session as StripeSession;
use App\Http\Controllers\Checkout\CompletedCheckoutController;

class TestableCompletedCheckoutController extends CompletedCheckoutController
{
    public function exposeSubscriptionDetails(object $session) : ?array
    {
        return $this->subscriptionDetails($session);
    }

    public function exposeManageSubscriptionUrl(object $session, ?array $details) : ?string
    {
        return $this->manageSubscriptionUrl($session, $details);
    }

    public function exposeFormatIntervalLabel(?string $interval, int $count) : ?string
    {
        return $this->formatIntervalLabel($interval, $count);
    }
}

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

it('returns descriptive subscription details when available', function () {
    $session = json_decode(json_encode([
        'subscription' => [
            'current_period_end' => Carbon::parse('2024-03-01 12:00:00', config('app.timezone'))->timestamp,
        ],
        'line_items' => [
            'data' => [[
                'price' => [
                    'product' => [
                        'name' => 'Pro Plan',
                        'description' => 'Monthly plan.',
                    ],
                    'recurring' => [
                        'interval' => 'month',
                        'interval_count' => 2,
                    ],
                ],
            ]],
        ],
    ]));

    $details = (new TestableCompletedCheckoutController)->exposeSubscriptionDetails($session);

    expect($details)->toMatchArray([
        'plan_name' => 'Pro Plan',
        'plan_description' => 'Monthly plan.',
        'interval_label' => 'Every 2 months',
    ]);
    expect($details['next_renewal'])->toBeInstanceOf(\Carbon\Carbon::class);
});

it('returns null subscription details when information is missing', function () {
    $session = (object) ['subscription' => null];

    expect((new TestableCompletedCheckoutController)->exposeSubscriptionDetails($session))->toBeNull();
});

it('returns null subscription details when line items are absent', function () {
    $session = (object) [
        'subscription' => (object) [],
        'line_items' => (object) ['data' => []],
    ];

    expect((new TestableCompletedCheckoutController)->exposeSubscriptionDetails($session))->toBeNull();
});

it('returns subscription details without renewal date when timestamp is missing', function () {
    $session = json_decode(json_encode([
        'subscription' => [
            'current_period_end' => null,
        ],
        'line_items' => [
            'data' => [[
                'price' => [
                    'product' => ['name' => 'Starter'],
                    'recurring' => [
                        'interval' => 'day',
                        'interval_count' => 1,
                    ],
                ],
            ]],
        ],
    ]));

    $details = (new TestableCompletedCheckoutController)->exposeSubscriptionDetails($session);

    expect($details['next_renewal'])->toBeNull();
});

it('generates manage subscription urls when customer information exists', function () {
    $portalSessions = new class
    {
        public array $payloads = [];

        public function create(array $payload)
        {
            $this->payloads[] = $payload;

            return (object) ['url' => 'https://portal.example.com/session'];
        }
    };

    $stripeClient = new class($portalSessions)
    {
        public function __construct(public $portalSessions)
        {
            $this->billingPortal = (object) ['sessions' => $this->portalSessions];
        }
    };

    app()->bind(StripeClient::class, fn () => $stripeClient);

    $session = (object) [
        'id' => 'sess_123',
        'customer' => 'cus_321',
    ];

    $url = (new TestableCompletedCheckoutController)->exposeManageSubscriptionUrl($session, ['plan_name' => 'Pro']);

    expect($url)->toBe('https://portal.example.com/session');
    expect($portalSessions->payloads)->toHaveCount(1);
    expect($portalSessions->payloads[0]['return_url'])->toBe(route('checkout.completed', ['session_id' => 'sess_123']));
});

it('returns null manage subscription urls when billing portal creation fails', function () {
    $portalSessions = new class
    {
        public function create()
        {
            throw new ApiConnectionException('Network error.');
        }
    };

    $stripeClient = new class($portalSessions)
    {
        public function __construct(public $portalSessions)
        {
            $this->billingPortal = (object) ['sessions' => $this->portalSessions];
        }
    };

    app()->bind(StripeClient::class, fn () => $stripeClient);

    $session = (object) ['id' => 'sess_123', 'customer' => 'cus_321'];

    expect((new TestableCompletedCheckoutController)->exposeManageSubscriptionUrl($session, ['plan_name' => 'Pro']))->toBeNull();
});

it('returns null manage subscription urls when subscription details are missing', function () {
    $session = (object) [
        'id' => 'sess_missing',
        'customer' => 'cus_missing',
    ];

    expect((new TestableCompletedCheckoutController)->exposeManageSubscriptionUrl($session, null))->toBeNull();
});

it('returns null manage subscription urls when customer is missing', function () {
    $session = (object) [
        'id' => 'sess_missing_customer',
        'customer' => null,
    ];

    expect((new TestableCompletedCheckoutController)->exposeManageSubscriptionUrl($session, ['plan_name' => 'Any']))->toBeNull();
});

it('formats interval labels for singular and plural values', function () {
    $controller = new TestableCompletedCheckoutController;

    expect($controller->exposeFormatIntervalLabel('month', 1))->toBe('Monthly');
    expect($controller->exposeFormatIntervalLabel('week', 3))->toBe('Every 3 weeks');
    expect($controller->exposeFormatIntervalLabel('year', 1))->toBe('Yearly');
    expect($controller->exposeFormatIntervalLabel(null, 1))->toBeNull();
    expect($controller->exposeFormatIntervalLabel('quarter', 2))->toBe('Every 2 quarters');
});
