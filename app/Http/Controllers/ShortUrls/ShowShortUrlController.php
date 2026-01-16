<?php

namespace App\Http\Controllers\ShortUrls;

use App\Jobs\TrackEvent;
use App\Models\ShortUrl;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Redirects a short URL to its destination and tracks the click.
 *
 * Extracted as a single-action controller to keep shortener routing thin.
 * Callers can rely on the short URL model being resolved from route binding.
 */
class ShowShortUrlController extends Controller
{
    public function __invoke(ShortUrl $shortUrl) : RedirectResponse
    {
        if (request()->ip() && request()->userAgent()) {
            TrackEvent::dispatchAfterResponse(
                'Clicked on short URL',
                ['url' => $shortUrl->url],
                request()->fullUrl(),
                request()->ip(),
                request()->userAgent(),
                request()->header('Accept-Language', ''),
                request()->header('Referer', ''),
            );
        }

        return redirect()->away($shortUrl->url);
    }
}
