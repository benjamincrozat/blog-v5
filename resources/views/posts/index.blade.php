<x-app>
    <x-breadcrumbs class="container xl:max-w-screen-lg">
        <x-breadcrumbs.item>
            Posts
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    <x-section :title="$posts->currentPage() > 1 ? 'Page ' . $posts->currentPage() : 'Latest posts'" class="mt-8">
        @if ($posts->isNotEmpty())
            <ul class="grid gap-10 mt-8 gap-y-16 xl:gap-x-16 md:grid-cols-2 xl:grid-cols-3">
                @foreach ($posts as $post)
                    <li>
                        <x-post :$post />
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($posts->hasPages())
            <div class="mt-16">
                {{ $posts->links() }}
            </div>
        @endif
    </x-section>
</x-app>
