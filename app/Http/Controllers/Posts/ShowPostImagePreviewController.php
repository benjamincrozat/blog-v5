<?php

namespace App\Http\Controllers\Posts;

use Illuminate\Http\Response;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Exceptions\PostMarkdownException;
use App\Actions\Posts\ResolveMarkdownPost;

/**
 * Displays a standalone preview page for generated post images.
 */
class ShowPostImagePreviewController extends Controller
{
    public function __invoke(string $slug, ResolveMarkdownPost $resolveMarkdownPost) : Response
    {
        try {
            $source = $resolveMarkdownPost->fromSlug($slug);
        } catch (InvalidArgumentException|PostMarkdownException) {
            abort(404);
        }

        return response()
            ->view('posts.image-preview', [
                'post' => $source->document,
            ])
            ->header('X-Robots-Tag', 'noindex, nofollow, noimageindex');
    }
}
