<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;

use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;

it('shows a post', function () {
    $post = Post::factory()->hasComments(3)->create();

    get(route('posts.show', $post))
        ->assertOk()
        ->assertViewIs('posts.show')
        ->assertViewHas('post', $post)
        ->assertViewHas('latestComment', $post->comments()
            ->whereRelation('user', 'github_login', '!=', 'benjamincrozat')
            ->latest()
            ->first())
        ->assertSee("<title>{$post->serp_title}</title>", escape: false)
        ->assertSee("<meta name=\"description\" content=\"{$post->serp_description}\" />", escape: false)
        ->assertSee('Ask ChatGPT')
        ->assertSee('Ask Claude')
        ->assertDontSee('Did you like this article? Then, keep learning:');
});

it('renders NewsArticle schema for eligible news posts while keeping the simple visible date', function () {
    $news = Category::factory()->create([
        'name' => 'News',
        'slug' => Post::NEWS_CATEGORY_SLUG,
    ]);

    $post = Post::factory()->create([
        'published_at' => now()->startOfDay()->setTime(9, 17),
        'modified_at' => now()->startOfDay()->setTime(14, 37),
        'is_commercial' => false,
        'sponsored_at' => null,
    ]);

    $post->categories()->sync([$news->id]);

    $expectedDate = ($post->modified_at ?? $post->published_at ?? $post->created_at)->isoFormat('ll');
    $response = get(route('posts.show', $post));

    $response
        ->assertOk()
        ->assertSee('"@type": "NewsArticle"', escape: false)
        ->assertSee('"mainEntityOfPage"', escape: false)
        ->assertSee('"publisher"', escape: false)
        ->assertSee(route('authors.show', $post->user->slug), escape: false)
        ->assertSee($expectedDate)
        ->assertDontSee('UTC');

    expect($response->getContent())
        ->toMatch('/Published\s*<br \/>\s*' . preg_quote($expectedDate, '/') . '\s*<\/div>/s');
});

it('keeps standard article schema for non-news posts', function () {
    $category = Category::factory()->create([
        'slug' => 'laravel',
    ]);

    $post = Post::factory()->create([
        'published_at' => now()->subDay(),
        'is_commercial' => false,
        'sponsored_at' => null,
    ]);

    $post->categories()->sync([$category->id]);

    get(route('posts.show', $post))
        ->assertOk()
        ->assertSee('"@type": "Article"', escape: false)
        ->assertDontSee('"@type": "NewsArticle"', escape: false);
});

it('without a SERP title, the title is used', function () {
    $post = Post::factory()->create(['serp_title' => null]);

    get(route('posts.show', $post))
        ->assertSee("<title>{$post->title}</title>", escape: false);
});

it('falls back to description when the SERP description is empty', function () {
    $post = Post::factory()->create([
        'serp_description' => null,
        'description' => 'Fallback summary',
    ]);

    get(route('posts.show', $post))
        ->assertSee('<meta name="description" content="Fallback summary" />', escape: false);
});

it('throws a 404 if the post does not exist', function () {
    get(route('posts.show', 'non-existent-post'))
        ->assertNotFound();
});

it('throws a 404 to guests if the post is not published', function () {
    $post = Post::factory()->create(['published_at' => null]);

    get(route('posts.show', $post))
        ->assertNotFound();
});

it('throws a 404 to guests if the post is scheduled', function () {
    $post = Post::factory()->create(['published_at' => now()->addDay()]);

    get(route('posts.show', $post))
        ->assertNotFound();
});

it('shows unpublished posts if the user is admin', function () {
    $user = User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    $post = Post::factory()->create([
        'published_at' => null,
    ]);

    actingAs($user)
        ->get(route('posts.show', $post))
        ->assertOk();
});

it('returns 410 gone when the post is soft deleted', function () {
    $post = Post::factory()->create();

    $post->delete();

    get(route('posts.show', $post))
        ->assertStatus(410);
});

it('hides the sticky carousel for commercial posts', function () {
    $post = Post::factory()->create([
        'is_commercial' => true,
    ]);

    get(route('posts.show', $post))
        ->assertOk()
        ->assertDontSee('Black Friday');
});

it('builds a single blog breadcrumb trail for posts and omits the current page URL from schema', function () {
    $post = Post::factory()->create([
        'title' => 'Better breadcrumbs for posts',
    ]);

    $categories = Category::factory()->count(2)->create();

    $post->categories()->sync($categories->pluck('id'));

    get(route('posts.show', $post))
        ->assertOk()
        ->assertViewHas('breadcrumbs', [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog', 'url' => route('posts.index')],
            ['label' => $post->title],
        ])
        ->assertViewHas('breadcrumbSchema', [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Blog',
                    'item' => route('posts.index'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $post->title,
                ],
            ],
        ]);
});
