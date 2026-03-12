<?php

use function Pest\Laravel\get;

it('shows the tools index with breadcrumbs', function () {
    get(route('tools.index'))
        ->assertOk()
        ->assertViewIs('tools.index')
        ->assertViewHas('breadcrumbs', [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Tools'],
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
                    'name' => 'Tools',
                ],
            ],
        ]);
});
