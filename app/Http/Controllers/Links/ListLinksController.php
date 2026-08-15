<?php

namespace App\Http\Controllers\Links;

use App\Models\Link;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;
use Illuminate\Database\Eloquent\Builder;

/**
 * Builds the public list of approved community links and their contributors.
 *
 * Pending and declined links are left out. The page also shows random avatars
 * from up to 10 different contributors other than the site owner and counts that
 * contributor group. Page numbers beyond the available results return 404. The
 * visible breadcrumbs and JSON-LD share the same data.
 */
class ListLinksController extends Controller
{
    public function __invoke() : View
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Links'],
        ];

        $distinctUsersQuery = Link::query()
            ->select('user_id')
            ->distinct('user_id')
            ->whereRelation('user', fn (Builder $query) => $query->where('github_login', '!=', 'benjamincrozat'))
            ->approved();

        $links = Link::query()
            ->latest('is_approved')
            ->approved()
            ->paginate(12);

        abort_if($links->currentPage() > $links->lastPage(), 404);

        return view('links.index', [
            'distinctUserAvatars' => $distinctUsersQuery
                ->whereRelation('user', fn (Builder $query) => $query->whereNotNull('avatar'))
                ->inRandomOrder()
                ->limit(10)
                ->get()
                ->map(fn (Link $link) => $link->user->avatar),

            'distinctUsersCount' => $distinctUsersQuery->count(),

            'links' => $links,
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
