<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Redirect;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\assertDatabaseCount;
use function Pest\Laravel\assertDatabaseMissing;

it('generates a slug when a post is created if none is provided', function () {
    $post = Post::factory()->create([
        'title' => 'Foo Bar Baz',
    ]);

    expect($post->slug)->toBe('foo-bar-baz');
});

it("does not generate a slug when it's already provided", function () {
    $post = Post::factory()->create([
        'title' => 'Foo Bar Baz',
        'slug' => 'lorem-ipsum-dolor-sit-amet',
    ]);

    expect($post->slug)->toBe('lorem-ipsum-dolor-sit-amet');
});

it('does not update the slug when title is changed', function () {
    $post = Post::factory()->create();

    $post->update(['title' => 'Foo Bar Baz']);

    expect($post->slug)->not->toBe('foo-bar-baz');
});

it('creates a redirect when the slug is changed', function () {
    $post = Post::factory()->create();

    $old = $post->slug;

    assertDatabaseCount(Redirect::class, 0);

    $post->update(['slug' => 'foo-bar-baz']);

    assertDatabaseHas(Redirect::class, [
        'from' => $old,
        'to' => 'foo-bar-baz',
    ]);
});

it('keeps only one redirect per original slug, even when toggling back and forth', function () {
    $post = Post::factory()->create([
        'slug' => 'foo',
    ]);

    // First change: foo -> bar (creates foo -> bar).
    $post->update(['slug' => 'bar']);

    assertDatabaseCount(Redirect::class, 1);

    // Change back: bar -> foo (should remove foo -> bar and create bar -> foo).
    $post->update(['slug' => 'foo']);

    assertDatabaseCount(Redirect::class, 1);

    assertDatabaseMissing(Redirect::class, [
        'from' => 'foo',
        'to' => 'bar',
    ]);

    assertDatabaseHas(Redirect::class, [
        'from' => 'bar',
        'to' => 'foo',
    ]);

    // Change again: foo -> bar (should delete bar -> foo and recreate foo -> bar).
    $post->update(['slug' => 'bar']);

    assertDatabaseCount(Redirect::class, 1);

    assertDatabaseMissing(Redirect::class, [
        'from' => 'bar',
        'to' => 'foo',
    ]);

    assertDatabaseHas(Redirect::class, [
        'from' => 'foo',
        'to' => 'bar',
    ]);
});

it('avoids circular redirects', function () {
    $post = Post::factory()->create([
        'slug' => 'foo',
    ]);

    // Pre-existing redirect bar -> foo.
    Redirect::query()->create([
        'from' => 'bar',
        'to' => 'foo',
    ]);

    // Changing foo -> bar would have produced foo -> bar, forming a loop.
    $post->update(['slug' => 'bar']);

    // The listener should have deleted the conflicting bar -> foo row and
    // created foo -> bar with no additional duplicates.
    assertDatabaseCount(Redirect::class, 1);

    assertDatabaseHas(Redirect::class, [
        'from' => 'foo',
        'to' => 'bar',
    ]);
});

it('updates existing redirects to avoid multi-hop chains', function () {
    $post = Post::factory()->create([
        'slug' => 'a',
    ]);

    // a -> b
    $post->update(['slug' => 'b']);

    // b -> c
    $post->update(['slug' => 'c']);

    // We should now have exactly two redirects, both pointing directly to "c".
    assertDatabaseCount(Redirect::class, 2);

    assertDatabaseHas(Redirect::class, [
        'from' => 'a',
        'to' => 'c',
    ]);

    assertDatabaseHas(Redirect::class, [
        'from' => 'b',
        'to' => 'c',
    ]);

    assertDatabaseMissing(Redirect::class, [
        'from' => 'a',
        'to' => 'b',
    ]);
});

it('does not create redirects when the new slug is empty', function () {
    $post = Post::factory()->create([
        'slug' => 'original',
    ]);

    assertDatabaseCount(Redirect::class, 0);

    $post->update(['slug' => '']);

    assertDatabaseCount(Redirect::class, 0);
});

