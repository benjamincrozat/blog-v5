<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use Illuminate\View\View;

/**
 * Handles home controller requests.
 */
class HomeController extends Controller
{
    public function __invoke() : View
    {
        $latest = Post::query()
            ->select([
                'id',
                'user_id',
                'slug',
                'title',
                'description',
                'content',
                'image_disk',
                'image_path',
                'is_commercial',
                'sponsored_at',
                'published_at',
                'modified_at',
            ])
            ->with([
                'categories:id,name,slug',
                'user:id,name,slug,avatar,github_data',
            ])
            ->withCount('comments')
            ->published()
            ->sponsored()
            ->latest('published_at')
            ->whereDoesntHave('link')
            ->limit(12)
            ->get();

        $links = Link::query()
            ->select([
                'id',
                'user_id',
                'post_id',
                'url',
                'title',
                'description',
                'image_url',
                'is_approved',
            ])
            ->with([
                'post:id,slug',
                'user:id,name,avatar,github_data',
            ])
            ->latest('is_approved')
            ->approved()
            ->limit(12)
            ->get();

        $aboutUser = User::query()
            ->select([
                'id',
                'name',
                'github_login',
                'biography',
                'avatar',
                'github_data',
            ])
            ->where('github_login', 'benjamincrozat')
            ->first();

        return view('home', compact('latest', 'links', 'aboutUser'));
    }
}
