<x-app
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

        <x-prose class="container mt-16">
            @if (! empty($headings = extract_headings_from_markdown($post['content'])))
                <div class="font-bold tracking-widest text-center text-black uppercase">
                    Table of contents
                </div>

                <x-table-of-contents :$headings />
            @endif

            {!! Str::markdown($post['content']) !!}
        </x-prose>
    </article>
</x-app>
