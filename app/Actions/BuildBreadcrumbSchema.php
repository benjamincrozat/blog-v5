<?php

namespace App\Actions;

/**
 * Builds a schema.org breadcrumb payload from route breadcrumbs.
 */
class BuildBreadcrumbSchema
{
    /**
     * @param  array<int, array{label: string, url: string}>  $breadcrumbs
     * @return array<string, mixed>
     */
    public function handle(array $breadcrumbs) : array
    {
        $items = [];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['label'],
                'item' => $breadcrumb['url'],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
