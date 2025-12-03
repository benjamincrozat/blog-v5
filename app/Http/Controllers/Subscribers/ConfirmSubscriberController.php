<?php

namespace App\Http\Controllers\Subscribers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ConfirmSubscriberController extends Controller
{
    public function __invoke(Request $request, Subscriber $subscriber) : RedirectResponse
    {
        if (! $subscriber->needsConfirmation()) {
            return to_route('newsletter')
                ->with([
                    'status' => 'Thanks, but you already confirmed your subscription.',
                    'status_type' => 'info',
                ]);
        }

        if (! $subscriber->tokenMatches($request->query('token'))) {
            return to_route('newsletter')
                ->with([
                    'status' => 'This confirmation link is invalid or has expired.',
                    'status_type' => 'error',
                ]);
        }

        $subscriber->markAsConfirmed();

        return to_route('newsletter')
            ->with([
                'status' => 'Thanks for confirming your subscription!',
                'status_type' => 'success',
            ]);
    }
}
