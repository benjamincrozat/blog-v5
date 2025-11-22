<?php

namespace App\Http\Controllers\Checkout;

use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Cashier\Cashier;
use App\Http\Controllers\Controller;
use Stripe\Exception\ApiErrorException;

class CompletedCheckoutController extends Controller
{
    public function __invoke(Request $request) : View
    {
        $session = cache()->remember(
            key: "checkout.session.$request->session_id",
            ttl: now()->addHour(),
            callback: fn () => Cashier::stripe()->checkout->sessions->retrieve(
                $request->session_id, [
                    'expand' => [
                        'customer',
                        'invoice',
                        'line_items.data.price.product',
                        'line_items',
                        'payment_intent.latest_charge',
                        'payment_intent',
                        'subscription',
                    ],
                ]
            )
        );

        $subscriptionDetails = $this->subscriptionDetails($session);
        $manageSubscriptionUrl = $this->manageSubscriptionUrl($session, $subscriptionDetails);

        return view('checkout.completed', compact('session', 'subscriptionDetails', 'manageSubscriptionUrl'));
    }

    protected function subscriptionDetails(object $session) : ?array
    {
        if (empty($session->subscription)) {
            return null;
        }

        $lineItem = $session->line_items->data[0] ?? null;

        if (null === $lineItem) {
            return null;
        }

        $interval = $lineItem->price->recurring->interval ?? null;
        $intervalCount = $lineItem->price->recurring->interval_count ?? 1;
        $nextRenewalTimestamp = $session->subscription->current_period_end ?? null;

        return [
            'plan_name' => $lineItem->price->product->name ?? null,
            'plan_description' => $lineItem->price->product->description ?? null,
            'interval_label' => $this->formatIntervalLabel($interval, $intervalCount),
            'next_renewal' => $nextRenewalTimestamp
                ? Carbon::createFromTimestamp($nextRenewalTimestamp)->timezone(config('app.timezone'))
                : null,
        ];
    }

    protected function manageSubscriptionUrl(object $session, ?array $subscriptionDetails) : ?string
    {
        if (null === $subscriptionDetails || empty($session->customer)) {
            return null;
        }

        try {
            $portalSession = Cashier::stripe()->billingPortal->sessions->create([
                'customer' => $session->customer,
                'return_url' => route('checkout.completed', [
                    'session_id' => $session->id,
                ]),
            ]);
        } catch (ApiErrorException) {
            return null;
        }

        return $portalSession->url ?? null;
    }

    protected function formatIntervalLabel(?string $interval, int $intervalCount) : ?string
    {
        if (null === $interval) {
            return null;
        }

        $normalizedInterval = match ($interval) {
            'day' => 'daily',
            'week' => 'weekly',
            'month' => 'monthly',
            'year' => 'yearly',
            default => $interval,
        };

        if (1 === $intervalCount) {
            return ucfirst($normalizedInterval);
        }

        return 'Every ' . $intervalCount . ' ' . Str::plural($interval, $intervalCount);
    }
}
