<?php

namespace App\Http\Controllers\Merchants;

use App\Jobs\TrackEvent;
use Illuminate\Support\Uri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Handles show merchant controller requests.
 */
class ShowMerchantController extends Controller
{
    public function __invoke(Request $request, string $slug) : RedirectResponse
    {
        abort_if(
            ! $merchantLink = collect(config('merchants'))
                ->flatMap(function (array $items) {
                    return collect($items)->map(
                        fn (mixed $item) => $item['link'] ?? $item
                    );
                })
                ->get($slug),
            404
        );

        if (! empty($request->fullUrl()) &&
            ($ip = $request->ip()) &&
            ($userAgent = $request->userAgent())) {
            TrackEvent::dispatchAfterResponse(
                'Clicked on merchant',
                [
                    'slug' => $slug,
                    'url' => $merchantLink,
                ],
                $request->fullUrl(),
                $ip,
                $userAgent,
                $request->header('Accept-Language', ''),
                $request->header('Referer', ''),
            );
        }

        return redirect()->away(
            Uri::of($merchantLink)
                ->withQuery(request()->all())
        );
    }
}
