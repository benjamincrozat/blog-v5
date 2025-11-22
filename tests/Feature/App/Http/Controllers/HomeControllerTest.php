<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;

use Illuminate\Support\Collection;

it("renders with popular and latest posts, links, and the creator's about section", function () {
    Post::factory(15)->create(['sessions_count' => 0]);

    Post::factory(15)->create(['sessions_count' => random_int(1, 1000)]);

    Link::factory(15)->approved()->create();

    User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    get(route('home'))
        ->assertOk()
        ->assertViewIs('home')
        ->assertViewHas('popular', fn (Collection $popular) => 10 === $popular->count())
        ->assertViewHas('latest', fn (Collection $latest) => 12 === $latest->count())
        ->assertViewHas('links', fn (Collection $links) => 12 === $links->count())
        ->assertViewHas('aboutUser', fn (User $aboutUser) => 'benjamincrozat' === $aboutUser->github_login);
});

it('does not show popular posts if there are no sessions', function () {
    Post::factory(15)->create(['sessions_count' => 0]);

    User::factory()->create([
        'github_login' => 'benjamincrozat',
    ]);

    get(route('home'))
        ->assertViewHas('popular', fn (Collection $popular) => $popular->isEmpty());
});
