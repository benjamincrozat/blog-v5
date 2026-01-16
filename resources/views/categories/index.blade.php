<x-app
    title="Categories"
    description="Level up as a web developer in {{ date('Y') }} with this collection of articles I wrote sorted by category."
>
    <x-section
        title="Categories"
        heading-tag="h1"
        :big-title="true"
        class="xl:max-w-(--breakpoint-lg)"
    >
        @if ($categories->isNotEmpty())
            <div class="grid gap-10 gap-y-16 xl:gap-x-16 md:grid-cols-2">
                @foreach ($categories as $category)
                    <div>
                        <h2 class="font-bold tracking-widest text-black uppercase text-balance">
                            <a wire:navigate href="{{ route('categories.show', $category) }}" class="underline decoration-1 underline-offset-8 decoration-black/30">
                                {{ $category->name }} →
                            </a>
                        </h2>

                        <ul class="grid gap-8 mt-6 md:gap-6">
                            @foreach ($category->activity as $post)
                                <li>
                                    <x-compact-post :$post />
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        @endif
    </x-section>
</x-app>
