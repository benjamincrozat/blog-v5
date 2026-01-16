<x-app title="The latest articles about web development in {{ date('Y') }}">
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
</x-app>
