<?php

use function Pest\Laravel\get;

it('shows the advertising landing page', function () {
    get(route('advertise'))
        ->assertOk()
        ->assertViewIs('advertise')
        ->assertViewHas('views')
        ->assertViewHas('sessions')
        ->assertViewHas('desktop')
        ->assertViewHas('breadcrumbs', [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Advertise'],
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
                    'name' => 'Advertise',
                ],
            ],
        ]);
});
