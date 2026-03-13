{{--
Displays the categories show view.
--}}

<x-app
    :title="'The best articles about ' . $category->name . ' in ' . date('Y')"
    :description="'Level up in ' . date('Y') . ' as a web developer with this collection of articles I wrote about ' . $category->name . '.'"
>
    <article class="container">
        <x-breadcrumbs :items="$breadcrumbs" class="mb-6" />

        <x-typography.heading>
            {{ $category->name }}
        </x-typography.heading>

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
