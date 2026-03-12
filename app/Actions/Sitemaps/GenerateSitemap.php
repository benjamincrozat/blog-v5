<?php

namespace App\Actions\Sitemaps;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/**
 * Builds the public sitemap.xml file from the current public routes.
 */
class GenerateSitemap
{
    public function handle() : string
    {
        $sitemap = Sitemap::create();

        $sitemap->add(route('home'));

        $sitemap->add(route('posts.index'));

        Post::query()
            ->published()
            ->cursor()
            ->each(function (Post $post) use ($sitemap) : void {
                $sitemap->add(
                    Url::create(route('posts.show', $post))
                        ->setLastModificationDate($post->modified_at ?? $post->published_at ?? $post->created_at)
                );
            });

        User::query()
            ->cursor()
            ->each(fn (User $user) => $sitemap->add(route('authors.show', $user->slug)));

        $sitemap->add(route('categories.index'));

        Category::query()
            ->cursor()
            ->each(function (Category $category) use ($sitemap) : void {
                $sitemap->add(
                    Url::create(route('categories.show', $category->slug))
                        ->setLastModificationDate($category->modified_at ?? $category->updated_at ?? $category->created_at)
                );
            });

        $sitemap->add(route('links.index'));

        $path = public_path('sitemap.xml');

        $sitemap->writeToFile($path);

        return $path;
    }
}
