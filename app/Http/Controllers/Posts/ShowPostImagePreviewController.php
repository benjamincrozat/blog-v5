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
                'mesh' => $this->randomMesh(),
                'post' => $source->document,
            ])
            ->header('X-Robots-Tag', 'noindex, nofollow, noimageindex');
    }

    /**
     * @return array{
     *     atmosphere: string,
     *     body: string,
     *     canvas: string,
     *     veil: string,
     *     blobs: array<int, string>,
     * }
     */
    protected function randomMesh() : array
    {
        $meshes = [
            [
                'body' => '#f4efe9',
                'canvas' => '#f9f5ef',
                'atmosphere' => 'radial-gradient(circle at top left, rgba(255,255,255,0.95), rgba(249,245,239,0.8) 42%, rgba(244,239,233,1))',
                'blobs' => [
                    'rgba(253,230,216,0.8)',
                    'rgba(216,236,255,0.75)',
                    'rgba(255,240,191,0.8)',
                    'rgba(219,242,228,0.85)',
                    'rgba(255,255,255,0.6)',
                ],
                'veil' => 'linear-gradient(145deg, rgba(255,255,255,0.35), transparent 35%, rgba(255,255,255,0.2))',
            ],
            [
                'body' => '#f5efe8',
                'canvas' => '#fbf5ee',
                'atmosphere' => 'radial-gradient(circle at top left, rgba(255,255,255,0.96), rgba(251,245,238,0.82) 40%, rgba(245,239,232,1))',
                'blobs' => [
                    'rgba(255,223,229,0.78)',
                    'rgba(221,229,255,0.74)',
                    'rgba(255,242,197,0.8)',
                    'rgba(214,243,233,0.84)',
                    'rgba(255,255,255,0.62)',
                ],
                'veil' => 'linear-gradient(145deg, rgba(255,255,255,0.32), transparent 36%, rgba(255,250,244,0.24))',
            ],
            [
                'body' => '#f3eee6',
                'canvas' => '#f8f3ea',
                'atmosphere' => 'radial-gradient(circle at top left, rgba(255,255,255,0.94), rgba(248,243,234,0.84) 43%, rgba(243,238,230,1))',
                'blobs' => [
                    'rgba(255,231,210,0.82)',
                    'rgba(209,236,255,0.74)',
                    'rgba(255,235,176,0.82)',
                    'rgba(210,240,227,0.86)',
                    'rgba(255,255,255,0.58)',
                ],
                'veil' => 'linear-gradient(145deg, rgba(255,255,255,0.3), transparent 34%, rgba(255,248,238,0.18))',
            ],
            [
                'body' => '#f4eee7',
                'canvas' => '#faf5ee',
                'atmosphere' => 'radial-gradient(circle at top left, rgba(255,255,255,0.95), rgba(250,245,238,0.82) 40%, rgba(244,238,231,1))',
                'blobs' => [
                    'rgba(255,228,214,0.8)',
                    'rgba(223,238,255,0.74)',
                    'rgba(241,235,255,0.72)',
                    'rgba(214,243,223,0.84)',
                    'rgba(255,255,255,0.6)',
                ],
                'veil' => 'linear-gradient(145deg, rgba(255,255,255,0.34), transparent 36%, rgba(255,247,240,0.2))',
            ],
        ];

        return $meshes[array_rand($meshes)];
    }
}
