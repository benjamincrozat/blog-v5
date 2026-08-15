<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Actions\BuildBreadcrumbSchema;

/**
 * Checks whether a post may be shown before building its article page.
 *
 * A missing slug returns 404, a deleted post returns 410, and an unpublished post
 * is hidden unless the visitor is the site administrator. A visible post also
 * gets breadcrumbs, the latest comment not written by the owner, and the prompt
 * used for follow-up questions.
 */
class ShowPostController extends Controller
{
    public function __invoke(Request $request, string $slug) : View
    {
        $post = Post::withTrashed()->where('slug', $slug)->first();

        if (! $post) {
            abort(404);
        }

        if ($post->trashed()) {
            abort(410);
        }

        if (! $request->user()?->isAdmin()) {
            if (! $post->isPublished()) {
                abort(404);
            }
        }

        $breadcrumbs = [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('posts.index')],
            ['label' => $post->title],
        ];

        return view('posts.show', compact('post') + [
            'latestComment' => $post->comments()
                ->whereRelation('user', 'github_login', '!=', 'benjamincrozat')
                ->latest()
                ->first(),
            'breadcrumbs' => $breadcrumbs,
            'breadcrumbSchema' => app(BuildBreadcrumbSchema::class)->handle($breadcrumbs),
            'aiPrompt' => "Read this blog post and help me with follow-up questions:\n\n{$post->title}\n" . route('posts.show', $post),
        ]);
    }
}
