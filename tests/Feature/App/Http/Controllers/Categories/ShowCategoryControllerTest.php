<?php

use App\Models\Post;
use App\Models\Category;

use function Pest\Laravel\get;

use Illuminate\Pagination\LengthAwarePaginator;

it('shows a category', function () {
    $category = Category::factory()->create();

    get(route('categories.show', $category))
        ->assertOk()
        ->assertViewIs('categories.show')
        ->assertViewHas('category', $category);
});

it('throws a 404 if the category does not exist', function () {
    get(route('categories.show', 'non-existent-category'))
        ->assertNotFound();
});

it('only includes published posts belonging to the category', function () {
    $category = Category::factory()->create();
    $otherCategory = Category::factory()->create();

    $publishedInCategory = Post::factory()
        ->hasAttached($category, [], 'categories')
        ->create([
            'published_at' => now()->subDay(),
        ]);

    // Unpublished (future) post in category should be excluded.
    Post::factory()
        ->hasAttached($category, [], 'categories')
        ->create([
            'published_at' => now()->addDay(),
        ]);

    // Published post in a different category should be excluded.
    Post::factory()
        ->hasAttached($otherCategory, [], 'categories')
        ->create([
            'published_at' => now()->subHours(2),
        ]);

    get(route('categories.show', $category))
        ->assertOk()
        ->assertViewHas('posts', function ($paginator) use ($publishedInCategory) {
            return $paginator instanceof LengthAwarePaginator
                && 1 === $paginator->total()
                && $paginator->pluck('id')->contains($publishedInCategory->id);
        });
});

it('orders posts by boosting recent sponsorship then by published_at desc', function () {
    $category = Category::factory()->create();

    $sponsoredRecent = Post::factory()
        ->hasAttached($category, [], 'categories')
        ->create([
            'published_at' => now()->subDays(3),
            'sponsored_at' => now()->subDay(), // within a week -> boosted
        ]);

    $unsponsoredLatest = Post::factory()
        ->hasAttached($category, [], 'categories')
        ->create([
            'published_at' => now(),
            'sponsored_at' => null,
        ]);

    $sponsoredOld = Post::factory()
        ->hasAttached($category, [], 'categories')
        ->create([
            'published_at' => now()->subHours(3),
            'sponsored_at' => now()->subMonths(2), // not boosted
        ]);

    $response = get(route('categories.show', $category))
        ->assertOk();

    $response->assertViewHas('posts', function (LengthAwarePaginator $paginator) use ($sponsoredRecent, $unsponsoredLatest, $sponsoredOld) {
        $ids = collect($paginator->items())->pluck('id');

        // Boosted recent sponsored post should be first.
        if ($ids->first() !== $sponsoredRecent->id) {
            return false;
        }

        // Among non-boosted, latest published should come before older sponsored.
        $posUnsponsoredLatest = $ids->search($unsponsoredLatest->id);
        $posSponsoredOld = $ids->search($sponsoredOld->id);

        return false !== $posUnsponsoredLatest
            && false !== $posSponsoredOld
            && $posUnsponsoredLatest < $posSponsoredOld;
    });
});

it('uses the same category copy and ordering for the news category', function () {
    $news = Category::factory()->create([
        'name' => 'News',
        'slug' => Post::NEWS_CATEGORY_SLUG,
    ]);

    $recentSponsored = Post::factory()
        ->hasAttached($news, [], 'categories')
        ->create([
            'published_at' => now()->subDay(),
            'is_commercial' => false,
            'sponsored_at' => now()->subHour(),
        ]);

    $latestUnsponsored = Post::factory()
        ->hasAttached($news, [], 'categories')
        ->create([
            'published_at' => now()->subHour(),
            'is_commercial' => false,
            'sponsored_at' => null,
        ]);

    get(route('categories.show', $news))
        ->assertOk()
        ->assertSee('Articles in the News category')
        ->assertViewHas('posts', function (LengthAwarePaginator $paginator) use ($recentSponsored, $latestUnsponsored) {
            $ids = collect($paginator->items())->pluck('id')->values();

            return [$recentSponsored->id, $latestUnsponsored->id] === $ids->all();
        });
});

it('paginates 24 posts per page and keeps the category heading consistent', function () {
    $category = Category::factory()->create();

    // Create 30 published posts in this category.
    foreach (range(1, 30) as $i) {
        Post::factory()
            ->hasAttached($category, [], 'categories')
            ->create(['published_at' => now()->subDays($i)]);
    }

    // Page 1: 24 items.
    get(route('categories.show', $category))
        ->assertOk()
        ->assertViewHas('posts', fn (LengthAwarePaginator $p) => 24 === $p->perPage() && 24 === $p->count());

    // Page 2: 6 items.
    get(route('categories.show', [$category, 'page' => 2]))
        ->assertOk()
        ->assertSee('Articles in the ' . $category->name . ' category')
        ->assertViewHas('posts', fn (LengthAwarePaginator $p) => 2 === $p->currentPage() && 6 === $p->count());
});
