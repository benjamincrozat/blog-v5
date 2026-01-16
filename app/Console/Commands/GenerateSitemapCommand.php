<?php

namespace App\Console\Commands;

use App\Models\Job;
use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'app:generate-sitemap',
    description: 'Generate the sitemap.'
)]
class GenerateSitemapCommand extends Command
{
    public function handle() : void
    {
        $sitemap = Sitemap::create(config('app.url'));

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

        Job::query()
            ->cursor()
            ->each(fn (Job $job) => $sitemap->add(route('jobs.show', $job->slug)));

        $sitemap->add(route('links.index'));

        $sitemap->writeToFile($path = public_path('sitemap.xml'));

        $this->info("Sitemap generated successfully at $path");
    }
}
