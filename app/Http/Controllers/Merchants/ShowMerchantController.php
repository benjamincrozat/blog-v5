<?php

namespace App\Http\Controllers\Merchants;

use Illuminate\Support\Uri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Redirects merchant slugs to their configured outbound recommendation links.
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
