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
        $path = public_path('sitemap.xml');
        $newsPath = public_path('news-sitemap.xml');

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

        $sitemap->writeToFile($path);
        $this->generateNewsSitemap()->writeToFile($newsPath);

        return $path;
    }

    protected function generateNewsSitemap() : Sitemap
    {
        $publicationName = (string) config('app.name');
        $publicationLanguage = app()->getLocale();
        $cutoff = now()->subHours(48);
        $sitemap = Sitemap::create();

        Post::query()
            ->newsEligible()
            ->where('published_at', '>=', $cutoff)
            ->latest('published_at')
            ->limit(1000)
            ->cursor()
            ->each(function (Post $post) use ($publicationName, $publicationLanguage, $sitemap) : void {
                $sitemap->add(
                    Url::create(route('posts.show', $post))
                        ->setLastModificationDate($post->modified_at ?? $post->published_at ?? $post->created_at)
                        ->addNews(
                            $publicationName,
                            $publicationLanguage,
                            $post->title,
                            $post->published_at,
                        )
                );
            });

        return $sitemap;
    }
}
