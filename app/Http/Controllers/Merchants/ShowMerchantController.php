<?php

namespace App\Http\Controllers\Merchants;

use Illuminate\Support\Uri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Sends a recommendation link to the merchant URL set for its slug.
 *
 * It finds the slug across the grouped merchant settings and keeps the incoming
 * query values on the destination. An unknown slug returns 404, so only listed
 * recommendations can send visitors away from the site.
 */
class ShowMerchantController extends Controller
{
    public function __invoke(Request $request, string $slug) : RedirectResponse
    {
        abort_if(
            ! $merchantLink = collect(config('merchants'))
                ->flatMap(fn (array $items) => $items)
                ->get($slug),
            404
        );

        return redirect()->away(
            Uri::of($merchantLink)
                ->withQuery(request()->all())
        );
    }
}
