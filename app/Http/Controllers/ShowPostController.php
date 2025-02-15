<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Date;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class ShowPostController extends Controller
{
    public function __invoke(string $slug) : View
    {
        $filepath = resource_path("markdown/posts/{$slug}.md");

        abort_if(! file_exists($filepath), 404);

        $timestamp = filemtime($filepath);

        $cacheKey = "post_{$slug}_$timestamp";

        $post = cache()->rememberForever($cacheKey, function () use ($filepath, $slug) {
            $document = YamlFrontMatter::parse(file_get_contents($filepath));

            return [
                'image' => $document->matter('Image'),
                'title' => $document->matter('Title'),
                'slug' => $slug,
                'content' => $document->body(),
                'description' => $document->matter('Description'),
                'published_at' => $document->matter('Published at')
                    ? Date::createFromTimestamp($document->matter('Published at'))
                    : null,
                'modified_at' => $document->matter('Modified at')
                    ? Date::createFromTimestamp($document->matter('Modified at'))
                    : null,
                'canonical' => $document->matter('Canonical'),
            ];
        });

        return view('posts.show', compact('post'));
    }
}
