<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;
use Carbon\CarbonImmutable;
use App\Notifications\LinkApproved;
use Illuminate\Support\Facades\Notification;

it('casts is_approved and is_declined to datetime', function () {
    $link = Link::factory()->create([
        'is_approved' => now(),
        'is_declined' => now(),
    ]);

    expect($link->is_approved)->toBeInstanceOf(CarbonImmutable::class);
    expect($link->is_declined)->toBeInstanceOf(CarbonImmutable::class);
});

it('scopes pending links', function () {
    Link::factory()->create([
        'is_approved' => null,
        'is_declined' => null,
    ]);

    Link::factory()->create([
        'is_approved' => now(),
        'is_declined' => null,
    ]);

    expect(Link::query()->pending()->get())->toHaveCount(1);
});

it('scopes approved links', function () {
    Link::factory()->create([
        'is_approved' => now(),
        'is_declined' => null,
    ]);

    Link::factory()->create([
        'is_approved' => null,
        'is_declined' => now(),
    ]);

    expect(Link::query()->approved()->get())->toHaveCount(1);
});

it('scopes declined links', function () {
    Link::factory()->create([
        'is_approved' => null,
        'is_declined' => now(),
    ]);

    Link::factory()->create([
        'is_approved' => now(),
        'is_declined' => null,
    ]);

    expect(Link::query()->declined()->get())->toHaveCount(1);
});

it('belongs to a user', function () {
    $user = User::factory()->create();

    $link = Link::factory()->create([
        'user_id' => $user->id,
    ]);

    expect($link->user->is($user))->toBeTrue();
});

it('belongs to a post', function () {
    $post = Post::factory()->create();

    $link = Link::factory()->create([
        'post_id' => $post->id,
    ]);

    expect($link->post->is($post))->toBeTrue();
});

it('has a domain attribute', function () {
    $link = Link::factory()->create([
        'url' => 'https://www.google.com',
    ]);

    expect($link->domain)->toBe('google.com');
});

it('can change to approved and notify the user', function () {
    Notification::fake();

    $link = Link::factory()->create([
        'is_approved' => null,
        'is_declined' => null,
    ]);

    expect($link->is_approved)->toBeNull();

    $link->approve();

    expect($link->is_approved)->toBeInstanceOf(CarbonImmutable::class);

    Notification::assertSentToTimes($link->user, LinkApproved::class, 1);
});

it('can change to declined', function () {
    $link = Link::factory()->create([
        'is_approved' => null,
        'is_declined' => null,
    ]);

    expect($link->is_declined)->toBeNull();

    $link->decline();

    expect($link->is_declined)->toBeInstanceOf(CarbonImmutable::class);
});

it('checks if it is approved', function () {
    $link = Link::factory()->create([
        'is_approved' => now(),
        'is_declined' => null,
    ]);

    expect($link->isApproved())->toBeTrue();
});

it('checks if it is declined', function () {
    $link = Link::factory()->create([
        'is_approved' => null,
        'is_declined' => now(),
    ]);

    expect($link->isDeclined())->toBeTrue();
});

it('maps searchable attributes and only indexes approved links', function () {
    $user = User::factory()->create([
        'name' => 'Jane Author',
    ]);

    $approvedLink = Link::factory()->for($user)->create([
        'title' => 'Product Hunt',
        'description' => 'A curated tool.',
        'url' => 'https://example.com/tool',
        'is_approved' => now(),
        'is_declined' => null,
    ]);

    expect($approvedLink->toSearchableArray())->toMatchArray([
        'user_name' => 'Jane Author',
        'title' => 'Product Hunt',
        'description' => 'A curated tool.',
        'url' => 'https://example.com/tool',
    ]);
    expect($approvedLink->shouldBeSearchable())->toBeTrue();

    $pendingLink = Link::factory()->create([
        'is_approved' => null,
        'is_declined' => null,
    ]);

    expect($pendingLink->shouldBeSearchable())->toBeFalse();
});
