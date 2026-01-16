@php
$description = Str::limit(
    strip_tags(Markdown::parse($author->about)),
    160
);
@endphp

<x-app
    title="About {{ $author->name }}"
    :description="$description"
    :image="filled($author->avatar) ? $author->avatar : Vite::asset('resources/img/apple-touch-icon.png')"
>
    <article class="container lg:max-w-(--breakpoint-md)">
        <header>
            <img
                loading="lazy"
                src="{{ $author->avatar }}"
                alt="{{ $author->name }}"
                class="mx-auto mt-1 rounded-full size-16"
            />

            <h1 class="mt-2 font-semibold text-center text-xl/tight">
                {{ $author->name }}
            </h1>

            @if ($author->company)
                <p class="text-center text-gray-400 text-lg/tight">
                    {{ $author->company }}
                </p>
            @endif
        </header>

        @if ($author->biography)
            <x-prose class="mt-6 md:mt-8">
                {!! Markdown::parse($author->about) !!}
            </x-prose>
        @endif
    </article>

    <x-section title="Articles by {{ $author->name }}" class="mt-12 md:mt-16">
        @if ($posts->isNotEmpty())
            <x-posts-grid :$posts />

            <x-pagination
                :paginator="$posts"
                class="mt-16"
            />
        @else
            <p class="-mt-4 text-center text-gray-500">
                So far, {{ $author->name }} didn't write any article.
            </p>
        @endif
    </x-section>

    <x-section title="Links sent by {{ $author->name }}" class="mt-12 md:mt-16">
        @if ($links->isNotEmpty())
            <x-links-grid :$links />

            <x-pagination
                :paginator="$links"
                class="mt-16"
            />
        @else
            <p class="-mt-4 text-center text-gray-500">
                So far, {{ $author->name }} didn't send any link.
            </p>
        @endif
    </x-section>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org",
            "@type": "Person",
            "name": "{{ $author->name }}",
            "image": "{{ $author->avatar }}",
            "url": "{{ url()->current() }}",
            "description": "{{ $description }}",
            "sameAs": [
                "{{ $author->github_data['user']['html_url'] ?? '' }}"
            ]
        }
    </script>
</x-app>
