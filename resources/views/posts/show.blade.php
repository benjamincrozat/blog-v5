<x-app
    :canonical="$post['canonical']"
    :description="$post['description']"
    :image="$post['image']"
    :title="$post['title']"
>
    <x-breadcrumbs class="container xl:max-w-screen-lg">
        <x-breadcrumbs.item href="{{ route('posts.index') }}">
            Posts
        </x-breadcrumbs.item>

        <x-breadcrumbs.item class="line-clamp-1">
            {{ $post['title'] }}
        </x-breadcrumbs.item>
    </x-breadcrumbs>

    <article class="mt-8">
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

            Benjamin Crozat

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
            <div class="not-prose">
                <div class="px-4 py-6 bg-gray-100 rounded-lg">
                    <div class="text-sm font-bold tracking-widest text-center text-black uppercase">
                        Table of contents
                    </div>

                    <x-table-of-contents :headings="extract_headings_from_markdown($post['content'])" class="mt-4 ml-0" />
                </div>
            </div>

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
