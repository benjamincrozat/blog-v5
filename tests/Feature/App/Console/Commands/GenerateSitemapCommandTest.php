<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;

use function Pest\Laravel\artisan;

use Illuminate\Support\Facades\File;
use App\Console\Commands\GenerateSitemapCommand;

it('generates a sitemap with the most important pages', function () {
    Post::factory(10)->create();

    Category::factory(10)->create();

    artisan(GenerateSitemapCommand::class);

    expect(file_exists($path = public_path('sitemap.xml')))->toBeTrue();

    $content = File::get($path);

    expect($content)->toContain(route('home'));

    expect($content)->toContain(route('posts.index'));

    Post::query()
        ->published()
        ->cursor()
        ->each(fn (Post $post) => expect($content)->toContain(route('posts.show', $post)));

    User::query()
        ->cursor()
        ->each(fn (User $user) => expect($content)->toContain(route('authors.show', $user->slug)));

    expect($content)->toContain(route('categories.index'));

    Category::query()
        ->cursor()
        ->each(fn (Category $category) => expect($content)->toContain(route('categories.show', $category->slug)));

    expect($content)->toContain(route('links.index'));
});
