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
     *     blobs: array<int, array{class: string, color: string}>,
     *     body: string,
     *     canvas: string,
     *     veil: string,
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
                    ['class' => '-top-28 left-[-4rem] size-[32rem]', 'color' => 'rgba(253,230,216,0.9)'],
                    ['class' => 'top-20 right-[-6rem] size-[34rem]', 'color' => 'rgba(216,236,255,0.82)'],
                    ['class' => 'bottom-[-8rem] left-1/4 size-[30rem]', 'color' => 'rgba(255,240,191,0.88)'],
                    ['class' => 'bottom-[-10rem] right-[-1rem] size-[26rem]', 'color' => 'rgba(219,242,228,0.9)'],
                    ['class' => 'top-1/3 left-1/2 size-[18rem] -translate-x-1/2', 'color' => 'rgba(255,255,255,0.62)'],
                ],
                'veil' => 'linear-gradient(145deg, rgba(255,255,255,0.35), transparent 35%, rgba(255,255,255,0.2))',
            ],
            [
                'body' => '#f8efe8',
                'canvas' => '#fdf6ef',
                'atmosphere' => 'radial-gradient(circle at 18% 16%, rgba(255,255,255,0.96), rgba(253,246,239,0.78) 36%, rgba(248,239,232,1))',
                'blobs' => [
                    ['class' => '-top-20 left-20 size-[24rem]', 'color' => 'rgba(255,188,205,0.88)'],
                    ['class' => 'top-8 right-[12rem] size-[24rem]', 'color' => 'rgba(255,222,162,0.82)'],
                    ['class' => 'bottom-[-5rem] left-[28rem] size-[34rem]', 'color' => 'rgba(255,236,152,0.9)'],
                    ['class' => 'bottom-[-6rem] right-[-5rem] size-[28rem]', 'color' => 'rgba(182,231,255,0.88)'],
                    ['class' => 'top-[16rem] right-[22rem] size-[18rem]', 'color' => 'rgba(255,255,255,0.58)'],
                ],
                'veil' => 'linear-gradient(135deg, rgba(255,255,255,0.22), transparent 28%, rgba(255,247,240,0.26))',
            ],
            [
                'body' => '#f1efe8',
                'canvas' => '#f7f5ef',
                'atmosphere' => 'radial-gradient(circle at 78% 24%, rgba(255,255,255,0.95), rgba(247,245,239,0.82) 34%, rgba(241,239,232,1))',
                'blobs' => [
                    ['class' => '-top-28 right-[-6rem] size-[34rem]', 'color' => 'rgba(197,226,255,0.9)'],
                    ['class' => 'top-[9rem] left-[-7rem] size-[26rem]', 'color' => 'rgba(212,242,220,0.82)'],
                    ['class' => 'bottom-[-8rem] left-[6rem] size-[28rem]', 'color' => 'rgba(255,215,185,0.86)'],
                    ['class' => 'bottom-[-9rem] right-[15rem] size-[32rem]', 'color' => 'rgba(249,237,181,0.86)'],
                    ['class' => 'top-[13rem] left-[26rem] size-[16rem]', 'color' => 'rgba(255,255,255,0.62)'],
                ],
                'veil' => 'linear-gradient(160deg, rgba(255,255,255,0.2), transparent 30%, rgba(238,248,255,0.18))',
            ],
            [
                'body' => '#f5efe8',
                'canvas' => '#fbf5ee',
                'atmosphere' => 'radial-gradient(circle at top left, rgba(255,255,255,0.95), rgba(250,245,238,0.82) 40%, rgba(244,238,231,1))',
                'blobs' => [
                    ['class' => '-top-20 left-[-5rem] size-[30rem]', 'color' => 'rgba(255,228,214,0.88)'],
                    ['class' => 'top-[5rem] right-[-7rem] size-[34rem]', 'color' => 'rgba(223,238,255,0.84)'],
                    ['class' => 'bottom-[2rem] left-[40rem] size-[18rem]', 'color' => 'rgba(241,235,255,0.82)'],
                    ['class' => 'bottom-[-8rem] left-[14rem] size-[34rem]', 'color' => 'rgba(255,232,144,0.88)'],
                    ['class' => 'bottom-[-8rem] right-[-2rem] size-[24rem]', 'color' => 'rgba(214,243,223,0.9)'],
                ],
                'veil' => 'linear-gradient(145deg, rgba(255,255,255,0.34), transparent 36%, rgba(255,247,240,0.2))',
            ],
            [
                'body' => '#efece8',
                'canvas' => '#f7f3ee',
                'atmosphere' => 'radial-gradient(circle at 50% 12%, rgba(255,255,255,0.96), rgba(247,243,238,0.82) 30%, rgba(239,236,232,1))',
                'blobs' => [
                    ['class' => '-top-24 left-[12rem] size-[22rem]', 'color' => 'rgba(218,237,255,0.86)'],
                    ['class' => 'top-[8rem] left-[-4rem] size-[24rem]', 'color' => 'rgba(255,214,198,0.9)'],
                    ['class' => 'top-[12rem] right-[-4rem] size-[22rem]', 'color' => 'rgba(255,234,181,0.84)'],
                    ['class' => 'bottom-[-7rem] left-[26rem] size-[36rem]', 'color' => 'rgba(202,240,226,0.88)'],
                    ['class' => 'bottom-[-5rem] right-[8rem] size-[22rem]', 'color' => 'rgba(226,226,255,0.8)'],
                ],
                'veil' => 'linear-gradient(180deg, rgba(255,255,255,0.18), transparent 24%, rgba(255,255,255,0.12))',
            ],
            [
                'body' => '#f6eee7',
                'canvas' => '#fcf4ed',
                'atmosphere' => 'radial-gradient(circle at 16% 72%, rgba(255,255,255,0.95), rgba(252,244,237,0.82) 34%, rgba(246,238,231,1))',
                'blobs' => [
                    ['class' => 'top-[-7rem] left-[6rem] size-[30rem]', 'color' => 'rgba(255,233,164,0.88)'],
                    ['class' => 'top-[2rem] right-[6rem] size-[28rem]', 'color' => 'rgba(205,231,255,0.84)'],
                    ['class' => 'bottom-[-10rem] left-[-2rem] size-[30rem]', 'color' => 'rgba(255,205,210,0.82)'],
                    ['class' => 'bottom-[-10rem] right-[18rem] size-[28rem]', 'color' => 'rgba(192,240,221,0.88)'],
                    ['class' => 'top-[15rem] left-[28rem] size-[18rem]', 'color' => 'rgba(255,255,255,0.56)'],
                ],
                'veil' => 'linear-gradient(125deg, rgba(255,255,255,0.25), transparent 25%, rgba(255,246,232,0.22))',
            ],
            [
                'body' => '#f1ede6',
                'canvas' => '#f8f4ec',
                'atmosphere' => 'radial-gradient(circle at 84% 70%, rgba(255,255,255,0.95), rgba(248,244,236,0.82) 36%, rgba(241,237,230,1))',
                'blobs' => [
                    ['class' => '-top-24 right-[10rem] size-[22rem]', 'color' => 'rgba(225,219,255,0.84)'],
                    ['class' => 'top-[7rem] left-[2rem] size-[26rem]', 'color' => 'rgba(255,219,198,0.88)'],
                    ['class' => 'top-[9rem] right-[-8rem] size-[24rem]', 'color' => 'rgba(184,230,255,0.84)'],
                    ['class' => 'bottom-[-8rem] left-[10rem] size-[26rem]', 'color' => 'rgba(255,238,167,0.9)'],
                    ['class' => 'bottom-[-9rem] right-[4rem] size-[34rem]', 'color' => 'rgba(206,242,225,0.86)'],
                ],
                'veil' => 'linear-gradient(150deg, rgba(255,255,255,0.28), transparent 34%, rgba(240,248,255,0.18))',
            ],
            [
                'body' => '#f6efe8',
                'canvas' => '#fcf6ef',
                'atmosphere' => 'radial-gradient(circle at 36% 18%, rgba(255,255,255,0.96), rgba(252,246,239,0.8) 30%, rgba(246,239,232,1))',
                'blobs' => [
                    ['class' => '-top-16 left-[-4rem] size-[24rem]', 'color' => 'rgba(255,209,187,0.9)'],
                    ['class' => 'top-[-5rem] right-[14rem] size-[24rem]', 'color' => 'rgba(255,232,152,0.86)'],
                    ['class' => 'top-[10rem] right-[-7rem] size-[30rem]', 'color' => 'rgba(208,232,255,0.88)'],
                    ['class' => 'bottom-[-6rem] left-[18rem] size-[22rem]', 'color' => 'rgba(224,221,255,0.78)'],
                    ['class' => 'bottom-[-10rem] right-[10rem] size-[34rem]', 'color' => 'rgba(193,241,217,0.88)'],
                ],
                'veil' => 'linear-gradient(140deg, rgba(255,255,255,0.22), transparent 28%, rgba(255,249,242,0.24))',
            ],
        ];

        return $meshes[array_rand($meshes)];
    }
}
