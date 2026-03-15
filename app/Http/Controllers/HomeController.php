<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

/**
 * Coordinates a single-action HTTP endpoint for the site.
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

        $aboutUser = User::query()
            ->where('github_login', 'benjamincrozat')
            ->first();

        return view('home', compact('latest', 'links', 'aboutUser'));
    }
}
