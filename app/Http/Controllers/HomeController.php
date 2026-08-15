<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Post;
use Illuminate\View\View;

/**
 * Loads the article and community-link sections for the public home page.
 *
 * It shows up to 12 published posts, with recent sponsors first, and up to 12
 * approved links. Posts that already represent community links are left out of
 * the article section so the same item does not appear twice.
 */
class HomeController extends Controller
{
    public function __invoke() : View
    {
        $latest = Post::query()
            ->withCount('comments')
            ->published()
            ->sponsored()
            ->latest('published_at')
            ->whereDoesntHave('link')
            ->limit(12)
            ->get();

        $links = Link::query()
            ->latest('is_approved')
            ->approved()
            ->limit(12)
            ->get();

        return view('home', compact('latest', 'links'));
    }
}
