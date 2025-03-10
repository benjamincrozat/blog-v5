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

            <span class="text-black underline underline-offset-4 decoration-black/30">
                Benjamin Crozat
            </span>
        </div>

        <h1 class="container mt-4 font-medium tracking-tight text-center text-black text-balance text-3xl/none sm:text-4xl/none md:text-5xl/none lg:text-6xl/none">
            {{ $post['title'] }}
        </h1>

        <x-prose class="container mt-8 md:mt-16">
            <div class="not-prose">
                <div class="grid grid-cols-2 gap-4 mb-4 sm:grid-cols-3">
                    <div class="flex-1 px-4 py-6 text-center rounded-lg text-sm/tight md:text-base/tight bg-gray-50">
                        <x-heroicon-o-calendar class="mx-auto mb-2 size-8" />
                        Published<br />
                        {{ $post['published_at']->isoFormat('LL') }}
                    </div>

                    <div class="flex-1 px-4 py-6 text-center rounded-lg text-sm/tight md:text-base/tight bg-gray-50">
                        <x-heroicon-o-chat-bubble-oval-left-ellipsis class="mx-auto mb-2 size-8" />
                        {{ $post['comments_count'] }}<br />
                        {{ trans_choice('comment|comments', $post['comments_count']) }}
                    </div>

                    <div class="flex-1 px-4 py-6 text-center rounded-lg text-sm/tight md:text-base/tight bg-gray-50">
                        <x-heroicon-o-clock class="mx-auto mb-2 size-8" />
                        {{ $readTime }} min<br />
                        read
                    </div>
                </div>

                <div class="px-4 py-6 rounded-lg bg-gray-50">
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
