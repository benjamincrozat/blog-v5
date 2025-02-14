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

        <div class="m-0 mt-8 text-center md:mt-16">
            <img
                src="https://www.gravatar.com/avatar/d58b99650fe5d74abeb9d9dad5da55ad?s=256"
                alt="Benjamin Crozat"
                class="mx-auto mb-2 rounded-full ring-1 ring-black/5 size-10"
            />

            <span class="font-medium underline decoration-black/30 decoration-1 underline-offset-4">Benjamin Crozat</span>

            <br />

            <time datetime="{{ $post['published_at']->toIso8601String() }}">
                @if ($post['modified_at'])
                    Updated on {{ $post['modified_at']->isoFormat('LL') }}
                @else
                    Published on {{ $post['published_at']->isoFormat('LL') }}
                @endif
            </time>
        </div>

        <h1 class="container mt-4 font-medium tracking-tight text-center text-black text-balance text-3xl/none sm:text-4xl/none md:text-5xl/none lg:text-6xl/none">
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
