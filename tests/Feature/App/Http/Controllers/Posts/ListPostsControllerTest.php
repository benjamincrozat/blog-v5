<?php

use App\Models\Post;

use function Pest\Laravel\get;

use Illuminate\Pagination\LengthAwarePaginator;

it('lists posts', function () {
    get(route('posts.index'))
        ->assertOk()
        ->assertViewIs('posts.index')
        ->assertViewHas('posts', fn (LengthAwarePaginator $posts) => true)
        ->assertViewHas('breadcrumbs', [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Blog'],
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
                ],
            ],
        ]);
});

it('publishes clean self-canonicals and rejects empty archive pages', function () {
    Post::factory(25)->create(['published_at' => now()]);

    get(route('posts.index', ['utm_source' => 'test']))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="' . route('posts.index') . '" />', escape: false);

    get(route('posts.index', ['page' => 2, 'utm_source' => 'test']))
        ->assertOk()
        ->assertSee('<link rel="canonical" href="' . route('posts.index', ['page' => 2]) . '" />', escape: false);

    get(route('posts.index', ['page' => 3]))
        ->assertNotFound();
});