it('casts the published_at attribute to a datetime', function () {
    $post = Post::factory()->create();

    expect($post->published_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('casts the modified_at attribute to a datetime', function () {
    $post = Post::factory()->create(['modified_at' => now()]);

    expect($post->modified_at)->toBeInstanceOf(CarbonImmutable::class);
});

it('casts the recommendations attribute to a collection', function () {
    $post = Post::factory()->create(['recommendations' => []]);

    expect($post->recommendations)->toBeInstanceOf(Collection::class);
});

it('scopes published posts', function () {
    Post::factory()->create(['published_at' => now()]);

    Post::factory()->create(['published_at' => null]);

    $publishedCount = Post::query()->published()->count();

    expect($publishedCount)->toBe(1);
});

it('scopes unpublished posts', function () {
    Post::factory()->create(['published_at' => null]);

    Post::factory()->create(['published_at' => now()]);

    $unpublishedCount = Post::query()->unpublished()->count();

    expect($unpublishedCount)->toBe(1);
});

it('belongs to a user', function () {
    $post = Post::factory()->create();

    expect($post->user)->toBeInstanceOf(User::class);
});

it('generates a prompt for LLMs', function () {
    $post = Post::factory()->create([
        'title' => 'Foo Bar Baz',
        'content' => 'Foo **bar** baz [qux](https://example.com)',
    ]);

    expect($post->toPrompt())->toContain('Foo Bar Baz Foo bar baz');
    expect($post->toPrompt())->toContain('href="https://example.com">qux</a>');
});

it('converts Markdown content to HTML via the formatted_content attribute', function () {
    $post = Post::factory()->create([
        'content' => '**bold** text',
    ]);

    expect($post->formatted_content)->toContain('<strong>bold</strong>');
});

it('returns null for image_url when the post has no image', function () {
    $post = Post::factory()->create([
        'image_path' => null,
        'image_disk' => null,
    ]);

    expect($post->image_url)->toBeNull();
});

it('returns the correct image_url when the post has an image', function () {
    Storage::fake('public');

    $post = Post::factory()->create([
        'image_path' => 'images/posts/foo.jpg',
        'image_disk' => 'public',
    ]);

    expect($post->image_url)->toMatch('/\/images\/posts\/foo\.jpg$/');
});

it('calculates the read_time attribute based on word count', function () {
    $content = collect(range(1, 400))->map(fn () => 'word')->implode(' ');

    $post = Post::factory()->create(['content' => $content]);

    expect($post->read_time)->toBe(2.0);
});

it('returns recommended posts for each stored recommendation id', function () {
    [$post, $recommended] = createPostWithRecommendation();

    $results = $post->recommended_posts;

    expect($results)->not->toBeNull();
    expect($results)->toHaveCount(1);
    expect($results->first()->is($recommended))->toBeTrue();
});

it('maps recommendation reasons to each recommended post', function () {
    [$post] = createPostWithRecommendation('Great follow-up');

    expect($post->recommended_posts->first()->reason)->toBe('Great follow-up');
});

it('accurately detects if a post has an attached image via hasImage()', function () {
    $withImage = Post::factory()->create([
        'image_path' => 'foo.jpg',
        'image_disk' => 'public',
    ]);

    $withoutImage = Post::factory()->create([
        'image_path' => null,
        'image_disk' => null,
    ]);

    expect($withImage->hasImage())->toBeTrue();
    expect($withoutImage->hasImage())->toBeFalse();
});

it('generates valid Markdown with YAML front matter via toMarkdown()', function () {
    $post = Post::factory()->hasCategories(3)->create([
        'title' => 'Foo Bar',
        'content' => 'Baz',
        'slug' => 'foo-bar',
        'description' => 'Desc',
        'serp_title' => 'SERP',
    ]);

    $markdown = $post->toMarkdown();

    expect($markdown)->toMatch('/^---\n/')
        ->and($markdown)->toContain('slug: foo-bar')
        ->and($markdown)->toContain('# Foo Bar');
});

it('getFeedItems only returns the 50 most recent published posts without links', function () {
    // 60 published posts without links.
    Post::factory(60)->create(['published_at' => now()]);

    // One published post with a link – should be excluded.
    $withLink = Post::factory()->create(['published_at' => now()]);
    \App\Models\Link::factory()->create(['post_id' => $withLink->id]);

    // One unpublished post – should be excluded.
    Post::factory()->create(['published_at' => null]);

    $feedItems = Post::getFeedItems();

    expect($feedItems)->toHaveCount(50);

    // Ensure ordering (latest first)
    expect($feedItems->first()->published_at->greaterThanOrEqualTo($feedItems->last()->published_at))->toBeTrue();

    // Ensure excluded post with link is not present
    expect($feedItems->pluck('id'))->not->toContain($withLink->id);
});

it('converts a post to a valid FeedItem via toFeedItem()', function () {
    $user = User::factory()->create(['name' => 'John Doe']);

    $post = Post::factory()->for($user)->create([
        'slug' => 'foo',
        'title' => 'Foo',
        'description' => 'Bar',
        'published_at' => now(),
    ]);

    $feedItem = $post->toFeedItem();

    expect($feedItem)->toBeInstanceOf(\Spatie\Feed\FeedItem::class)
        ->and($feedItem->id)->toBe('foo')
        ->and($feedItem->title)->toBe('Foo')
        ->and($feedItem->link)->toBe(route('posts.show', $post))
        ->and($feedItem->authorName)->toBe('John Doe')
        ->and($feedItem->summary)->toContain('Bar');
});

it('belongs to many categories', function () {
    $post = Post::factory()->hasCategories(3)->create();

    expect($post->categories)->not->toBeEmpty();
});

it('has many comments and counts them automatically', function () {
    $post = Post::factory()->hasComments(3)->create();

    // Refresh so comments_count is included.
    $post->refresh();

    expect($post->comments)->toHaveCount(3)
        ->and($post->comments_count)->toBe(3);
});

it('has many reports', function () {
    $post = Post::factory()->hasReports(2)->create();

    expect($post->reports)->toHaveCount(2);
});

it('has one link', function () {
    $post = Post::factory()->create();
    $link = \App\Models\Link::factory()->create(['post_id' => $post->id]);

    expect($post->link)->toBeInstanceOf(\App\Models\Link::class)
        ->and($post->link->is($link))->toBeTrue();
});

it('scopes sponsored posts first within a week, then by sponsored_at desc, else normal order', function () {
    // Create a non-sponsored, recent published post.
    $normalPosts = Post::factory()->create([
        'published_at' => now()->subDay(),
        'sponsored_at' => null,
        'title' => 'Normal',
    ]);

    // Sponsored yesterday (they come before normal posts).
    $recentlySponsored = Post::factory()->create([
        'published_at' => now()->subDay(),
        'sponsored_at' => now()->subDay(),
        'title' => 'Recent Sponsored',
    ]);

    // Sponsored 6 days ago (still within a week, but older than yesterday).
    $olderButRecentlySponsored = Post::factory()->create([
        'published_at' => now()->subDays(6),
        'sponsored_at' => now()->subDays(6),
        'title' => 'Older Recent Sponsored',
    ]);

    // Sponsored 8 days ago (older than a week, they should not get the boost).
    $sponsoredAWhileAgo = Post::factory()->create([
        'published_at' => now()->subDays(8),
        'sponsored_at' => now()->subDays(8),
        'title' => 'Old Sponsored',
    ]);

    // Query with sponsored ordering then latest published.
    $orderedIds = Post::query()
        ->published()
        ->sponsored()
        ->latest('published_at')
        ->pluck('id')
        ->all();

    expect($orderedIds)->toBe([
        $recentlySponsored->id,
        $olderButRecentlySponsored->id,
        $normalPosts->id,
        $sponsoredAWhileAgo->id,
    ]);
});

/**
 * @return array{0: \App\Models\Post, 1: \App\Models\Post}
 */
function createPostWithRecommendation(string $reason = 'Great follow-up') : array
{
    $recommended = Post::factory()->create();

    $post = Post::factory()->create([
        'recommendations' => [
            ['id' => $recommended->id, 'reason' => $reason],
        ],
    ]);

    return [$post, $recommended];
}
