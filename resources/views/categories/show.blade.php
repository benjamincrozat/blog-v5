{{--
Displays the categories show view.
--}}

<x-app
    :title="$isNewsCategory ? 'Latest web development news' : 'The best articles about ' . $category->name . ' in ' . date('Y')"
    :description="$isNewsCategory
        ? 'Follow current web development news, releases, and notable updates from ' . config('app.name') . '.'
        : 'Level up in ' . date('Y') . ' as a web developer with this collection of articles I wrote about ' . $category->name . '.'"
>
    <article class="container">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-6" />

        @if ($posts->currentPage() === 1)
            <x-typography.heading>
                {{ $isNewsCategory ? 'Latest web development news' : 'Articles in the ' . $category->name . ' category' }}
            </x-typography.heading>
        @else
            <x-typography.heading>
                {{ $isNewsCategory ? 'More web development news' : 'Page ' . $posts->currentPage() }}
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
