<x-app
    :canonical="$post['canonical']"
    :description="$post['description']"
    :image="$post['image']"
    :title="$post['title']"
>
    <article class="mt-16">
        <div class="container break-all lg:max-w-screen-md">
            @if ($post['image'])
                <img src="{{ $post['image'] }}" alt="{{ $post['title']  }}" class="object-cover w-full shadow-xl ring-1 ring-black/5 rounded-xl aspect-video" />
            @endif
        </div>

        <h1 class="container mt-8 font-medium tracking-tighter text-center text-black text-balance text-3xl/tight md:mt-16 sm:text-4xl/tight md:text-5xl/tight lg:text-6xl/tight">
            {{ $post['title'] }}
        </h1>

        <x-prose class="container mt-8 md:mt-16">
            @if (! empty($headings = extract_headings_from_markdown($post['content'])))
                <div class="p-4 pt-6 mb-8 bg-gray-100 not-prose md:mb-16 md:p-8 md:pt-10 rounded-xl">
                    <div class="text-sm font-bold tracking-widest text-center text-black uppercase">
                        Table of contents
                    </div>

                    <x-table-of-contents :$headings class="grid mt-4" />
                </div>
            @endif

            {!! Str::markdown($post['content']) !!}
        </x-prose>
    </article>

    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "Article",
            "author": {
                "@type": "Person",
                "name": "Benjamin Crozat",
                "url": "{{ route('home') }}#about"
            },
            "headline": "{{ $post['title'] }}",
            "description": "{{ $post['description'] }}",
            "image": "{{ $post['image'] }}",
            "datePublished": "{{ $post['published_at']->toIso8601String() }}",
            "dateModified": "{{ $post['modified_at']?->toIso8601String() ?? $post['published_at']->toIso8601String() }}"
        }
    </script>
</x-app>
