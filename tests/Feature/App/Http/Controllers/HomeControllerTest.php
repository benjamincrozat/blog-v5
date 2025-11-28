<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;

use Illuminate\Support\Collection;

it('shows the top ten popular posts when sessions have been recorded', function () {
    Post::factory(10)->create(['sessions_count' => 0]);
    Post::factory(15)->create(['sessions_count' => random_int(100, 500)]);
    ensureHomeCreator();

    get(route('home'))
        ->assertViewHas('popular', fn (Collection $popular) => 10 === $popular->count());
});

it('limits the latest posts collection to twelve entries', function () {
    Post::factory(20)->create(['published_at' => now()]);
    ensureHomeCreator();

    get(route('home'))
        ->assertViewHas('latest', fn (Collection $latest) => 12 === $latest->count());
});

it('shows twelve approved links on the homepage', function () {
    Link::factory(20)->approved()->create();
    ensureHomeCreator();

    get(route('home'))
        ->assertViewHas('links', fn (Collection $links) => 12 === $links->count());
});

it("exposes Benjamin's about section to the view", function () {
    $creator = ensureHomeCreator();

    get(route('home'))
        ->assertViewHas('aboutUser', fn (User $aboutUser) => $aboutUser->is($creator));
});

it('does not show popular posts if there are no sessions', function () {
    Post::factory(15)->create(['sessions_count' => 0]);
    ensureHomeCreator();

    get(route('home'))
        ->assertViewHas('popular', fn (Collection $popular) => $popular->isEmpty());
});

function ensureHomeCreator() : User
{
    return User::query()->firstOrCreate(
        ['github_login' => 'benjamincrozat'],
        User::factory()->make(['github_login' => 'benjamincrozat'])->getAttributes(),
    );
}
