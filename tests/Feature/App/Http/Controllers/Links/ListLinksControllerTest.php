<?php

use App\Models\Link;
use App\Models\User;

use function Pest\Laravel\get;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

it('passes approved links to the view', function () {
    $approved = Link::factory(5)->approved()->create();

    Link::factory(3)->declined()->create();

    Link::factory(2)->create();

    get(route('links.index'))
        ->assertOk()
        ->assertViewHas('links', function (LengthAwarePaginator $links) use ($approved) {
            return $links->count() === $approved->count() &&
                   $links->every(
                       fn (Link $link) => null !== $link->is_approved && null === $link->is_declined
                   );
        });
});

it('orders links by is_approved in descending order', function () {
    $old = Link::factory()->approved()->create(['is_approved' => now()->subDays(2)]);
    $new = Link::factory()->approved()->create(['is_approved' => now()->subDay()]);
    $newest = Link::factory()->approved()->create(['is_approved' => now()]);

    get(route('links.index'))
        ->assertOk()
        ->assertViewHas('links', function (LengthAwarePaginator $links) use ($newest, $new, $old) {
            return $links->first()->id === $newest->id &&
                   $links->get(1)->id === $new->id &&
                   $links->get(2)->id === $old->id;
        });
});

it('passes distinct user avatars to the view', function () {
    $usersWithAvatars = User::factory(5)
        ->create(['avatar' => 'https://example.com/avatar.png']);

    $usersWithoutAvatars = User::factory(3)
        ->create(['avatar' => null]);

    foreach ($usersWithAvatars as $user) {
        Link::factory()->approved()->create(['user_id' => $user->id]);
    }

    foreach ($usersWithoutAvatars as $user) {
        Link::factory()->approved()->create(['user_id' => $user->id]);
    }

    get(route('links.index'))
        ->assertOk()
        ->assertViewHas('distinctUserAvatars', function (Collection $avatars) {
            return $avatars->count() <= 10 &&
                   $avatars->every(fn (string $avatar) => null !== $avatar);
        });
});

it('excludes specific users from distinct user avatars', function () {
    User::factory()->sequence(
        ['github_login' => 'benjamincrozat'],
    )->create([
        'avatar' => 'https://example.com/excluded-avatar.png',
    ])->each(function (User $user) {
        Link::factory()->approved()->create([
            'user_id' => $user->id,
        ]);
    });

    User::factory(5)
        ->create(['avatar' => 'https://example.com/avatar.png'])
        ->each(function (User $user) {
            Link::factory()->approved()->create([
                'user_id' => $user->id,
            ]);
        });

    get(route('links.index'))
        ->assertOk()
        ->assertViewHas('distinctUserAvatars', fn (Collection $avatars) => ! $avatars->contains('https://example.com/excluded-avatar.png'));
});

it('passes distinct users count to the view', function () {
    // Create some users with avatars.
    User::factory(5)
        ->create(['avatar' => 'https://example.com/avatar.png'])
        ->each(fn (User $user) => Link::factory()->approved()->create(['user_id' => $user->id]));

    // Create users without avatars (they shouldn't be counted).
    User::factory(3)
        ->create(['avatar' => null])
        ->each(fn (User $user) => Link::factory()->approved()->create(['user_id' => $user->id]));

    // Create excluded users (they shouldn't be counted as well).
    $excludedUser = User::factory()->create([
        'github_login' => 'benjamincrozat',
        'avatar' => 'https://example.com/avatar.png',
    ]);

    Link::factory()->approved()->create(['user_id' => $excludedUser->id]);

    get(route('links.index'))
        ->assertOk()
        ->assertViewHas('distinctUsersCount', 5); // Only count non-excluded users with avatars.
});

it('paginates the links collection', function () {
    Link::factory(15)->approved()->create();

    get(route('links.index'))
        ->assertOk()
        ->assertViewHas('links', fn (LengthAwarePaginator $links) => 12 === $links->count());
});

it('builds breadcrumbs for the links index', function () {
    get(route('links.index'))
        ->assertOk()
        ->assertViewHas('breadcrumbs', [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Links'],
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
                    'name' => 'Links',
                ],
            ],
        ]);
});
