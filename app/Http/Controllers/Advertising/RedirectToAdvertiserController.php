<?php

namespace App\Http\Controllers\Advertising;

use Illuminate\Support\Uri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

/**
 * Sends an advertising link to the destination set for its slug.
 *
 * It keeps query values from the incoming link and adds the blog's UTM source
 * when one was not already given. An unknown or disabled advertising slug returns
 * a 404 instead of sending the visitor to a guessed or empty URL.
 */
class RedirectToAdvertiserController extends Controller
{
    public function __invoke(Request $request, string $slug) : RedirectResponse
    {
        if (! $adUrl = config("advertisers.$slug")) {
            abort(404);
        }

        return redirect(
            Uri::of($adUrl)->withQuery($request->query() + [
                'utm_source' => 'benjamin_crozat',
            ])
        );
    }
}
