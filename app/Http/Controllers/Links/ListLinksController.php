<?php

namespace App\Http\Controllers\Links;

use App\Models\Link;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;
use Illuminate\Database\Eloquent\Builder;

/**
 * Lists approved community links with contributor avatars and breadcrumb schema data.
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
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
