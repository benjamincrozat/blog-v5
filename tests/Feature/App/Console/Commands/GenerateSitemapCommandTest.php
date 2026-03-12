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
    expect(file_exists(public_path('news-sitemap.xml')))->toBeTrue();

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

it('generates a news sitemap with only eligible recent news posts', function () {
    $news = Category::factory()->create([
        'name' => 'News',
        'slug' => Post::NEWS_CATEGORY_SLUG,
    ]);

    $eligible = Post::factory()->create([
        'title' => 'Eligible news post',
        'published_at' => now()->subHours(2),
        'is_commercial' => false,
        'sponsored_at' => null,
    ]);
    $eligible->categories()->sync([$news->id]);

    $stale = Post::factory()->create([
        'published_at' => now()->subDays(3),
        'is_commercial' => false,
        'sponsored_at' => null,
    ]);
    $stale->categories()->sync([$news->id]);

    $commercial = Post::factory()->create([
        'published_at' => now()->subHour(),
        'is_commercial' => true,
        'sponsored_at' => null,
    ]);
    $commercial->categories()->sync([$news->id]);

    $sponsored = Post::factory()->create([
        'published_at' => now()->subHour(),
        'is_commercial' => false,
        'sponsored_at' => now()->subMinutes(30),
    ]);
    $sponsored->categories()->sync([$news->id]);

    $withLink = Post::factory()->create([
        'published_at' => now()->subHour(),
        'is_commercial' => false,
        'sponsored_at' => null,
    ]);
    $withLink->categories()->sync([$news->id]);
    \App\Models\Link::factory()->create(['post_id' => $withLink->id]);

    $evergreen = Post::factory()->create([
        'published_at' => now()->subHour(),
        'is_commercial' => false,
        'sponsored_at' => null,
    ]);
    $evergreen->categories()->sync([Category::factory()->create(['slug' => 'laravel'])->id]);

    artisan(GenerateSitemapCommand::class);

    $content = File::get(public_path('news-sitemap.xml'));

    expect($content)
        ->toContain(route('posts.show', $eligible))
        ->toContain('<news:name>Benjamin Crozat</news:name>')
        ->toContain('<news:title>Eligible news post</news:title>')
        ->not->toContain(route('posts.show', $stale))
        ->not->toContain(route('posts.show', $commercial))
        ->not->toContain(route('posts.show', $sponsored))
        ->not->toContain(route('posts.show', $withLink))
        ->not->toContain(route('posts.show', $evergreen));
});
