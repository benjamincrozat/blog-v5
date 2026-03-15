<?php

namespace App\Http\Controllers\Posts;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

/**
 * Returns a post as Markdown for authenticated admin actions.
 */
class ShowPostMarkdownController extends Controller
{
    public function __invoke(Request $request, Post $post) : Response
    {
        abort_unless($request->user()?->isAdmin(), 403);

        return response($post->toMarkdown(), headers: [
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Cache-Control' => 'no-store, private',
        ]);
    }
}
