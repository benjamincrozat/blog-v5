{{--
Displays the authors show view.
--}}

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

            <p class="mx-auto mt-4 max-w-prose text-center text-sm leading-relaxed text-gray-500">
                This profile lists {{ $author->name }}'s published articles and approved links on {{ config('app.name') }}, a site covering web development news, techniques, and tools. Sponsored articles are labeled clearly on the article page.
            </p>
        </header>

        @if ($author->biography)
            <x-prose class="mt-6 md:mt-8">
                {!! \App\Markdown\MarkdownRenderer::parse($author->about) !!}
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

    @php
        $profileSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfilePage',
            'url' => url()->current(),
            'mainEntity' => array_filter([
                '@type' => 'Person',
                'name' => $author->name,
                'image' => $author->avatar,
                'url' => url()->current(),
                'description' => $description,
                'sameAs' => array_values(array_filter([
                    data_get($author->github_data, 'user.html_url'),
                    $author->blog_url,
                ])),
                'worksFor' => filled($author->company) ? [
                    '@type' => 'Organization',
                    'name' => $author->company,
                ] : null,
            ], function (mixed $value) {
                if (is_array($value)) {
                    return [] !== $value;
                }

                return ! is_null($value) && '' !== $value;
            }),
        ];
    @endphp

    <script type="application/ld+json">
        {!! json_encode($profileSchema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
</x-app>
