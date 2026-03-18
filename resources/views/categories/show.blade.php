{{--
Displays the categories show view.
--}}

<x-app
    :title="'Articles, news, takes, and tutorials about ' . $category->name"
    :description="'Browse articles, news, takes, and tutorials about ' . $category->name . ' for web developers.'"
>
    <article class="container">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-12 md:mb-14" />

        <x-typography.headline class="mx-auto max-w-screen-md">
            Articles, news, takes, and tutorials about <span class="text-blue-600">{{ $category->name }}</span>
        </x-typography.headline>

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
