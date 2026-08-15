<?php

use App\Actions\BuildBreadcrumbSchema;

it('omits the current page URL from the breadcrumb schema', function () {
    $schema = app(BuildBreadcrumbSchema::class)->handle([
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Articles', 'url' => route('posts.index')],
        ['label' => 'Laravel'],
    ]);

    expect($schema)->toBe([
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
                'name' => 'Articles',
                'item' => route('posts.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => 'Laravel',
            ],
        ],
    ]);
});
