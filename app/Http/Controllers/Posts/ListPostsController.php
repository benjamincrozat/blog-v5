<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\View\View;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;

/**
 * Coordinates a single-action HTTP endpoint for the site.
 */
class ListPostsController extends Controller
{
    public function __invoke() : View
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog'],
        ];

        return view('posts.index', [
            'posts' => Post::query()
                ->published()
                ->sponsored()
                ->latest('published_at')
                ->whereDoesntHave('link')
                ->paginate(24),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
        ]);
    }
}
