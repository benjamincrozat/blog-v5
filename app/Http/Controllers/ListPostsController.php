<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Date;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class ListPostsController extends Controller
{
    public function __invoke() : View
    {
        $timestamp = max(array_map('filemtime', glob(resource_path('markdown/posts') . '/*.md')));

        $key = "posts_$timestamp";

        $posts = cache()->rememberForever($key, function () {
            return collect(
                iterator_to_array(
                    app(Finder::class)
                        ->files()
                        ->in(resource_path('markdown/posts'))
                        ->name('*.md')
                        ->sortByName(),
                    false
                )
            )
                ->map(function (SplFileInfo $file) {
                    $post = YamlFrontMatter::parse($file->getContents());

                    return [
                        'image' => $post->matter('Image'),
                        'title' => $post->matter('Title'),
                        'description' => $post->matter('Description'),
                        'published_at' => $post->matter('Published at') ? Date::createFromTimestamp($post->matter('Published at')) : null,
                        'modified_at' => $post->matter('Modified at') ? Date::createFromTimestamp($post->matter('Modified at')) : null,
                        'slug' => preg_replace('/^\d+-/', '', basename($file->getFilename(), '.md')),
                    ];
                })
                ->sortByDesc('published_at');
        })->paginate(24);

        return view('posts.index', compact('posts'));
    }
}
