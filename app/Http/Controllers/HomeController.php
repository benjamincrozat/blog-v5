<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke() : View
    {
        // Fetch popular and latest posts separately…
        $popular = Post::query()
            ->published()
            ->whereDoesntHave('link')
            ->where('sessions_count', '>', 0)
            ->orderBy('sessions_count', 'desc')
            ->limit(6)
            ->get();

        $jobs = Job::query()
            ->latest()
            ->paginate(6);

        $latest = Post::query()
            ->published()
            ->sponsored()
            ->latest('published_at')
            ->whereDoesntHave('link')
            ->limit(12)
            ->get();

        // … then eager-load the heavy relationships ONCE for both collections.
        // FYI, without this, Model::automaticallyEagerLoadRelationships()
        // would have loaded the relationships twice later in the view.
        $popular->concat($latest)->load('categories', 'user');

        $links = Link::query()
            ->latest('is_approved')
            ->approved()
            ->limit(12)
            ->get();

        $aboutUser = User::query()
            ->where('github_login', 'benjamincrozat')
            ->first();

        return view('home', compact('popular', 'jobs', 'latest', 'links', 'aboutUser'));
    }
}
