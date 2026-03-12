<?php

namespace App\Actions;

/**
 * Builds a schema.org breadcrumb payload from route breadcrumbs.
 */
class BuildBreadcrumbSchema
{
    /**
     * @param  array<int, array{label: string, url?: string|null}>  $breadcrumbs
     * @return array<string, mixed>
     */
    public function handle(array $breadcrumbs) : array
    {
        $items = [];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $item = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['label'],
            ];

            if (! empty($breadcrumb['url'])) {
                $item['item'] = $breadcrumb['url'];
            }

            $items[] = $item;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }
}
