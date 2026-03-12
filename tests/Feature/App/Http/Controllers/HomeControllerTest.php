<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;

use Illuminate\Support\Collection;

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

function ensureHomeCreator() : User
{
    return User::query()->firstOrCreate(
        ['github_login' => 'benjamincrozat'],
        User::factory()->make(['github_login' => 'benjamincrozat'])->getAttributes(),
    );
}
