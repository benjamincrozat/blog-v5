<?php

use App\Models\Link;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;

use Illuminate\Support\Collection;

it('limits the latest posts collection to twelve entries', function () {
    Post::factory(20)->create(['published_at' => now()]);

    get(route('home'))
        ->assertViewHas('latest', fn (Collection $latest) => 12 === $latest->count()
            && $latest->every(fn (Post $post) => $post->relationLoaded('categories') && $post->relationLoaded('user')));
});

it('shows twelve approved links on the homepage', function () {
    $olderUser = User::factory()->create([
        'github_data' => ['id' => 123],
    ]);

    Link::factory(20)->for($olderUser)->approved()->create();

    get(route('home'))
        ->assertViewHas('links', fn (Collection $links) => 12 === $links->count()
            && $links->every(fn (Link $link) => $link->relationLoaded('post') && $link->relationLoaded('user')));
});

it('does not load community preview images on the homepage', function () {
    $link = Link::factory()->approved()->create([
        'image_url' => 'https://example.com/large-community-preview.jpg',
    ]);

    get(route('home'))
        ->assertOk()
        ->assertSee($link->title)
        ->assertDontSee($link->image_url, escape: false);

    get(route('links.index'))
        ->assertOk()
        ->assertSee($link->image_url, escape: false);
});

it('omits the homepage calls to action and about links', function () {
    get(route('home'))
        ->assertDontSee('Who the F are you?')
        ->assertDontSee('Start reading')
        ->assertDontSee('About me')
        ->assertDontSee(route('home') . '#about', escape: false)
        ->assertViewMissing('aboutUser');
});
