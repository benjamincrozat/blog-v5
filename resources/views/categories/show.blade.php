{{--
Displays the categories show view.
--}}

<x-app
    :title="$category->name . ' articles, news, takes, and tutorials'"
    :description="'Browse articles, news, takes, and tutorials about ' . $category->name . ' for web developers.'"
>
    <article class="container xl:max-w-(--breakpoint-lg)">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-12 md:mb-14" />

        <x-typography.headline>
            Stay ahead in <span class="text-blue-600">{{ $category->name }}</span>
        </x-typography.headline>

        <x-typography.subheadline class="mt-6 md:mt-10">
            Articles, news, takes, and tutorials for web developers who want to keep up with {{ $category->name }}.
        </x-typography.subheadline>

        <x-posts-grid :$posts class="mt-16 md:mt-24" />

        <x-pagination
            :paginator="$posts"
            class="mt-16"
        />
    </article>

    <script type="application/ld+json">
        {!! json_encode($breadcrumbSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-app>
