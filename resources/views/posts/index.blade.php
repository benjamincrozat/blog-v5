{{--
Displays the posts index view.
--}}

<x-app title="The latest articles about web development in {{ date('Y') }}">
    <div class="container">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-8 md:mb-10" />
    </div>

    <x-section
        :title="$posts->currentPage() > 1
            ? 'Page ' . $posts->currentPage()
            : 'Latest'"
        heading-tag="h1"
        :big-title="$posts->currentPage() === 1"
    >
        @if ($posts->isNotEmpty())
            <x-posts-grid :$posts />
        @endif

        <x-pagination
            :paginator="$posts"
            class="mt-16"
        />
    </x-section>

    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-app>
