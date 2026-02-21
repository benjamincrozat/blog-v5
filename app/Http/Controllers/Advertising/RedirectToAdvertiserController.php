<?php

namespace App\Http\Controllers\Advertising;

use App\Jobs\TrackEvent;
use Illuminate\Support\Uri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Defines the RedirectToAdvertiserController implementation.
 */
class RedirectToAdvertiserController extends Controller
{
    public function __invoke(Request $request, string $slug) : RedirectResponse
    {
        if (! $adUrl = config("advertisers.$slug")) {
            abort(404);
        }

        if (! empty($request->fullUrl()) &&
            ($ip = $request->ip()) &&
            ($userAgent = $request->userAgent())) {
            TrackEvent::dispatchAfterResponse(
                'Clicked on ad',
                [
                    'slug' => $slug,
                    'url' => $adUrl,
                ],
                $request->fullUrl(),
                $ip,
                $userAgent,
                $request->header('Accept-Language', ''),
                $request->header('Referer', ''),
            );
        }

        return redirect(
            Uri::of($adUrl)->withQuery($request->query() + [
                'utm_source' => 'benjamin_crozat',
            ])
        );
    }
}
