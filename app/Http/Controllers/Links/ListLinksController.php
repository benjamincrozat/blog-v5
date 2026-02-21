<?php

namespace App\Http\Controllers\Links;

use App\Models\Link;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

/**
 * Defines the ListLinksController implementation.
 */
class ListLinksController extends Controller
{
    public function __invoke() : View
    {
        $distinctUsersQuery = Link::query()
            ->select('user_id')
            ->distinct('user_id')
            ->whereRelation('user', fn (Builder $query) => $query->where('github_login', '!=', 'benjamincrozat'))
            ->approved();

        return view('links.index', [
            'distinctUserAvatars' => $distinctUsersQuery
                ->whereRelation('user', fn (Builder $query) => $query->whereNotNull('avatar'))
                ->inRandomOrder()
                ->limit(10)
                ->get()
                ->map(fn (Link $link) => $link->user->avatar),

            'distinctUsersCount' => $distinctUsersQuery->count(),

            'links' => Link::query()
                ->latest('is_approved')
                ->approved()
                ->paginate(12),
        ]);
    }
}
