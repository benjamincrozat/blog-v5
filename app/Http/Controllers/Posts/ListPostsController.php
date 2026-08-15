<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;

/**
 * Builds the main list of published blog posts.
 *
 * Posts that represent community links are left out. Recent sponsors come first,
 * then the newest publication date, and results are split into pages of 24. The
 * visible breadcrumbs and JSON-LD use the same list. Page numbers beyond the
 * available results return 404 instead of publishing empty archive pages.
 */
class ListPostsController extends Controller
{
    public function __invoke() : View
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog'],
        ];

        $posts = Post::query()
            ->published()
            ->sponsored()
            ->latest('published_at')
            ->whereDoesntHave('link')
            ->paginate(24);

        abort_if($posts->currentPage() > $posts->lastPage(), 404);

        return view('posts.index', [
            'posts' => $posts,
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
