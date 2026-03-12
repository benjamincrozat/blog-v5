<?php

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
