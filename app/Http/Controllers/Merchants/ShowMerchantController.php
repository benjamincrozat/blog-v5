<?php

namespace App\Http\Controllers\Merchants;

use App\Models\Tool;
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
        $toolLink = Tool::query()
            ->published()
            ->where('slug', $slug)
            ->value('outbound_url');

        $merchantLink = $toolLink ?: collect(config('merchants'))
            ->flatMap(function (array $items) {
                return collect($items)->map(
                    fn (mixed $item) => $item['link'] ?? $item
                );
            })
            ->get($slug);

        abort_if(blank($merchantLink), 404);

        return redirect()->away(
            Uri::of($merchantLink)
                ->withQuery(request()->all())
        );
    }
}
