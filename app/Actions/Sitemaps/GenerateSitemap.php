<?php

namespace App\Actions\Sitemaps;

use App\Models\Post;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

/**
 * Writes the regular sitemap and the Google News sitemap from public content.
 *
 * The regular sitemap includes the main pages, published posts without a canonical
 * override, every category, and the links page. Category dates are omitted because
 * their content changes independently of the category row. The news sitemap includes
 * at most 1,000 allowed posts from the last 48 hours. Both files are replaced on
 * disk. Nothing is sent or submitted to a search engine.
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
            ->withoutCanonicalOverride()
            ->cursor()
            ->each(function (Post $post) use ($sitemap) : void {
                $sitemap->add(
                    Url::create(route('posts.show', $post))
                        ->setLastModificationDate($post->modified_at ?? $post->published_at ?? $post->created_at)
                );
            });

        Category::query()
            ->cursor()
            ->each(function (Category $category) use ($sitemap) : void {
                $sitemap->add(route('categories.show', $category->slug));
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
