<?php

use App\Models\Tool;

use function Pest\Laravel\get;

it('shows the tools index with breadcrumbs and published tools', function () {
    $publishedTool = Tool::factory()->create([
        'name' => 'Remodex',
        'slug' => 'remodex',
        'published_at' => now()->subHour(),
    ]);

    Tool::factory()->create([
        'name' => 'Draft Tool',
        'slug' => 'draft-tool',
        'published_at' => now()->addHour(),
    ]);

    get(route('tools.index'))
        ->assertOk()
        ->assertViewIs('tools.index')
        ->assertSee('Remodex')
        ->assertDontSee('Draft Tool')
        ->assertViewHas('breadcrumbs', [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Tools'],
        ])
        ->assertViewHas('tools', fn ($tools) => $tools->contains($publishedTool))
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
