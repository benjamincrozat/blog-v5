@php
    $breadcrumbs = [
        ['label' => 'Home', 'url' => route('home')],
        ['label' => 'Categories', 'url' => route('categories.index')],
        ['label' => $category->name, 'url' => route('categories.show', $category)],
    ];

    $breadcrumbSchemaItems = [];

    foreach ($breadcrumbs as $index => $breadcrumb) {
        $breadcrumbSchemaItems[] = [
            '@type' => 'ListItem',
            'position' => $index + 1,
            'name' => $breadcrumb['label'],
            'item' => $breadcrumb['url'],
        ];
    }

    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $breadcrumbSchemaItems,
    ];
@endphp

<x-app
    title="The best articles about {{ $category->name }} in {{ date('Y') }}"
    description="Level up in {{ date('Y') }} as a web developer with this collection of articles I wrote about {{ $category->name }}."
>
    <article class="container">
        <x-breadcrumbs :items="$breadcrumbs" class="justify-center mb-6" />

        @if ($posts->currentPage() === 1)
            <x-typography.heading>
                Articles in the {{ $category->name }} category
            </x-typography.heading>
        @else
            <x-typography.heading>
                Page {{ $posts->currentPage() }}
            </x-typography.heading>
        @endif

        <x-posts-grid :$posts class="mt-10" />

        <x-pagination
            :paginator="$posts"
            class="mt-16"
        />
    </article>

    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-app>
