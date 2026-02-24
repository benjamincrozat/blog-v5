{{--
Displays the components link component and accepts component props, Blade attributes, and slot content.
--}}

@props(['link'])

<div {{ $attributes }}>
    <a
        href="{{ $link->url }}"
        target="_blank"
        data-pirsch-event="Clicked link image"
        data-pirsch-meta-title="{{ $link->title }}"
    >
        @if ($link->image_url)
            <img
                fetchpriority="high"
                src="{{ $link->image_url }}"
                alt="{{ $link->title  }}"
                class="object-cover rounded-xl ring-1 shadow-md transition-opacity shadow-black/5 aspect-video hover:opacity-50 ring-black/5"
            />
        @else
            @php
            $bgColors = collect([
                'bg-amber-600', 'bg-blue-600', 'bg-cyan-600', 'bg-emerald-600', 'bg-gray-600', 'bg-green-600', 'bg-indigo-600', 'bg-lime-600', 'bg-pink-600', 'bg-purple-600', 'bg-red-600', 'bg-sky-600', 'bg-teal-600', 'bg-yellow-600',
            ])->random();
            @endphp

            <div class="transition-opacity {{ $bgColors }} shadow-md ring-1 ring-black/5 hover:opacity-50 aspect-video rounded-xl shadow-black/5"></div>
        @endif
    </a>

    <div class="flex gap-2 items-center mt-4">
        @if ($link->post)
            <a wire:navigate href="{{ route('posts.show', $link->post) }}" class="underline underline-offset-4 decoration-gray-600/30 decoration-1">
        @endif
                <time datetime="{{ $link->is_approved }}">
                    {{ $link->is_approved->isoFormat('LL') }}
                </time>
        @if ($link->post)
            </a>
        @endif

        <a
            href="{{ $link->user->github_data['user']['html_url'] }}"
            target="_blank"
            class="flex items-center"
            data-pirsch-event="Clicked link author"
            data-pirsch-meta-name="{{ $link->user->name }}"
        >
            <span class="mr-2 text-xs opacity-50">
                /
            </span>

            <span class="text-black underline underline-offset-4 decoration-black/30">
                {{ $link->user->name }}
            </span>
        </a>
    </div>

    <div class="flex gap-6 justify-between items-center mt-2">
        <a
            href="{{ $link->url }}"
            target="_blank"
            class="font-bold transition-colors text-xl/tight hover:text-blue-600"
            data-pirsch-event="Clicked link title"
            data-pirsch-meta-title="{{ $link->title }}"
        >
            {{ $link->title }}
        </a>

        <img
            loading="lazy"
            src="{{ $link->user->avatar }}"
            alt="{{ $link->user->name }}"
            class="rounded-full ring-1 ring-black/5 size-10"
        />
    </div>

    <div class="mt-2">
        {!! \App\Markdown\MarkdownRenderer::parse($link->description ?? '') !!}
    </div>
</div>
