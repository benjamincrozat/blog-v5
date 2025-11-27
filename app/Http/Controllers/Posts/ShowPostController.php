<?php

namespace App\Http\Controllers\Posts;

use App\Models\Job;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShowPostController extends Controller
{
    /**
     * The resolution logic is here to make the code easier to follow.
     * In that case, no implicit binding since it needs to be custom.
     */
    public function __invoke(Request $request, string $slug) : View
    {
        // Retrieve the post, including soft-deleted ones.
        $post = Post::withTrashed()->where('slug', $slug)->first();

        // If it doesn't exist at all, return 404.
        if (! $post) {
            abort(404);
        }

        // If the post is soft-deleted, return 410 Gone.
        if ($post->trashed()) {
            abort(410);
        }

        if (! $request->user()?->isAdmin()) {
            // If the post is not published, return 404.
            if (! $post->isPublished()) {
                abort(404);
            }
        }

        return view('posts.show', compact('post') + [
            'latestComment' => $post->comments()
                ->whereRelation('user', 'github_login', '!=', 'benjamincrozat')
                ->latest()
                ->first(),

            'latestJobs' => Job::query()->limit(5)->get(),
        ]);
    }
}
