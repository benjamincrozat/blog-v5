<?php

namespace App\Http\Controllers\Subscribers;

use App\Models\User;
use App\Models\Subscriber;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Notifications\SubscriberConfirmed;

/**
 * Confirms a newsletter subscription from a signed link.
 *
 * Extracted to keep confirmation logic out of route files and views.
 * Callers can rely on a redirect back to the newsletter page with a status message.
 */
class ConfirmSubscriberController extends Controller
{
    public function __invoke(Subscriber $subscriber) : RedirectResponse
    {
        if (! $subscriber->needsConfirmation()) {
            return to_route('newsletter')
                ->with([
                    'status' => 'Thanks, but you already confirmed your subscription.',
                    'status_type' => 'info',
                ]);
        }

        if (! $subscriber->tokenMatches(request()->query('token'))) {
            return to_route('newsletter')
                ->with([
                    'status' => 'This confirmation link is invalid or has expired.',
                    'status_type' => 'error',
                ]);
        }

        $subscriber->markAsConfirmed();

        User::query()
            ->where('github_login', 'benjamincrozat')
            ->first()
            ?->notify(new SubscriberConfirmed($subscriber));

        return to_route('newsletter')
            ->with([
                'status' => 'Thanks for confirming your subscription!',
                'status_type' => 'success',
            ]);
    }
}
