<?php

namespace App\Actions;

/**
 * Builds the schema.org breadcrumb data used by public pages.
 *
 * Controllers give it labels and optional links in display order. It numbers
 * each item and leaves the current page without a link, so every page produces
 * the same JSON-LD shape.
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
